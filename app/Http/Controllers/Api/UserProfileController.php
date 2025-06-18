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

        // Profielafbeelding
        if ($request->hasFile('profile_image')) {
            if ($user->profile_image && Storage::disk('public')->exists($user->profile_image)) {
                Storage::disk('public')->delete($user->profile_image);
            }

            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/users/profile-image', $filename, 'public');
            $user->profile_image = $path;
        }

        // Bannerafbeelding
        if ($request->hasFile('banner_image')) {
            if ($user->banner_image && Storage::disk('public')->exists($user->banner_image)) {
                Storage::disk('public')->delete($user->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('uploads/users/banner-image', $filename, 'public');
            $user->banner_image = $path;
        }

        $user->save();

        return response()->json([
            'message' => 'Profiel succesvol bijgewerkt.',
            'user' => new UserResource($user),
        ]);
    }
}
