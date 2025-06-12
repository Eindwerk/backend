<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamProfileRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
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
        return response()->json(TeamResource::collection(Team::all()));
    }

    public function store(StoreTeamRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo_url')) {
            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $path = $request->file('logo_url')->storeAs('uploads/teams/profile-image', $filename, 'public');
            $data['logo_url'] = 'storage/' . $path;
        }

        if ($request->hasFile('banner_image')) {
            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $path = $request->file('banner_image')->storeAs('uploads/teams/banner-image', $filename, 'public');
            $data['banner_image'] = 'storage/' . $path;
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

        if (isset($data['name'])) {
            $team->name = $data['name'];
        }

        if (isset($data['league'])) {
            $team->league = $data['league'];
        }

        if ($request->hasFile('logo_url')) {
            if ($team->logo_url && file_exists(public_path($team->logo_url))) {
                unlink(public_path($team->logo_url));
            }

            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $path = $request->file('logo_url')->storeAs('uploads/teams/profile-image', $filename, 'public');
            $team->logo_url = 'storage/' . $path;
        }

        if ($request->hasFile('banner_image')) {
            if ($team->banner_image && file_exists(public_path($team->banner_image))) {
                unlink(public_path($team->banner_image));
            }

            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $path = $request->file('banner_image')->storeAs('uploads/teams/banner-image', $filename, 'public');
            $team->banner_image = 'storage/' . $path;
        }

        $team->save();

        return response()->json(new TeamResource($team));
    }

    public function destroy(Team $team): JsonResponse
    {
        if ($team->logo_url) {
            Storage::disk('public')->delete(str_replace('storage/', '', $team->logo_url));
        }

        if ($team->banner_image) {
            Storage::disk('public')->delete(str_replace('storage/', '', $team->banner_image));
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
