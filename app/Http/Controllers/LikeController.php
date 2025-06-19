<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

/**
 * @OA\Tag(
 *     name="Likes",
 *     description="Liken en unliken van posts"
 * )
 */
class LikeController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/likes",
     *     summary="Voeg een like toe aan een post",
     *     tags={"Likes"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"post_id"},
     *             @OA\Property(property="post_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post geliket",
     *         @OA\JsonContent(ref="#/components/schemas/Like")
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function store(LikeRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $validated['post_id'],
        ]);

        $post = Post::find($validated['post_id']);

        if ($post && $post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => Auth::id(),
                'type' => 'like',
                'post_id' => $post->id,
                'game_id' => $post->game_id,
            ]);
        }

        return response()->json(new LikeResource($like), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/likes/{post_id}",
     *     summary="Verwijder een like van een post",
     *     tags={"Likes"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="post_id",
     *         in="path",
     *         required=true,
     *         description="ID van de post",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Like verwijderd",
     *         @OA\JsonContent(@OA\Property(property="deleted", type="boolean", example=true))
     *     ),
     *     @OA\Response(response=404, description="Like niet gevonden"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function destroy(int $post_id): JsonResponse
    {
        $deleted = Like::where('user_id', Auth::id())
            ->where('post_id', $post_id)
            ->delete();

        if ($deleted) {
            return response()->json(['deleted' => true], 200);
        }

        return response()->json(['message' => 'Like niet gevonden.'], 404);
    }
}
