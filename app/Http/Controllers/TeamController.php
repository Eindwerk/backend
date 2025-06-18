<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamProfileRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Teams",
 *     description="Beheer van voetbalteams"
 * )
 */
class TeamController extends Controller
{
    public function index(): JsonResponse
    {
        $teams = Team::all();
        return response()->json(TeamResource::collection($teams));
    }

    public function store(StoreTeamRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('teams/profile-image', 's3');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('teams/banner-image', 's3');
        }

        $team = Team::create($data);

        return response()->json(new TeamResource($team), 201);
    }

    public function show(Team $team): JsonResponse
    {
        return response()->json(new TeamResource($team));
    }

    public function update(UpdateTeamProfileRequest $request, Team $team): JsonResponse
    {
        $data = $request->validated();

        $team->fill([
            'name' => $data['name'] ?? $team->name,
            'league_id' => $data['league_id'] ?? $team->league_id,
        ]);

        if ($request->hasFile('profile_image')) {
            $this->deleteImageIfExists($team->profile_image);
            $team->profile_image = $request->file('profile_image')->store('teams/profile-image', 's3');
        }

        if ($request->hasFile('banner_image')) {
            $this->deleteImageIfExists($team->banner_image);
            $team->banner_image = $request->file('banner_image')->store('teams/banner-image', 's3');
        }

        $team->save();

        return response()->json(new TeamResource($team));
    }

    public function destroy(Team $team): JsonResponse
    {
        $this->deleteImageIfExists($team->profile_image);
        $this->deleteImageIfExists($team->banner_image);

        $team->delete();

        return response()->json(null, 204);
    }

    protected function deleteImageIfExists(?string $path): void
    {
        if ($path && Storage::disk('s3')->exists($path)) {
            Storage::disk('s3')->delete($path);
        }
    }
}
