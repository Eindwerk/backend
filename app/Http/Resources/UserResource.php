<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Cédric Van Hoorebeke"),
 *     @OA\Property(property="username", type="string", example="cedricvh"),
 *     @OA\Property(property="email", type="string", example="cedric@example.com"),
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="https://backend.ddev.site/uploads/users/profile-image/1.jpg"),
 *     @OA\Property(property="banner_image", type="string", nullable=true, example="https://backend.ddev.site/uploads/users/banner-image/1.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class UserResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'              => $this->id,
            'name'            => $this->name,
            'username'        => $this->username,
            'email'           => $this->email,
            'role'            => $this->role,
            'profile_image'   => $this->profile_image ? url($this->profile_image) : null,
            'banner_image'    => $this->banner_image ? url($this->banner_image) : null,
            'email_verified_at' => $this->email_verified_at,
            'created_at'      => $this->created_at,
            'updated_at'      => $this->updated_at,
        ];
    }
}
