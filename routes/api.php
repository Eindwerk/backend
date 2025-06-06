<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\Verified;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;

use App\Models\User;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\FollowController;
use App\Http\Controllers\{
    StadiumController,
    TeamController,
    GameController,
    VisitController,
    PostController,
    CommentController,
    LikeController,
    NotificationController
};
use App\Http\Middleware\ApiKeyMiddleware;

/*
|--------------------------------------------------------------------------
| REGISTRATIE
|--------------------------------------------------------------------------
|
| Bij registratie genereren we de gebruiker, slaan direct een Sanctum‐token
| op en sturen een e-mail met onze FrontendVerifyEmail-notificatie.
|
*/

Route::post('/register', function (Request $request) {
    $request->validate([
        'name'     => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email'    => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name'     => $request->name,
        'username' => $request->username,
        'email'    => $request->email,
        'password' => bcrypt($request->password),
    ]);

    // Trigger onze aangepaste notificatie (genereert token en stuurt e-mail)
    $user->sendEmailVerificationNotification();

    return response()->json([
        'token'   => $user->createToken('api-token')->plainTextToken,
        'user'    => $user,
        'message' => 'Verificatiemail verzonden. Bevestig je e-mailadres om volledige toegang te krijgen.',
    ]);
});

/*
|--------------------------------------------------------------------------
| LOGIN
|--------------------------------------------------------------------------
|
| We laten nu ook ongeverifieerde gebruikers inloggen, zodat de frontend
| na het inloggen alsnog de e-mail kan verifiëren via het nieuwe endpoint.
| Zodra de e-mail geverifieerd is, kunnen de frontend‐protected routes
| (middlware ’verified’) wél gebruikt worden.
|
*/
Route::post('/login', function (Request $request) {
    $request->validate([
        'email'    => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    // ❌ Haal de volgende check weg, zodat ongeverifieerde gebruikers wél kunnen inloggen:
    // if (! $user->hasVerifiedEmail()) {
    //     return response()->json(['message' => 'Bevestig eerst je e-mailadres.'], 403);
    // }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user'  => $user,
    ]);
});

/*
|--------------------------------------------------------------------------
| LOGOUT & "ME"
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Uitgelogd.']);
});

Route::middleware('auth:sanctum')->get('/me', fn(Request $request) => $request->user());

/*
|--------------------------------------------------------------------------
| E-MAILVERIFICATIE: TOKEN-BASIS FLOW
|--------------------------------------------------------------------------
|
| 1) Frontend roept /email/verification-notification aan om opnieuw token te sturen.
| 2) Nieuw endpoint POST /email/verify: controleert de token en markeert e-mail.
|
*/

/** 1) Resend Verification-mail (blijf dit endpoint behouden) */
Route::middleware(['auth:sanctum', 'throttle:6,1'])
    ->post('/email/verification-notification', function (Request $request) {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'E-mailadres is al bevestigd.']);
        }

        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verificatiemail opnieuw verzonden.']);
    });

/** 2) Nieuw endpoint voor front-driven verificatie */
Route::middleware(['auth:sanctum'])->post('/email/verify', function (Request $request) {
    $request->validate([
        'verify_token' => 'required|string',
    ]);

    /** @var User $user */
    $user = $request->user();

    if ($user->hasVerifiedEmail()) {
        return response()->json(['message' => 'E-mailadres is al geverifieerd.'], 200);
    }

    // Her‐hash de plain token en vergelijk met wat in de DB staat
    $hashed = hash_hmac('sha256', $request->verify_token, config('app.key'));

    if (
        $user->email_verification_token !== $hashed ||
        ! $user->email_verification_token_expires_at ||
        now()->greaterThan($user->email_verification_token_expires_at)
    ) {
        return response()->json(['message' => 'Ongeldige of verlopen verificatietoken.'], 403);
    }

    // Alles klopt: zet e-mail als verified, wis de tokenvelden, sla op
    $user->markEmailAsVerified();
    $user->email_verification_token = null;
    $user->email_verification_token_expires_at = null;
    $user->save();

    event(new Verified($user));

    return response()->json(['message' => 'E-mailadres succesvol geverifieerd.'], 200);
});

/*
|--------------------------------------------------------------------------
| WACHTWOORD VERGETEN
|--------------------------------------------------------------------------
*/

use App\Notifications\ResetPasswordLink;

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);

    $user = \App\Models\User::where('email', $request->email)->first();
    if (! $user) {
        return response()->json(['message' => 'Gebruiker niet gevonden.'], 404);
    }

    $token = Password::createToken($user);
    $user->notify(new ResetPasswordLink($token, $user->email));

    return response()->json(['message' => 'Resetlink verzonden.']);
});

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'email'                 => 'required|email',
        'token'                 => 'required',
        'password'              => 'required|string|min:6|confirmed',
    ]);

    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user) use ($request) {
            $user->forceFill([
                'password' => bcrypt($request->password),
            ])->save();

            event(new PasswordReset($user));
        }
    );

    return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => 'Wachtwoord succesvol gereset.'])
        : response()->json(['message' => __($status)], 500);
});

/*
|--------------------------------------------------------------------------
| PROFIEL
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', ApiKeyMiddleware::class])
    ->post('/users/profile', [UserProfileController::class, 'update']);

/*
|--------------------------------------------------------------------------
| FOLLOW SYSTEM
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', ApiKeyMiddleware::class])->group(function () {
    Route::post('/follow',    [FollowController::class, 'follow']);
    Route::delete('/unfollow', [FollowController::class, 'unfollow']);
    Route::get('/following',  [FollowController::class, 'index']);
});

/*
|--------------------------------------------------------------------------
| BEVEILIGDE API RESOURCES
|--------------------------------------------------------------------------
|
| We blijven hier de ’verified’ middleware gebruiken, zodat alleen
| gebruikers met een geverifieerd e-mailadres deze routes kunnen aanspreken.
|
*/
Route::middleware(['auth:sanctum', 'verified', ApiKeyMiddleware::class])->group(function () {
    Route::apiResource('stadiums', StadiumController::class);
    Route::apiResource('teams',    TeamController::class);
    Route::apiResource('games',    GameController::class);
    Route::apiResource('visits',   VisitController::class);
    Route::apiResource('posts',    PostController::class);

    Route::post('comments',                [CommentController::class, 'store']);
    Route::delete('comments/{comment}',    [CommentController::class, 'destroy']);

    Route::post('likes',                   [LikeController::class, 'store']);
    Route::delete('likes/{post_id}',       [LikeController::class, 'destroy']);

    Route::get('notifications',            [NotificationController::class, 'index']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
});
