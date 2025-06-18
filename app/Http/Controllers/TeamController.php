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

        // Upload en sla profiel afbeelding op
        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $this->storeImage($request->file('profile_image'), 'teams/profile-image');
        }

        // Upload en sla banner afbeelding op
        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $this->storeImage($request->file('banner_image'), 'teams/banner-image');
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
            $team->profile_image = $this->storeImage($request->file('profile_image'), 'teams/profile-image');
        }

        if ($request->hasFile('banner_image')) {
            $this->deleteImageIfExists($team->banner_image);
            $team->banner_image = $this->storeImage($request->file('banner_image'), 'teams/banner-image');
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

    /**
     * Helper: upload afbeelding met md5 bestandsnaam
     */
    protected function storeImage($file, string $directory): string
    {
        $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, 'public');
    }

    /**
     * Helper: verwijder afbeelding als die bestaat
     */
    protected function deleteImageIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
