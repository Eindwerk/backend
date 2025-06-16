<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Cédric Van Hoorebeke"),
 *     @OA\Property(property="username", type="string", example="cedricvh"),
 *     @OA\Property(property="email", type="string", example="cedric@example.com"),
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="https://backend.ddev.site/storage/users/profile-image/1.jpg"),
 *     @OA\Property(property="banner_image", type="string", nullable=true, example="https://backend.ddev.site/storage/users/banner-image/1.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'role' => $this->role,
            'profile_image' => $this->getProfileImageUrl(),
            'banner_image' => $this->getBannerImageUrl(),
            'email_verified_at' => $this->email_verified_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getProfileImageUrl(): ?string
    {
        return $this->profile_image && Storage::disk('public')->exists($this->profile_image)
            ? asset('storage/' . $this->profile_image)
            : null; // of asset('storage/defaults/profile.jpg');
    }

    private function getBannerImageUrl(): ?string
    {
        return $this->banner_image && Storage::disk('public')->exists($this->banner_image)
            ? asset('storage/' . $this->banner_image)
            : null; // of asset('storage/defaults/banner.jpg');
    }
}
