<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="Team",
 *     required={"name", "league_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Borussia Dortmund"),
 *     @OA\Property(property="league_id", type="integer", example=4),
 *     @OA\Property(property="profile_image", type="string", format="url", nullable=true, example="https://admin.groundpass.be/storage/teams/profile-image/logo.png"),
 *     @OA\Property(property="banner_image", type="string", format="url", nullable=true, example="https://admin.groundpass.be/storage/teams/banner-image/banner.png"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
 * )
 */
class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'league_id'     => $this->league_id,
            'profile_image' => $this->profile_image ? Storage::url($this->profile_image) : null,
            'banner_image'  => $this->banner_image ? Storage::url($this->banner_image) : null,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
