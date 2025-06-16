<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Comments",
 *     description="Endpoints voor reacties op posts"
 * )
 */
class CommentController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/comments",
     *     summary="Voeg een reactie toe",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"post_id", "comment"},
     *             @OA\Property(property="post_id", type="integer", example=1),
     *             @OA\Property(property="comment", type="string", example="Wat een sfeer daar!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Reactie succesvol aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Comment")
     *     )
     * )
     */
    public function store(CommentRequest $request): JsonResponse
    {
        $data = $request->validated();

        $comment = Comment::create([
            'user_id' => Auth::id(),
            ...$data,
        ]);

        $post = Post::find($data['post_id']);

        if ($post && $post->user_id !== Auth::id()) {
            Notification::create([
                'user_id' => $post->user_id,
                'sender_id' => Auth::id(),
                'type' => 'comment',
                'game_id' => $post->game_id,
                'post_id' => $post->id,
            ]);
        }

        return response()->json(new CommentResource($comment), 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/comments/{id}",
     *     summary="Verwijder een bestaande reactie",
     *     tags={"Comments"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de reactie",
     *         @OA\Schema(type="integer", example=12)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Reactie succesvol verwijderd"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Geen toestemming om deze reactie te verwijderen"
     *     )
     * )
     */
    public function destroy(Comment $comment): JsonResponse
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'Je bent niet gemachtigd om deze reactie te verwijderen.',
            ], 403);
        }

        $comment->delete();

        return response()->json(null, 204);
    }
}
