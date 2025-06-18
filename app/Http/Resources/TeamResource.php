<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class TeamResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id'            => $this->id,
            'name'          => $this->name,
            'league_id'     => $this->league_id,
            'profile_image' => $this->profile_image ? $disk->url($this->profile_image) : null,
            'banner_image'  => $this->banner_image ? $disk->url($this->banner_image) : null,
            'created_at'    => $this->created_at?->toIso8601String(),
            'updated_at'    => $this->updated_at?->toIso8601String(),
        ];
    }
}
