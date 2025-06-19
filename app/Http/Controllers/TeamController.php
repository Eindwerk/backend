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
    /**
     * @OA\Get(
     *     path="/api/teams",
     *     summary="Toon alle teams",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van teams",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Team"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $teams = Team::all();
        return response()->json(TeamResource::collection($teams));
    }

    /**
     * @OA\Post(
     *     path="/api/teams",
     *     summary="Voeg een nieuw team toe",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name"},
     *                 @OA\Property(property="name", type="string", example="RSC Anderlecht"),
     *                 @OA\Property(property="league_id", type="integer", example=3),
     *                 @OA\Property(property="profile_image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Team aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
     *     summary="Toon een specifiek team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Team details", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=404, description="Team niet gevonden")
     * )
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json(new TeamResource($team));
    }

    /**
     * @OA\Post(
     *     path="/api/teams/{id}",
     *     summary="Update teamgegevens (PATCH via _method)",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="_method", type="string", example="PATCH"),
     *                 @OA\Property(property="name", type="string", example="Club Brugge"),
     *                 @OA\Property(property="league_id", type="integer", example=2),
     *                 @OA\Property(property="profile_image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Team geüpdatet", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
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

    /**
     * @OA\Delete(
     *     path="/api/teams/{id}",
     *     summary="Verwijder een team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Team verwijderd"),
     *     @OA\Response(response=404, description="Team niet gevonden")
     * )
     */
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
