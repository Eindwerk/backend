<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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
            $query->whereHas('game', fn($q) => $q->where('stadium_id', $stadiumId));
        }

        if ($teamId = request('team_id')) {
            $query->whereHas(
                'game',
                fn($q) =>
                $q->where('home_team_id', $teamId)
                    ->orWhere('away_team_id', $teamId)
            );
        }

        $posts = $query
            ->with(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'comments', 'user'])
            ->latest()
            ->get();

        return response()->json(PostResource::collection($posts));
    }

    public function store(PostRequest $request): JsonResponse
    {
        $data = $request->validated();

        // Upload naar Laravel Storage 'public' disk consistent?
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $data['image_path'] = $file->storeAs('uploads/posts/images', $filename, 'public');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'game_id' => $data['game_id'],
            'stadium_id' => $data['stadium_id'],
            'image_path' => $data['image_path'] ?? null,
        ]);

        $post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'comments', 'user']);

        return response()->json(new PostResource($post), 201);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json(
            new PostResource($post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'comments', 'user']))
        );
    }

    public function update(PostRequest $request, Post $post): JsonResponse
    {
        $this->authorizePost($post);

        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($post->image_path && File::exists(public_path($post->image_path))) {
                File::delete(public_path($post->image_path));
            }

            $file = $request->file('image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $data['image_path'] = $file->storeAs('uploads/posts/images', $filename, 'public');
        }

        // Content niet updaten
        unset($data['content']);

        $post->update($data);

        return response()->json(
            new PostResource($post->load(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'comments', 'user']))
        );
    }

    public function destroy(Post $post): JsonResponse
    {
        $this->authorizePost($post);

        if ($post->image_path && File::exists(public_path($post->image_path))) {
            File::delete(public_path($post->image_path));
        }

        $post->delete();

        return response()->json(null, 204);
    }

    public function myPosts(): JsonResponse
    {
        $posts = tap(Auth::user())->posts()
            ->with(['game.homeTeam', 'game.awayTeam', 'game.stadium', 'comments', 'user'])
            ->latest()
            ->get();

        return response()->json(PostResource::collection($posts));
    }

    protected function authorizePost(Post $post): void
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, 'Not authorized.');
        }
    }
}
