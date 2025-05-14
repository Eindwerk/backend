<?php

namespace App\Http\Controllers;

use App\Http\Requests\StadiumRequest;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Stadiums",
 *     description="Beheer van stadions"
 * )
 *
 * @OA\Schema(
 *     schema="Stadium",
 *     type="object",
 *     required={"name", "city", "capacity"},
 *     @OA\Property(property="id", type="integer", readOnly=true, example=1),
 *     @OA\Property(property="name", type="string", example="Allianz Arena"),
 *     @OA\Property(property="city", type="string", example="München"),
 *     @OA\Property(property="capacity", type="integer", example=75000),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-05-01T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-05-10T12:34:56Z")
 * )
 */
class StadiumController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/stadiums",
     *     tags={"Stadiums"},
     *     summary="Toon alle stadions",
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van stadions",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Stadium"))
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        return response()->json(StadiumResource::collection(Stadium::all()));
    }

    /**
     * @OA\Post(
     *     path="/api/stadiums",
     *     tags={"Stadiums"},
     *     summary="Voeg een stadion toe",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "city", "capacity"},
     *             @OA\Property(property="name", type="string", example="Signal Iduna Park"),
     *             @OA\Property(property="city", type="string", example="Dortmund"),
     *             @OA\Property(property="capacity", type="integer", example=81365)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stadion aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     )
     * )
     */
    public function store(StadiumRequest $request): JsonResponse
    {
        $stadium = Stadium::create($request->validated());

        return response()->json(new StadiumResource($stadium), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/stadiums/{id}",
     *     tags={"Stadiums"},
     *     summary="Toon één stadion",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het stadion",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stadion gevonden",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     )
     * )
     */
    public function show(Stadium $stadium): JsonResponse
    {
        return response()->json(new StadiumResource($stadium));
    }

    /**
     * @OA\Put(
     *     path="/api/stadiums/{id}",
     *     tags={"Stadiums"},
     *     summary="Update een stadion",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het stadion",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Borussia-Park"),
     *             @OA\Property(property="city", type="string", example="Mönchengladbach"),
     *             @OA\Property(property="capacity", type="integer", example=54000)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stadion geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     )
     * )
     */
    public function update(StadiumRequest $request, Stadium $stadium): JsonResponse
    {
        $stadium->update($request->validated());

        return response()->json(new StadiumResource($stadium));
    }

    /**
     * @OA\Delete(
     *     path="/api/stadiums/{id}",
     *     tags={"Stadiums"},
     *     summary="Verwijder een stadion",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van het stadion",
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Stadion verwijderd"
     *     )
     * )
     */
    public function destroy(Stadium $stadium): JsonResponse
    {
        $stadium->delete();

        return response()->json(null, 204);
    }
}
