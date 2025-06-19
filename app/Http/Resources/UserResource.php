<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "username", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Cédric Van Hoorebeke"),
 *     @OA\Property(property="username", type="string", example="cedrictest"),
 *     @OA\Property(property="email", type="string", format="email", example="cedric@example.com"),
 *     @OA\Property(property="role", type="string", example="user"),
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="https://s3-url.com/image.jpg"),
 *     @OA\Property(property="banner_image", type="string", nullable=true, example="https://s3-url.com/banner.jpg"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, example="2025-06-18T12:34:56Z"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-18T12:34:56Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-18T12:34:56Z")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id'                => $this->id,
            'name'              => $this->name,
            'username'          => $this->username,
            'email'             => $this->email,
            'role'              => $this->role,
            'profile_image'     => $this->profile_image ? $disk->url($this->profile_image) : null,
            'banner_image'      => $this->banner_image ? $disk->url($this->banner_image) : null,
            'email_verified_at' => $this->email_verified_at,
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
