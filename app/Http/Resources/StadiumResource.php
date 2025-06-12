<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Stadium",
 *     required={"name", "team_id", "location"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Allianz Arena"),
 *     @OA\Property(property="team_name", type="string", example="Bayern München"),
 *     @OA\Property(property="profile_image", type="string", format="url", nullable=true, example="https://admin.groundpass.be/storage/stadiums/profile-image/logo.png"),
 *     @OA\Property(property="banner_image", type="string", format="url", nullable=true, example="https://admin.groundpass.be/storage/stadiums/banner-image/banner.png"),
 *     @OA\Property(
 *         property="location",
 *         type="object",
 *         required={"latitude", "altitude"},
 *         @OA\Property(property="latitude", type="number", format="float", example=48.218),
 *         @OA\Property(property="altitude", type="number", format="float", example=519)
 *     ),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
 * )
 */
class StadiumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'team_name'     => $this->team?->name,
            'profile_image' => $this->profile_image ? asset($this->profile_image) : null,
            'banner_image'  => $this->banner_image ? asset($this->banner_image) : null,
            'location'      => [
                'latitude' => $this->location['latitude'] ?? null,
                'altitude' => $this->location['altitude'] ?? null,
            ],
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
