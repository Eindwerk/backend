<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Post",
 *     required={"user_id", "game", "content"},
 *     @OA\Property(property="id", type="integer", example=7),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="game", ref="#/components/schemas/Game"),
 *     @OA\Property(
 *         property="comments",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Comment")
 *     ),
 *     @OA\Property(property="content", type="string", example="Wat een sfeer tijdens die derby!"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T09:55:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:05:00Z")
 * )
 */
class PostResource extends JsonResource
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
            'content' => $this->content,
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
