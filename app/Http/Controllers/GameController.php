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
     * Toon alle wedstrijden
     *
     * @OA\Get(
     *     path="/api/games",
     *     tags={"Games"},
     *     summary="Lijst van alle gespeelde en geplande wedstrijden",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van games",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Game")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(GameResource::collection(Game::with(['stadium', 'homeTeam', 'awayTeam'])->get()));
    }

    /**
     * Voeg een nieuwe wedstrijd toe
     *
     * @OA\Post(
     *     path="/api/games",
     *     tags={"Games"},
     *     summary="Maak een nieuwe wedstrijd aan",
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
     *         description="Wedstrijd succesvol aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     )
     * )
     */
    public function store(GameRequest $request): JsonResponse
    {
        $game = Game::create($request->validated());
        return response()->json(new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam'])), 201);
    }

    /**
     * Toon één specifieke wedstrijd
     *
     * @OA\Get(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Details van een wedstrijd",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedstrijd gevonden",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     )
     * )
     */
    public function show(Game $game): JsonResponse
    {
        return response()->json(new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam'])));
    }

    /**
     * Werk een bestaande wedstrijd bij
     *
     * @OA\Put(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Update gegevens van een wedstrijd",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="stadium_id", type="integer", example=1),
     *             @OA\Property(property="home_team_id", type="integer", example=2),
     *             @OA\Property(property="away_team_id", type="integer", example=3),
     *             @OA\Property(property="home_score", type="integer", nullable=true, example=3),
     *             @OA\Property(property="away_score", type="integer", nullable=true, example=0),
     *             @OA\Property(property="match_date", type="string", format="date", example="2025-05-20")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Wedstrijd geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Game")
     *     )
     * )
     */
    public function update(GameRequest $request, Game $game): JsonResponse
    {
        $game->update($request->validated());
        return response()->json(new GameResource($game->load(['stadium', 'homeTeam', 'awayTeam'])));
    }

    /**
     * Verwijder een wedstrijd
     *
     * @OA\Delete(
     *     path="/api/games/{id}",
     *     tags={"Games"},
     *     summary="Verwijder een wedstrijd",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de wedstrijd",
     *         @OA\Schema(type="integer", example=5)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Wedstrijd verwijderd"
     *     )
     * )
     */
    public function destroy(Game $game): JsonResponse
    {
        $game->delete();
        return response()->json(null, 204);
    }
}
