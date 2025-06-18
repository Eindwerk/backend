<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class StadiumResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var \Illuminate\Contracts\Filesystem\Cloud $disk */
        $disk = Storage::disk('s3');

        return [
            'id' => $this->id,
            'name' => $this->name,
            'team_name' => $this->team?->name,
            'profile_image' => $this->profile_image ? $disk->url($this->profile_image) : null,
            'banner_image' => $this->banner_image ? $disk->url($this->banner_image) : null,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
