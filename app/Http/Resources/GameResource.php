<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Game",
 *     required={"stadium_id", "home_team_id", "away_team_id", "match_date"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="stadium_id", type="integer", example=2),
 *     @OA\Property(property="home_team_id", type="integer", example=3),
 *     @OA\Property(property="away_team_id", type="integer", example=4),
 *     @OA\Property(property="home_score", type="integer", example=2, nullable=true),
 *     @OA\Property(property="away_score", type="integer", example=1, nullable=true),
 *     @OA\Property(property="match_date", type="string", format="date", example="2025-05-14"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:05:00Z")
 * )
 */
class GameResource extends JsonResource
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
            'stadium_id' => $this->stadium_id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'home_score' => $this->home_score,
            'away_score' => $this->away_score,
            'match_date' => $this->match_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
