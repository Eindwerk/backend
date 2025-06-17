<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamProfileRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

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
            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('teams/profile-image', $filename, 'public');
            $data['profile_image'] = $path;
        }

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('teams/banner-image', $filename, 'public');
            $data['banner_image'] = $path;
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
            if ($team->profile_image && Storage::disk('public')->exists($team->profile_image)) {
                Storage::disk('public')->delete($team->profile_image);
            }

            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('teams/profile-image', $filename, 'public');
            $team->profile_image = $path;
        }

        if ($request->hasFile('banner_image')) {
            if ($team->banner_image && Storage::disk('public')->exists($team->banner_image)) {
                Storage::disk('public')->delete($team->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('teams/banner-image', $filename, 'public');
            $team->banner_image = $path;
        }

        $team->save();

        return response()->json(new TeamResource($team));
    }

    public function destroy(Team $team): JsonResponse
    {
        if ($team->profile_image && Storage::disk('public')->exists($team->profile_image)) {
            Storage::disk('public')->delete($team->profile_image);
        }

        if ($team->banner_image && Storage::disk('public')->exists($team->banner_image)) {
            Storage::disk('public')->delete($team->banner_image);
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
