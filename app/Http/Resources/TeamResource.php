<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="Team",
 *     type="object",
 *     required={"name", "league_id"},
 *     @OA\Property(property="id", type="integer", example=1, readOnly=true),
 *     @OA\Property(property="name", type="string", example="Manchester City"),
 *     @OA\Property(property="league_id", type="integer", example=2),
 *     @OA\Property(
 *         property="profile_image",
 *         type="string",
 *         format="uri",
 *         nullable=true,
 *         example="https://your-bucket.s3.region.amazonaws.com/teams/profile-image/abc.jpg"
 *     ),
 *     @OA\Property(
 *         property="banner_image",
 *         type="string",
 *         format="uri",
 *         nullable=true,
 *         example="https://your-bucket.s3.region.amazonaws.com/teams/banner-image/xyz.jpg"
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T10:30:00Z")
 * )
 */
class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'league_id'     => $this->league_id,
            'profile_image' => $this->profile_image ? $disk->url($this->profile_image) : null,
            'banner_image'  => $this->banner_image ? $disk->url($this->banner_image) : null,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
