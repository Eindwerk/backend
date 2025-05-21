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
 *     @OA\Property(property="profile_image", type="string", nullable=true, example="storage/profiles/1.jpg"),
 *     @OA\Property(property="banner_image", type="string", nullable=true, example="storage/banners/1.jpg"),
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
            'profile_image' => $this->profile_image,
            'banner_image' => $this->banner_image,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
