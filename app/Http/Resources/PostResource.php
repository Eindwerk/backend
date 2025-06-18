<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

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
