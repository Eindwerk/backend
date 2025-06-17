<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Game",
 *     type="object",
 *     required={"stadium_id", "home_team_id", "away_team_id", "match_date"},
 *     @OA\Property(property="id", type="integer", example=1, readOnly=true),
 *     @OA\Property(property="stadium_id", type="integer", example=2),
 *     @OA\Property(property="home_team_id", type="integer", example=3),
 *     @OA\Property(property="away_team_id", type="integer", example=4),
 *     @OA\Property(property="match_date", type="string", format="date", example="2025-05-14"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, example="2025-05-14T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, example="2025-05-14T10:05:00Z")
 * )
 */
class GameResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'stadium_id' => $this->stadium_id,
            'home_team_id' => $this->home_team_id,
            'away_team_id' => $this->away_team_id,
            'match_date' => $this->match_date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'stadium' => new StadiumResource($this->whenLoaded('stadium')),
            'home_team' => new TeamResource($this->whenLoaded('homeTeam')),
            'away_team' => new TeamResource($this->whenLoaded('awayTeam')),
        ];
    }
}
