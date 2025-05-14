<?php

namespace App\Http\Controllers;

use App\Http\Resources\NotificationResource;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Notifications",
 *     description="Meldingen voor nieuwe posts of bezoeken van vrienden"
 * )
 */
class NotificationController extends Controller
{
    /**
     * Haal alle meldingen op van de ingelogde gebruiker
     *
     * @OA\Get(
     *     path="/api/notifications",
     *     tags={"Notifications"},
     *     summary="Toon meldingen voor de huidige gebruiker",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst met notificaties",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=5),
     *                 @OA\Property(property="sender_id", type="integer", example=3),
     *                 @OA\Property(property="type", type="string", example="comment"),
     *                 @OA\Property(property="game_id", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="post_id", type="integer", nullable=true, example=9),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *             )
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $notifications = Notification::with(['game', 'post'])
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json(NotificationResource::collection($notifications));
    }

    /**
     * Verwijder een melding
     *
     * @OA\Delete(
     *     path="/api/notifications/{id}",
     *     tags={"Notifications"},
     *     summary="Verwijder een notificatie",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de notificatie",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Notificatie succesvol verwijderd"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Geen toegang om deze notificatie te verwijderen"
     *     )
     * )
     */
    public function destroy(Notification $notification): JsonResponse
    {
        if ($notification->user_id !== Auth::id()) {
            abort(403, 'Not authorized.');
        }

        $notification->delete();
        return response()->json(null, 204);
    }
}
