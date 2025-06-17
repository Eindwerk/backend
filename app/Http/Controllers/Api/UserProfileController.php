<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
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

        if (!empty($data['username'])) {
            $user->username = $data['username'];
        }

        // Profielafbeelding
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users/profile-image', $filename, 'public');
            $user->profile_image = $path;
        }

        // Bannerafbeelding
        if ($request->hasFile('banner_image')) {
            if ($user->banner_image && Storage::disk('public')->exists($user->banner_image)) {
                Storage::disk('public')->delete($user->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users/banner-image', $filename, 'public');
            $user->banner_image = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profiel succesvol bijgewerkt.',
            'user' => new UserResource($user),
        ]);
    }
}
