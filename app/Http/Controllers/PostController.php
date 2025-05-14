<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Posts aanmaken over wedstrijden"
 * )
 */
class PostController extends Controller
{
    /**
     * Toon alle posts
     *
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Lijst van posts",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Overzicht van posts",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Post"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $posts = Post::with(['game', 'comments'])->latest()->get();
        return response()->json(PostResource::collection($posts));
    }

    /**
     * Maak een nieuwe post
     *
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Creëer een nieuwe post over een wedstrijd",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"game_id", "content"},
     *             @OA\Property(property="game_id", type="integer", example=1),
     *             @OA\Property(property="content", type="string", example="Geweldige sfeer in het stadion!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Post succesvol aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     )
     * )
     */
    public function store(PostRequest $request): JsonResponse
    {
        $post = Post::create([
            'user_id' => Auth::id(),
            ...$request->validated(),
        ]);

        return response()->json(new PostResource($post->load(['game', 'comments'])), 201);
    }

    /**
     * Toon een specifieke post
     *
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Bekijk een specifieke post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de post",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details van de post",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     )
     * )
     */
    public function show(Post $post): JsonResponse
    {
        return response()->json(new PostResource($post->load(['game', 'comments'])));
    }

    /**
     * Update een post
     *
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Bewerk een bestaande post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de post",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="content", type="string", example="Aangepaste inhoud")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post bijgewerkt",
     *         @OA\JsonContent(ref="#/components/schemas/Post")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Niet gemachtigd"
     *     )
     * )
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $this->authorizePost($post);
        $post->update($request->validated());

        return response()->json(new PostResource($post->load(['game', 'comments'])));
    }

    /**
     * Verwijder een post
     *
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Verwijder een post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de post",
     *         @OA\Schema(type="integer", example=3)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Post verwijderd"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Niet gemachtigd om te verwijderen"
     *     )
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorizePost($post);
        $post->delete();

        return response()->json(null, 204);
    }

    protected function authorizePost(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Not authorized.');
        }
    }
}
