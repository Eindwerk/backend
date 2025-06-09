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
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'username' => $this->username,
            'email' => $this->email,
            'profile_image' => $this->getProfileImageUrl(),
            'banner_image' => $this->getBannerImageUrl(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }

    private function getProfileImageUrl(): ?string
    {
        if ($this->profile_image && Storage::disk('public')->exists($this->profile_image)) {
            return asset('storage/' . $this->profile_image);
        }

        // Geef null terug of een fallback-afbeelding
        return asset('storage/defaults/profile.jpg'); // ← pas dit aan of gebruik null
    }

    private function getBannerImageUrl(): ?string
    {
        if ($this->banner_image && Storage::disk('public')->exists($this->banner_image)) {
            return asset('storage/' . $this->banner_image);
        }

        // Geef null terug of een fallback-afbeelding
        return asset('storage/defaults/banner.jpg'); // ← pas dit aan of gebruik null
    }
}
