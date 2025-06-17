<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Post",
 *     required={"user_id", "game", "image"},
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="game", ref="#/components/schemas/Game"),
 *     @OA\Property(
 *         property="comments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comment")
 *     ),
 *     @OA\Property(
 *         property="likes",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Like")
 *     ),
 *     @OA\Property(property="image", type="string", format="url", example="https://groundpass.be/uploads/posts/images/abc123.jpg"),
 *     @OA\Property(property="title", type="string", example="Club Brugge vs Antwerp – Jan Breydelstadion"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:55:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:05:00Z")
 * )
 */
class PostResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game' => new GameResource($this->whenLoaded('game')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),

            'image' => $this->image_path,

            'title' => optional($this->game?->homeTeam)->name && optional($this->game?->awayTeam)->name && optional($this->game?->stadium)->name
                ? "{$this->game->homeTeam->name} vs {$this->game->awayTeam->name} – {$this->game->stadium->name}"
                : null,

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
