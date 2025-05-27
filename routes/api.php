<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Verified;
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
use App\Http\Controllers\Api\{
    UserProfileController,
    FollowController
};

// ----------------------------
// 🔐 AUTHENTICATIE
// ----------------------------

Route::post('/register', function (Request $request) {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users',
        'password' => 'required|string|min:6',
    ]);

    $user = \App\Models\User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
    ]);

    $user->sendEmailVerificationNotification();

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => $user,
    ]);
});

Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    $user = \App\Models\User::where('email', $request->email)->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Invalid credentials'], 401);
    }

    return response()->json([
        'token' => $user->createToken('api-token')->plainTextToken,
        'user' => $user,
    ]);
});

Route::middleware('auth:sanctum')->post('/logout', function (Request $request) {
    $request->user()->currentAccessToken()->delete();

    return response()->json(['message' => 'Uitgelogd.']);
});

Route::middleware('auth:sanctum')->get('/me', fn(Request $request) => $request->user());


// ----------------------------
// 📧 E-MAIL VERIFICATIE
// ----------------------------

Route::middleware(['auth:sanctum', 'throttle:6,1'])->post('/email/verification-notification', function (Request $request) {
    if ($request->user()->hasVerifiedEmail()) {
        return response()->json(['message' => 'E-mailadres is al bevestigd.']);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json(['message' => 'Verificatiemail opnieuw verzonden.']);
});

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
    $user = \App\Models\User::findOrFail($id);

    if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
        abort(403, 'Ongeldige verificatielink.');
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return response()->json([
        'message' => 'E-mailadres succesvol bevestigd.',
        'verified' => true,
    ]);
})->middleware(['signed'])->name('verification.verify');


// ----------------------------
// 👤 PROFIEL
// ----------------------------

Route::middleware('auth:sanctum')->post('/users/profile', [UserProfileController::class, 'update']);


// ----------------------------
// ➕ POST routes voor updates (i.p.v. PATCH voor form-data)
// ----------------------------

Route::middleware('auth:sanctum')->post('/stadiums/{stadium}', [StadiumController::class, 'update']);
Route::middleware('auth:sanctum')->post('/teams/{team}', [TeamController::class, 'update']);


// ----------------------------
// 🔁 FOLLOW SYSTEM
// ----------------------------

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/follow', [FollowController::class, 'follow']);
    Route::delete('/unfollow', [FollowController::class, 'unfollow']);
    Route::get('/following', [FollowController::class, 'index']);
});


// ----------------------------
// 🔐 BEVEILIGDE API RESOURCES
// Enkel voor geverifieerde gebruikers
// ----------------------------

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
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
