<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamProfileRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

        File::ensureDirectoryExists(public_path('uploads/teams/profile-image'));
        File::ensureDirectoryExists(public_path('uploads/teams/banner-image'));

        if ($request->hasFile('logo_url')) {
            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $request->file('logo_url')->move(public_path('uploads/teams/profile-image'), $filename);
            $data['logo_url'] = 'uploads/teams/profile-image/' . $filename;
        }

        if ($request->hasFile('banner_image')) {
            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/teams/banner-image'), $filename);
            $data['banner_image'] = 'uploads/teams/banner-image/' . $filename;
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
            'league' => $data['league'] ?? $team->league,
        ]);

        File::ensureDirectoryExists(public_path('uploads/teams/profile-image'));
        File::ensureDirectoryExists(public_path('uploads/teams/banner-image'));

        if ($request->hasFile('logo_url')) {
            File::delete(public_path($team->logo_url));

            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $request->file('logo_url')->move(public_path('uploads/teams/profile-image'), $filename);
            $team->logo_url = 'uploads/teams/profile-image/' . $filename;
        }

        if ($request->hasFile('banner_image')) {
            File::delete(public_path($team->banner_image));

            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/teams/banner-image'), $filename);
            $team->banner_image = 'uploads/teams/banner-image/' . $filename;
        }

        $team->save();

        return response()->json(new TeamResource($team));
    }

    public function destroy(Team $team): JsonResponse
    {
        File::delete(public_path($team->logo_url));
        File::delete(public_path($team->banner_image));

        $team->delete();

        return response()->json(null, 204);
    }
}
