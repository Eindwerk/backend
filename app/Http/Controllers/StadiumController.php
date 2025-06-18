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
    public function index(): JsonResponse
    {
        $stadiums = Stadium::all();
        return response()->json(StadiumResource::collection($stadiums));
    }

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

    public function show(Stadium $stadium): JsonResponse
    {
        return response()->json(new StadiumResource($stadium));
    }

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
