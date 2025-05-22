<?php

namespace App\Http\Controllers;

use App\Http\Requests\StadiumRequest;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

/**
 * @OA\Tag(
 *     name="Stadiums",
 *     description="Beheer van stadions"
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
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "city", "capacity"},
     *                 @OA\Property(property="name", type="string", example="Signal Iduna Park"),
     *                 @OA\Property(property="city", type="string", example="Dortmund"),
     *                 @OA\Property(property="capacity", type="integer", example=81365),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stadion aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function store(StadiumRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('stadiums/profile-image', 'public');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('stadiums/banner-image', 'public');
        }

        $stadium = Stadium::create($data);

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
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=404, description="Niet gevonden")
     * )
     */
    public function show(Stadium $stadium): JsonResponse
    {
        return response()->json(new StadiumResource($stadium));
    }

    /**
     * @OA\Patch(
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
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Borussia-Park"),
     *                 @OA\Property(property="city", type="string", example="Mönchengladbach"),
     *                 @OA\Property(property="capacity", type="integer", example=54000),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Stadion geüpdatet",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=403, description="Geen rechten"),
     *     @OA\Response(response=404, description="Niet gevonden")
     * )
     */
    public function update(StadiumRequest $request, Stadium $stadium): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($stadium->image) {
                Storage::disk('public')->delete($stadium->image);
            }
            $data['image'] = $request->file('image')->store('stadiums/profile-image', 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($stadium->banner_image) {
                Storage::disk('public')->delete($stadium->banner_image);
            }
            $data['banner_image'] = $request->file('banner_image')->store('stadiums/banner-image', 'public');
        }

        $stadium->update($data);

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
     *     ),
     *     @OA\Response(response=401, description="Niet geauthenticeerd"),
     *     @OA\Response(response=403, description="Geen rechten"),
     *     @OA\Response(response=404, description="Niet gevonden")
     * )
     */
    public function destroy(Stadium $stadium): JsonResponse
    {
        if ($stadium->image) {
            Storage::disk('public')->delete($stadium->image);
        }
        if ($stadium->banner_image) {
            Storage::disk('public')->delete($stadium->banner_image);
        }

        $stadium->delete();

        return response()->json(null, 204);
    }
}
