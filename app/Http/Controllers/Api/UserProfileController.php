<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * @OA\Post(
 *     path="/api/users/profile",
 *     summary="Update het profiel van de ingelogde gebruiker",
 *     tags={"Profiel"},
 *     security={{"sanctum":{}}},
 *     @OA\RequestBody(
 *         required=false,
 *         @OA\MediaType(
 *             mediaType="multipart/form-data",
 *             @OA\Schema(
 *                 @OA\Property(property="username", type="string", example="cedric_123"),
 *                 @OA\Property(property="profile_image", type="file"),
 *                 @OA\Property(property="banner_image", type="file")
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Profiel succesvol bijgewerkt",
 *         @OA\JsonContent(
 *             @OA\Property(property="message", type="string", example="Profiel succesvol bijgewerkt."),
 *             @OA\Property(property="user", ref="#/components/schemas/User")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validatiefout"),
 *     @OA\Response(response=401, description="Niet geauthenticeerd")
 * )
 */
class UserProfileController extends Controller
{
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Gebruikersnaam bijwerken
        if (isset($data['username'])) {
            $user->username = $data['username'];
        }

        // Zorg dat de folders bestaan
        File::ensureDirectoryExists(public_path('uploads/users/profile-image'));
        File::ensureDirectoryExists(public_path('uploads/users/banner-image'));

        // Profielafbeelding bijwerken
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && file_exists(public_path($user->profile_image))) {
                unlink(public_path($user->profile_image));
            }

            $filename = Str::random(40) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move(public_path('uploads/users/profile-image'), $filename);
            $user->profile_image = 'uploads/users/profile-image/' . $filename;
        }

        // Bannerafbeelding bijwerken
        if ($request->hasFile('banner_image')) {
            if ($user->banner_image && file_exists(public_path($user->banner_image))) {
                unlink(public_path($user->banner_image));
            }

            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/users/banner-image'), $filename);
            $user->banner_image = 'uploads/users/banner-image/' . $filename;
        }

        $user->save();

        return response()->json([
            'message' => 'Profiel succesvol bijgewerkt.',
            'user' => new UserResource($user),
        ]);
    }
}
