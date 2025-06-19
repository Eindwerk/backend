<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Notification;

/**
 * @OA\Tag(
 *     name="Posts",
 *     description="Posts aanmaken over wedstrijden"
 * )
 */
class PostController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Haal alle posts op (met filters)",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="exclude_me", in="query", required=false, @OA\Schema(type="boolean")),
     *     @OA\Parameter(name="user_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="stadium_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Parameter(name="team_id", in="query", required=false, @OA\Schema(type="integer")),
     *     @OA\Response(
     *         response=200,
     *         description="Lijst met posts",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Post"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $userId = Auth::id();
        $query = Post::query();

        if (request('exclude_me') === 'true') {
            $query->where('user_id', '!=', $userId);
        }

        if ($userFilter = request('user_id')) {
            $query->where('user_id', $userFilter);
        }

        if ($stadiumId = request('stadium_id')) {
            $query->where('stadium_id', $stadiumId);
        }

        if ($teamId = request('team_id')) {
            $query->where('team_id', $teamId);
        }

        $posts = $query->latest()->get();

        return response()->json(PostResource::collection($posts));
    }

    /**
     * @OA\Post(
     *     path="/api/posts",
     *     summary="Maak een nieuwe post aan",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"game_id"},
     *                 @OA\Property(property="game_id", type="integer", example=1),
     *                 @OA\Property(property="stadium_id", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="team_id", type="integer", nullable=true, example=3),
     *                 @OA\Property(property="caption", type="string", example="Geweldige match!"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Post aangemaakt", @OA\JsonContent(ref="#/components/schemas/Post")),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 's3');
        }

        $post = Post::create([
            'user_id'    => Auth::id(),
            'game_id'    => $data['game_id'],
            'stadium_id' => $data['stadium_id'] ?? null,
            'team_id'    => $data['team_id'] ?? null,
            'caption'    => $data['caption'] ?? null,
            'image'      => $data['image'] ?? null,
        ]);

        foreach (Auth::user()->followers as $follower) {
            Notification::create([
                'user_id'   => $follower->id,
                'sender_id' => Auth::id(),
                'type'      => 'friend_post',
                'post_id'   => $post->id,
                'game_id'   => $post->game_id,
            ]);
        }

        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);

        return response()->json(new PostResource($post), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/posts/{id}",
     *     summary="Toon één post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Details van post", @OA\JsonContent(ref="#/components/schemas/Post")),
     *     @OA\Response(response=404, description="Post niet gevonden")
     * )
     */
    public function show(Post $post): JsonResponse
    {
        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);
        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Put(
     *     path="/api/posts/{id}",
     *     summary="Update een bestaande post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="caption", type="string", example="Gewijzigd onderschrift"),
     *                 @OA\Property(property="image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Post geüpdatet", @OA\JsonContent(ref="#/components/schemas/Post")),
     *     @OA\Response(response=403, description="Geen toestemming"),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $this->authorizePost($post);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 's3');
        }

        unset($data['comments']);

        $post->update($data);

        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);

        return response()->json(new PostResource($post));
    }

    /**
     * @OA\Delete(
     *     path="/api/posts/{id}",
     *     summary="Verwijder een post",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Post verwijderd"),
     *     @OA\Response(response=403, description="Geen toestemming")
     * )
     */
    public function destroy(Post $post): JsonResponse
    {
        $this->authorizePost($post);

        if ($post->image && Storage::disk('s3')->exists($post->image)) {
            Storage::disk('s3')->delete($post->image);
        }

        $post->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *     path="/api/me/posts",
     *     summary="Toon posts van de ingelogde gebruiker",
     *     tags={"Posts"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Lijst met eigen posts", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Post")))
     * )
     */
    public function myPosts(): JsonResponse
    {
        $posts = Post::where('user_id', Auth::id())->latest()->get();
        return response()->json(PostResource::collection($posts));
    }

    protected function authorizePost(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Je bent niet gemachtigd om deze post te bewerken.');
        }
    }
}
