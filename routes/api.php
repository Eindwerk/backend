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

// 🔐 REGISTRATIE
Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'username' => 'required|string|max:255|unique:users,username',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|string|min:6',
    ]);

    $user = User::create([
        'name' => $request->name,
        'username' => $request->username,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    $user->sendEmailVerificationNotification();

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => $user,
        'message' => 'Verificatiemail verzonden. Bevestig je e-mailadres om in te loggen.',
    ]);
});

// 🔐 LOGIN
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    if (! $user->hasVerifiedEmail()) {
        return response()->json(['message' => 'Bevestig eerst je e-mailadres.'], 403);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => $user,
    ]);
});

// 🔓 LOGOUT
Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();
    return response()->json(['message' => 'Uitgelogd.']);
});

Route::middleware('auth:sanctum')->get('/me', fn(Request $request) => $request->user());

// 📧 VERIFICATIE
Route::middleware(['auth:sanctum', 'throttle:6,1'])->post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'E-mailadres is al bevestigd.']);
    }

    $request->user()->sendEmailVerificationNotification();
    return response()->json(['message' => 'Verificatiemail opnieuw verzonden.']);
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Ongeldige verificatielink.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect()->away('https://groundpass.be/confirmed');
})->middleware(['signed'])->name('verification.verify');

// 🔐 WACHTWOORD VERGETEN
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
        'email' => 'required|email',
        'token' => 'required',
        'password' => 'required|string|min:6|confirmed',
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

// 👤 PROFIEL
Route::middleware(['auth:sanctum', ApiKeyMiddleware::class])->post('/users/profile', [UserProfileController::class, 'update']);

// 🔁 FOLLOW SYSTEM
Route::middleware(['auth:sanctum', ApiKeyMiddleware::class])->group(function () {
    Route::post('/follow', [FollowController::class, 'follow']);
    Route::delete('/unfollow', [FollowController::class, 'unfollow']);
    Route::get('/following', [FollowController::class, 'index']);
});

// 🔐 BEVEILIGDE API RESOURCES
Route::middleware(['auth:sanctum', 'verified', ApiKeyMiddleware::class])->group(function () {
    Route::apiResource('stadiums', StadiumController::class);
    Route::apiResource('teams', TeamController::class);
    Route::apiResource('games', GameController::class);
    Route::apiResource('visits', VisitController::class);
    Route::apiResource('posts', PostController::class);

    Route::post('comments', [CommentController::class, 'store']);
    Route::delete('comments/{comment}', [CommentController::class, 'destroy']);

    Route::post('likes', [LikeController::class, 'store']);
    Route::delete('likes/{post_id}', [LikeController::class, 'destroy']);

    Route::get('notifications', [NotificationController::class, 'index']);
    Route::delete('notifications/{notification}', [NotificationController::class, 'destroy']);
});
