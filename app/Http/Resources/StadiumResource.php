<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Stadium",
 *     required={"name", "city", "capacity"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Allianz Arena"),
 *     @OA\Property(property="city", type="string", example="München"),
 *     @OA\Property(property="capacity", type="integer", example=75000),
 *     @OA\Property(property="image", type="string", format="url", nullable=true, example="storage/profiles/stadium.jpg"),
 *     @OA\Property(property="banner_image", type="string", format="url", nullable=true, example="storage/banners/stadium_banner.jpg"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
 * )
 */
class StadiumResource extends JsonResource
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
            'city'          => $this->city,
            'capacity'      => $this->capacity,
            'image'         => $this->image,
            'banner_image'  => $this->banner_image,
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
