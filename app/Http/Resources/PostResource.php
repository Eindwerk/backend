<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class PostResource extends JsonResource
{
    public function toArray($request): array
    {
        // No need to assign $disk, use Storage::url() directly

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'game' => new GameResource($this->whenLoaded('game')),
            // 'comments' als kolom, geen relatie, dus gewoon als string:
            'comments' => $this->whenLoaded('comments') ? CommentResource::collection($this->comments) : null,

            // 'likes' relatie kan blijven als je die hebt:
            'likes' => $this->whenLoaded('likes') ? LikeResource::collection($this->likes) : null,

            'image' => $this->image ? Storage::url($this->image) : null,

            'title' => $this->game && $this->game->homeTeam && $this->game->awayTeam
                ? "{$this->game->homeTeam->name} vs {$this->game->awayTeam->name}"
                : null,

            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
