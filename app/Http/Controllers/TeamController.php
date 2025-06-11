<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
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
     *     @OA\Response(response=200, description="Lijst van teams", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Team"))),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(TeamResource::collection(Team::all()));
    }

    /**
     * @OA\Post(
     *     path="/api/teams",
     *     summary="Voeg een team toe",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "league"},
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="league", type="string"),
     *                 @OA\Property(property="logo_url", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Team aangemaakt", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function store(TeamRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo_url')) {
            $filename = $request->file('logo_url')->hashName();
            $data['logo_url'] = $request->file('logo_url')->storeAs('teams/profile-image', $filename, 'public');
        }

        if ($request->hasFile('banner_image')) {
            $filename = $request->file('banner_image')->hashName();
            $data['banner_image'] = $request->file('banner_image')->storeAs('teams/banner-image', $filename, 'public');
        }

        $team = Team::create($data);

        return response()->json(new TeamResource($team), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
     *     summary="Toon een team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Teamdetails", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=404, description="Niet gevonden"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json(new TeamResource($team));
    }

    /**
     * @OA\Patch(
     *     path="/api/teams/{id}",
     *     summary="Update een team (PATCH)",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="league", type="string"),
     *                 @OA\Property(property="logo_url", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Team geüpdatet", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     * 
     * @OA\Post(
     *     path="/api/teams/{id}",
     *     summary="Update een team (POST alternatief)",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="league", type="string"),
     *                 @OA\Property(property="logo_url", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Team geüpdatet", @OA\JsonContent(ref="#/components/schemas/Team")),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function update(TeamRequest $request, Team $team): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('logo_url')) {
            if ($team->logo_url) {
                Storage::disk('public')->delete($team->logo_url);
            }
            $filename = $request->file('logo_url')->hashName();
            $data['logo_url'] = $request->file('logo_url')->storeAs('teams/profile-image', $filename, 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($team->banner_image) {
                Storage::disk('public')->delete($team->banner_image);
            }
            $filename = $request->file('banner_image')->hashName();
            $data['banner_image'] = $request->file('banner_image')->storeAs('teams/banner-image', $filename, 'public');
        }

        $team->update($data);

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
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function destroy(Team $team): JsonResponse
    {
        if ($team->logo_url) {
            Storage::disk('public')->delete($team->logo_url);
        }

        if ($team->banner_image) {
            Storage::disk('public')->delete($team->banner_image);
        }

        $team->delete();

        return response()->json(null, 204);
    }
}
