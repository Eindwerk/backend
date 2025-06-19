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

    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 's3');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'game_id' => $data['game_id'],
            'stadium_id' => $data['stadium_id'] ?? null,
            'team_id' => $data['team_id'] ?? null,
            'caption' => $data['caption'] ?? null,
            'image' => $data['image'] ?? null,
        ]);

        // Notificaties voor followers (of vrienden)
        $user = Auth::user();
        // Pas deze relatie aan naar jouw eigen followers-relatie indien nodig!
        foreach ($user->followers as $follower) {
            Notification::create([
                'user_id' => $follower->id,
                'sender_id' => $user->id,
                'type' => 'friend_post',
                'post_id' => $post->id,
                'game_id' => $post->game_id,
            ]);
        }

        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);

        return response()->json(new PostResource($post), 201);
    }

    public function show(Post $post): JsonResponse
    {
        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);
        return response()->json(new PostResource($post));
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $this->authorizePost($post);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('posts', 's3');
        }

        // Comments niet mee updaten
        unset($data['comments']);

        $post->update($data);

        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'user', 'comments', 'likes']);

        return response()->json(new PostResource($post));
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorizePost($post);

        if ($post->image && Storage::disk('s3')->exists($post->image)) {
            Storage::disk('s3')->delete($post->image);
        }

        $post->delete();

        return response()->json(null, 204);
    }

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
