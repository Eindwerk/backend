<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Http\Resources\LikeResource;
use App\Models\Like;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function store(LikeRequest $request): JsonResponse
    {
        $like = Like::firstOrCreate([
            'user_id' => Auth::id(),
            'post_id' => $request->validated()['post_id'],
        ]);

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
     *         @OA\JsonContent(
     *             @OA\Property(property="deleted", type="boolean", example=true)
     *         )
     *     ),
     *     @OA\Response(response=404, description="Like niet gevonden"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function destroy($post_id): JsonResponse
    {
        $deleted = Like::where('user_id', Auth::id())
            ->where('post_id', $post_id)
            ->delete();

        return response()->json(['deleted' => $deleted > 0], $deleted ? 200 : 404);
    }
}
