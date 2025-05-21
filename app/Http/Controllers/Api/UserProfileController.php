<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

/**
 * @OA\Patch(
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
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'username' => [
                'string',
                Rule::unique('users')->ignore($user->id),
            ],
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'banner_image' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
        ]);

        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->hasFile('profile_image')) {
            if ($user->profile_image) {
                Storage::disk('public')->delete($user->profile_image);
            }
            $user->profile_image = $request->file('profile_image')->store('profiles', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($user->banner_image) {
                Storage::disk('public')->delete($user->banner_image);
            }
            $user->banner_image = $request->file('banner_image')->store('banners', 'public');
        }

        $user->save();

        return response()->json([
            'message' => 'Profiel succesvol bijgewerkt.',
            'user' => $user, // Overweeg hier eventueel UserResource te gebruiken
        ]);
    }
}
