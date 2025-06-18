<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserProfileRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class UserProfileController extends Controller
{
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
}
