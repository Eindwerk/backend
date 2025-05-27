<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

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
 *             @OA\Property(property="user", type="object")
 *         )
 *     ),
 *     @OA\Response(response=422, description="Validatiefout"),
 *     @OA\Response(response=401, description="Niet geauthenticeerd")
 * )
 */
class UserProfileController extends Controller
{
    /**
     * POST /api/users/profile
     * Content-Type: multipart/form-data
     */
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        // Username bijwerken
        if (isset($data['username'])) {
            $user->username = $data['username'];
        }

        // Profielafbeelding bijwerken
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $user->profile_image = $request->file('profile_image')
                ->store('users/profile-image', 'public');
        }

        // Bannerafbeelding bijwerken
        if ($request->hasFile('banner_image')) {
            if ($user->banner_image) {
                Storage::disk('public')->delete($user->banner_image);
            }

            $user->banner_image = $request->file('banner_image')
                ->store('users/banner-image', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'Profiel succesvol bijgewerkt.',
            'user' => $user->only(['id', 'username', 'profile_image', 'banner_image']),
        ]);
    }
}
