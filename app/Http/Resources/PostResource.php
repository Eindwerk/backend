<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Schema(
 *     schema="Post",
 *     type="object",
 *     required={"user_id", "game"},
 *     @OA\Property(property="id", type="integer", example=12),
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
 *     @OA\Property(property="image", type="string", format="uri", nullable=true, example="https://example.com/storage/posts/12345.jpg"),
 *     @OA\Property(property="title", type="string", nullable=true, example="FC Barcelona vs Real Madrid"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-06-01T14:20:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-06-01T15:00:00Z")
 * )
 */
class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game' => new GameResource($this->whenLoaded('game')),
            'comments' => CommentResource::collection($this->whenLoaded('comments') ?? []),
            'likes' => LikeResource::collection($this->whenLoaded('likes') ?? []),
            'image' => $this->image ? Storage::url($this->image) : null,
            'title' => $this->game && $this->game->homeTeam && $this->game->awayTeam
                ? "{$this->game->homeTeam->name} vs {$this->game->awayTeam->name}"
                : null,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
