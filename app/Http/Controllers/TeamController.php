<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamProfileRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
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

        if ($request->hasFile('logo_url')) {
            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $path = $request->file('logo_url')->storeAs('uploads/teams/profile-image', $filename, 'public');
            $data['logo_url'] = $path; // bv. uploads/teams/profile-image/xxxx.png
        }

        if ($request->hasFile('banner_image')) {
            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $path = $request->file('banner_image')->storeAs('uploads/teams/banner-image', $filename, 'public');
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
            'league' => $data['league'] ?? $team->league,
        ]);

        if ($request->hasFile('logo_url')) {
            if ($team->logo_url && Storage::disk('public')->exists($team->logo_url)) {
                Storage::disk('public')->delete($team->logo_url);
            }

            $filename = Str::random(40) . '.' . $request->file('logo_url')->getClientOriginalExtension();
            $path = $request->file('logo_url')->storeAs('uploads/teams/profile-image', $filename, 'public');
            $team->logo_url = $path;
        }

        if ($request->hasFile('banner_image')) {
            if ($team->banner_image && Storage::disk('public')->exists($team->banner_image)) {
                Storage::disk('public')->delete($team->banner_image);
            }

            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $path = $request->file('banner_image')->storeAs('uploads/teams/banner-image', $filename, 'public');
            $team->banner_image = $path;
        }

        $team->save();

        return response()->json(new TeamResource($team));
    }

    public function destroy(Team $team): JsonResponse
    {
        if ($team->logo_url && Storage::disk('public')->exists($team->logo_url)) {
            Storage::disk('public')->delete($team->logo_url);
        }

        if ($team->banner_image && Storage::disk('public')->exists($team->banner_image)) {
            Storage::disk('public')->delete($team->banner_image);
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
