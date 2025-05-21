<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Comment",
 *     type="object",
 *     required={"user_id", "post_id", "comment"},
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="user_id", type="integer", example=3),
 *     @OA\Property(property="post_id", type="integer", example=12),
 *     @OA\Property(property="comment", type="string", example="Leuke sfeer tijdens de match!"),
 *     @OA\Property(property="created_at", type="string", format="date-time", readOnly=true, example="2025-05-14T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true, example="2025-05-14T10:05:00Z")
 * )
 */
class CommentResource extends JsonResource
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
            'post_id' => $this->post_id,
            'comment' => $this->comment,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
