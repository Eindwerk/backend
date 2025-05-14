<?php

namespace App\Http\Controllers;

use App\Http\Requests\TeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Teams",
 *     description="Beheer van voetbalteams"
 * )
 *
 * @OA\Schema(
 *     schema="Team",
 *     required={"name", "league"},
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="name", type="string", example="Borussia Dortmund"),
 *     @OA\Property(property="league", type="string", example="Bundesliga"),
 *     @OA\Property(property="logo_url", type="string", nullable=true, example="https://example.com/logo.png"),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-01T12:00:00Z")
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
     *         @OA\JsonContent(
     *             required={"name", "league"},
     *             @OA\Property(property="name", type="string", example="Borussia Dortmund"),
     *             @OA\Property(property="league", type="string", example="Bundesliga"),
     *             @OA\Property(property="logo_url", type="string", example="https://example.com/logo.png")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Team aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function store(TeamRequest $request): JsonResponse
    {
        $team = Team::create($request->validated());
        return response()->json(new TeamResource($team), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
     *     summary="Toon een team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het team",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Teamdetails",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function show(Team $team): JsonResponse
    {
        return response()->json(new TeamResource($team));
    }

    /**
     * @OA\Put(
     *     path="/api/teams/{id}",
     *     summary="Update een team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het team",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Borussia Dortmund"),
     *             @OA\Property(property="league", type="string", example="Bundesliga"),
     *             @OA\Property(property="logo_url", type="string", example="https://example.com/logo.png")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Team geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function update(TeamRequest $request, Team $team): JsonResponse
    {
        $team->update($request->validated());
        return response()->json(new TeamResource($team));
    }

    /**
     * @OA\Delete(
     *     path="/api/teams/{id}",
     *     summary="Verwijder een team",
     *     tags={"Teams"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het team",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Team verwijderd"
     *     )
     * )
     */
    public function destroy(Team $team): JsonResponse
    {
        $team->delete();
        return response()->json(null, 204);
    }
}
