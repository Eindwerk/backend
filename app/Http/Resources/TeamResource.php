<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Team",
 *     required={"name", "league"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Borussia Dortmund"),
 *     @OA\Property(property="league", type="string", example="Bundesliga"),
 *     @OA\Property(property="logo_url", type="string", format="url", nullable=true, example="https://admin.groundpass.be/uploads/teams/profile-image/logo.png"),
 *     @OA\Property(property="banner_image", type="string", format="url", nullable=true, example="https://admin.groundpass.be/uploads/teams/banner-image/banner.png"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
 * )
 */
class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'league'        => $this->league,
            'logo_url'      => $this->logo_url,
            'banner_image'  => $this->banner_image,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
