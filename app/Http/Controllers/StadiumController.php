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
     *     summary="Toon alle stadions",
     *     tags={"Stadiums"},
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
        $stadiums = Stadium::all();
        return response()->json(StadiumResource::collection($stadiums));
    }

    /**
     * @OA\Post(
     *     path="/api/stadiums",
     *     summary="Voeg een nieuw stadion toe",
     *     tags={"Stadiums"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"name", "latitude", "longitude"},
     *                 @OA\Property(property="name", type="string", example="Allianz Arena"),
     *                 @OA\Property(property="team_id", type="integer", nullable=true, example=5),
     *                 @OA\Property(property="latitude", type="string", example="48.2188000"),
     *                 @OA\Property(property="longitude", type="string", example="11.6247000"),
     *                 @OA\Property(property="profile_image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Stadion aangemaakt",
     *         @OA\JsonContent(ref="#/components/schemas/Stadium")
     *     ),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function store(StadiumRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('profile_image')) {
            $data['profile_image'] = $request->file('profile_image')->store('uploads/stadiums/profile-image', 's3');
        }

        if ($request->hasFile('banner_image')) {
            $data['banner_image'] = $request->file('banner_image')->store('uploads/stadiums/banner-image', 's3');
        }

        $stadium = Stadium::create($data);

        return response()->json(new StadiumResource($stadium), 201);
    }

    /**
     * @OA\Get(
     *     path="/api/stadiums/{id}",
     *     summary="Toon details van een stadion",
     *     tags={"Stadiums"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Stadion gevonden", @OA\JsonContent(ref="#/components/schemas/Stadium")),
     *     @OA\Response(response=404, description="Niet gevonden")
     * )
     */
    public function show(Stadium $stadium): JsonResponse
    {
        return response()->json(new StadiumResource($stadium));
    }

    /**
     * @OA\Post(
     *     path="/api/stadiums/{id}",
     *     summary="Update een stadion (custom route)",
     *     tags={"Stadiums"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Stade de France"),
     *                 @OA\Property(property="team_id", type="integer", nullable=true, example=2),
     *                 @OA\Property(property="latitude", type="string", example="48.924459"),
     *                 @OA\Property(property="longitude", type="string", example="2.360169"),
     *                 @OA\Property(property="profile_image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Stadion geüpdatet", @OA\JsonContent(ref="#/components/schemas/Stadium")),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function update(StadiumRequest $request, Stadium $stadium): JsonResponse
    {
        $data = $request->validated();

        $stadium->fill([
            'name' => $data['name'] ?? $stadium->name,
            'team_id' => $data['team_id'] ?? $stadium->team_id,
            'latitude' => $data['latitude'] ?? $stadium->latitude,
            'longitude' => $data['longitude'] ?? $stadium->longitude,
        ]);

        if ($request->hasFile('profile_image')) {
            if ($stadium->profile_image && Storage::disk('s3')->exists($stadium->profile_image)) {
                Storage::disk('s3')->delete($stadium->profile_image);
            }

            $stadium->profile_image = $request->file('profile_image')->store('uploads/stadiums/profile-image', 's3');
        }

        if ($request->hasFile('banner_image')) {
            if ($stadium->banner_image && Storage::disk('s3')->exists($stadium->banner_image)) {
                Storage::disk('s3')->delete($stadium->banner_image);
            }

            $stadium->banner_image = $request->file('banner_image')->store('uploads/stadiums/banner-image', 's3');
        }

        $stadium->save();

        return response()->json(new StadiumResource($stadium));
    }

    /**
     * @OA\Delete(
     *     path="/api/stadiums/{id}",
     *     summary="Verwijder een stadion",
     *     tags={"Stadiums"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Stadion verwijderd"),
     *     @OA\Response(response=404, description="Niet gevonden")
     * )
     */
    public function destroy(Stadium $stadium): JsonResponse
    {
        if ($stadium->profile_image && Storage::disk('s3')->exists($stadium->profile_image)) {
            Storage::disk('s3')->delete($stadium->profile_image);
        }

        if ($stadium->banner_image && Storage::disk('s3')->exists($stadium->banner_image)) {
            Storage::disk('s3')->delete($stadium->banner_image);
        }

        $stadium->delete();

        return response()->json(null, 204);
    }
}
