<?php

namespace App\Http\Controllers;

use App\Http\Requests\VisitRequest;
use App\Http\Resources\VisitResource;
use App\Models\Visit;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Visits",
 *     description="Bijhouden van bezochte wedstrijden"
 * )
 */
class VisitController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/visits",
     *     summary="Toon alle bezoeken van de ingelogde gebruiker",
     *     tags={"Visits"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van bezoeken",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Visit"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $visits = Visit::with('game')->where('user_id', Auth::id())->get();
        return response()->json(VisitResource::collection($visits));
    }

    /**
     * @OA\Post(
     *     path="/api/visits",
     *     summary="Registreer een stadionbezoek",
     *     tags={"Visits"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"game_id"},
     *             @OA\Property(property="game_id", type="integer", example=5),
     *             @OA\Property(property="visited_at", type="string", format="date", example="2025-05-12"),
     *             @OA\Property(property="notes", type="string", example="Eerste keer in dit stadion!")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Bezoek geregistreerd",
     *         @OA\JsonContent(ref="#/components/schemas/Visit")
     *     )
     * )
     */
    public function store(VisitRequest $request): JsonResponse
    {
        $visit = Visit::create([
            'user_id' => Auth::id(),
            ...$request->validated(),
        ]);

        // Meldingen naar vrienden!
        $user = Auth::user();
        foreach ($user->friendships as $friendship) {
            $friend = $friendship->friend;

            \App\Models\Notification::create([
                'user_id' => $friend->id,
                'sender_id' => $user->id,
                'type' => 'visit',
                'game_id' => $visit->game_id,
            ]);
        }

        return response()->json(new VisitResource($visit->load('game')), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/visits/{id}",
     *     summary="Toon een specifiek stadionbezoek",
     *     tags={"Visits"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het bezoek",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bezoekdetails",
     *         @OA\JsonContent(ref="#/components/schemas/Visit")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Geen toegang tot dit bezoek"
     *     )
     * )
     */
    public function show(Visit $visit): JsonResponse
    {
        $this->authorizeVisit($visit);
        return response()->json(new VisitResource($visit->load('game')));
    }

    /**
     * @OA\Put(
     *     path="/api/visits/{id}",
     *     summary="Update een stadionbezoek",
     *     tags={"Visits"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het bezoek",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Visit")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Bezoek geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Visit")
     *     )
     * )
     */
    public function update(VisitRequest $request, Visit $visit): JsonResponse
    {
        $this->authorizeVisit($visit);
        $visit->update($request->validated());

        return response()->json(new VisitResource($visit->load('game')));
    }

    /**
     * @OA\Delete(
     *     path="/api/visits/{id}",
     *     summary="Verwijder een bezoek",
     *     tags={"Visits"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het bezoek",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Bezoek verwijderd"
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Niet gemachtigd"
     *     )
     * )
     */
    public function destroy(Visit $visit): JsonResponse
    {
        $this->authorizeVisit($visit);
        $visit->delete();

        return response()->json(null, 204);
    }

    protected function authorizeVisit(Visit $visit): void
    {
        if ($visit->user_id !== Auth::id()) {
            abort(403, 'Not authorized.');
        }
    }
}
