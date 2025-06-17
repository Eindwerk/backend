<?php

namespace App\Http\Controllers;

use App\Http\Requests\StadiumRequest;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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
            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('stadiums/profile-image', $filename, 'public');
            $data['profile_image'] = $path;
        }

        if ($request->hasFile('banner_image')) {
            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('stadiums/banner-image', $filename, 'public');
            $data['banner_image'] = $path;
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
            if ($stadium->profile_image && Storage::disk('public')->exists($stadium->profile_image)) {
                Storage::disk('public')->delete($stadium->profile_image);
            }

            $file = $request->file('profile_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('stadiums/profile-image', $filename, 'public');
            $stadium->profile_image = $path;
        }

        if ($request->hasFile('banner_image')) {
            if ($stadium->banner_image && Storage::disk('public')->exists($stadium->banner_image)) {
                Storage::disk('public')->delete($stadium->banner_image);
            }

            $file = $request->file('banner_image');
            $filename = md5_file($file->getRealPath()) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('stadiums/banner-image', $filename, 'public');
            $stadium->banner_image = $path;
        }

        $stadium->save();

        return response()->json(new StadiumResource($stadium));
    }

    public function destroy(Stadium $stadium): JsonResponse
    {
        if ($stadium->profile_image && Storage::disk('public')->exists($stadium->profile_image)) {
            Storage::disk('public')->delete($stadium->profile_image);
        }

        if ($stadium->banner_image && Storage::disk('public')->exists($stadium->banner_image)) {
            Storage::disk('public')->delete($stadium->banner_image);
        }

        $stadium->delete();

        return response()->json(null, 204);
    }
}
