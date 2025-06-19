<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="Stadium",
 *     type="object",
 *     required={"name", "latitude", "longitude"},
 *     @OA\Property(property="id", type="integer", example=15),
 *     @OA\Property(property="name", type="string", example="Allianz Arena"),
 *     @OA\Property(property="team_name", type="string", nullable=true, example="Bayern Munich"),
 *     @OA\Property(
 *         property="profile_image",
 *         type="string",
 *         format="uri",
 *         nullable=true,
 *         example="https://your-bucket.s3.region.amazonaws.com/uploads/stadiums/profile-image/abc.jpg"
 *     ),
 *     @OA\Property(
 *         property="banner_image",
 *         type="string",
 *         format="uri",
 *         nullable=true,
 *         example="https://your-bucket.s3.region.amazonaws.com/uploads/stadiums/banner-image/xyz.jpg"
 *     ),
 *     @OA\Property(property="latitude", type="string", example="48.2188"),
 *     @OA\Property(property="longitude", type="string", example="11.6247"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T12:00:00Z")
 * )
 */
class StadiumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'team_name' => $this->team?->name,
            'profile_image' => $this->profile_image ? $disk->url($this->profile_image) : null,
            'banner_image' => $this->banner_image ? $disk->url($this->banner_image) : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
