<?php

namespace App\Http\Controllers;

use App\Http\Requests\GameRequest;
use App\Http\Resources\GameResource;
use App\Models\Game;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Games",
 *     description="Wedstrijden tussen teams in een stadion"
 * )
 */
class GameController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/games",
     *     summary="Lijst van alle gespeelde en geplande wedstrijden",
     *     tags={"Games"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van games",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Game"))
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function index(): JsonResponse
    {
        $games = Game::with(['stadium', 'homeTeam', 'awayTeam'])->latest()->get();

        return response()->json(GameResource::collection($games));
    }

    /**
     * @OA\Post(
     *     path="/api/games",
     *     summary="Maak een nieuwe wedstrijd aan",
     *     tags={"Games"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"stadium_id", "home_team_id", "away_team_id", "match_date"},
     *             @OA\Property(property="stadium_id", type="integer", example=1),
     *             @OA\Property(property="home_team_id", type="integer", example=2),
     *             @OA\Property(property="away_team_id", type="integer", example=3),
     *             @OA\Property(property="home_score", type="integer", nullable=true, example=2),
     *             @OA\Property(property="away_score", type="integer", nullable=true, example=1),
     *             @OA\Property(property="match_date", type="string", format="date", example="2025-05-15")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Wedstrijd aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function store(GameRequest $request): JsonResponse
    {
        $game = Game::create($request->validated());

        return response()->json(
            new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam'])),
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/games/{id}",
     *     summary="Toon details van een wedstrijd",
     *     tags={"Games"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details van de wedstrijd",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     ),
     *     @OA\Response(response=404, description="Wedstrijd niet gevonden"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function show(Game $game): JsonResponse
    {
        return response()->json(
            new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam']))
        );
    }

    /**
     * @OA\Put(
     *     path="/api/games/{id}",
     *     summary="Update gegevens van een wedstrijd",
     *     tags={"Games"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="stadium_id", type="integer", example=1),
     *             @OA\Property(property="home_team_id", type="integer", example=2),
     *             @OA\Property(property="away_team_id", type="integer", example=3),
     *             @OA\Property(property="match_date", type="string", format="date", example="2025-05-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedstrijd geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function update(GameRequest $request, Game $game): JsonResponse
    {
        $game->update($request->validated());

        return response()->json(
            new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam']))
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/games/{id}",
     *     summary="Verwijder een wedstrijd",
     *     tags={"Games"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=4)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Wedstrijd verwijderd"
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function destroy(Game $game): JsonResponse
    {
        $game->delete();

        return response()->json(null, 204);
    }
}
