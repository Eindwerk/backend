<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Profiel",
 *     description="Gebruikersprofiel bewerken"
 * )
 */
class UserProfileController extends Controller
{
    /**
     * @OA\Post(
     *     path="/users/profile",
     *     summary="Update het profiel van de ingelogde gebruiker",
     *     tags={"Profiel"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"username"},
     *                 @OA\Property(property="username", type="string", example="newusername"),
     *                 @OA\Property(property="profile_image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profiel succesvol bijgewerkt",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function update(UpdateUserProfileRequest $request): JsonResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (!empty($data['username'])) {
            $user->username = $data['username'];
        }

        if ($request->hasFile('profile_image')) {
            $this->deleteImageIfExists($user->profile_image);
            $user->profile_image = $this->storeImage($request->file('profile_image'), 'users/profile-image');
        }

        if ($request->hasFile('banner_image')) {
            $this->deleteImageIfExists($user->banner_image);
            $user->banner_image = $this->storeImage($request->file('banner_image'), 'users/banner-image');
        }

        $user->save();

        return response()->json(new UserResource($user));
    }

    protected function deleteImageIfExists(?string $path): void
    {
        if ($path && Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }

    protected function storeImage($file, string $directory): string
    {
        return $file->store($directory, 's3');
    }
}
