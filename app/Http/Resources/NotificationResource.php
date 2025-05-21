<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(
 *     schema="Notification",
 *     required={"user_id", "sender_id", "type"},
 *     @OA\Property(property="id", type="integer", example=22),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="sender_id", type="integer", example=2),
 *     @OA\Property(property="type", type="string", example="comment"),
 *     @OA\Property(property="game_id", type="integer", nullable=true, example=5),
 *     @OA\Property(property="post_id", type="integer", nullable=true, example=9),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-14T10:20:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-14T10:30:00Z")
 * )
 */
class NotificationResource extends JsonResource
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
            'sender_id' => $this->sender_id,
            'type' => $this->type,
            'game_id' => $this->game_id,
            'post_id' => $this->post_id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
