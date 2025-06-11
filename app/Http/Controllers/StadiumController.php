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
     *     @OA\Response(response=200, description="Lijst van stadions", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Stadium"))),
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
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="capacity", type="integer"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Stadion aangemaakt", @OA\JsonContent(ref="#/components/schemas/Stadium")),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function store(StadiumRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $filename = $request->file('image')->hashName();
            $data['image'] = $request->file('image')->storeAs('stadiums/profile-image', $filename, 'public');
        }

        if ($request->hasFile('banner_image')) {
            $filename = $request->file('banner_image')->hashName();
            $data['banner_image'] = $request->file('banner_image')->storeAs('stadiums/banner-image', $filename, 'public');
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Stadion gevonden", @OA\JsonContent(ref="#/components/schemas/Stadium")),
     *     @OA\Response(response=404, description="Niet gevonden"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="city", type="string"),
     *                 @OA\Property(property="capacity", type="integer"),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="banner_image", type="string", format="binary")
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Stadion geüpdatet", @OA\JsonContent(ref="#/components/schemas/Stadium")),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
     * )
     */
    public function update(StadiumRequest $request, Stadium $stadium): JsonResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            if ($stadium->image) {
                Storage::disk('public')->delete($stadium->image);
            }
            $filename = $request->file('image')->hashName();
            $data['image'] = $request->file('image')->storeAs('stadiums/profile-image', $filename, 'public');
        }

        if ($request->hasFile('banner_image')) {
            if ($stadium->banner_image) {
                Storage::disk('public')->delete($stadium->banner_image);
            }
            $filename = $request->file('banner_image')->hashName();
            $data['banner_image'] = $request->file('banner_image')->storeAs('stadiums/banner-image', $filename, 'public');
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
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Stadion verwijderd"),
     *     @OA\Response(response=401, description="Niet geauthenticeerd")
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
