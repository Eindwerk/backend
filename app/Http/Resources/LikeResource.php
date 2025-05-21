<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Like",
 *     type="object",
 *     required={"user_id", "post_id"},
 *     @OA\Property(property="id", type="integer", example=10, readOnly=true),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="post_id", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:15:00Z")
 * )
 */
class LikeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'post_id' => $this->post_id,
            'created_at' => $this->created_at,

            // Indien gewenst (en eager-loaded): bijhorende gebruiker of post
            'user' => new UserResource($this->whenLoaded('user')),
            'post' => new PostResource($this->whenLoaded('post')),
        ];
    }
}
