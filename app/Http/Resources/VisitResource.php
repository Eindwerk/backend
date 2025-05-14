<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Visit",
 *     required={"user_id", "game"},
 *     @OA\Property(property="id", type="integer", example=14),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="game", ref="#/components/schemas/Game"),
 *     @OA\Property(property="visited_at", type="string", format="date", nullable=true, example="2025-05-12"),
 *     @OA\Property(property="notes", type="string", nullable=true, example="Geweldige sfeer, veel pyro!"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:15:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:20:00Z")
 * )
 */
class VisitResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game' => new \App\Http\Resources\GameResource($this->whenLoaded('game')),
            'visited_at' => $this->visited_at,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
