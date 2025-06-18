<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game' => new GameResource($this->whenLoaded('game')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'likes' => LikeResource::collection($this->whenLoaded('likes')),

            'image' => $this->image_path ? $disk->url($this->image_path) : null,

            'title' => optional($this->game?->homeTeam)->name && optional($this->game?->awayTeam)->name && optional($this->game?->stadium)->name
                ? "{$this->game->homeTeam->name} vs {$this->game->awayTeam->name} – {$this->game->stadium->name}"
                : null,

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
