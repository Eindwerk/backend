<?php

namespace App\Http\Controllers;

use App\Http\Requests\StadiumRequest;
use App\Http\Resources\StadiumResource;
use App\Models\Stadium;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

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

        File::ensureDirectoryExists(public_path('uploads/stadiums/profile-image'));
        File::ensureDirectoryExists(public_path('uploads/stadiums/banner-image'));

        if ($request->hasFile('profile_image')) {
            $filename = Str::random(40) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move(public_path('uploads/stadiums/profile-image'), $filename);
            $data['profile_image'] = 'uploads/stadiums/profile-image/' . $filename;
        }

        if ($request->hasFile('banner_image')) {
            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/stadiums/banner-image'), $filename);
            $data['banner_image'] = 'uploads/stadiums/banner-image/' . $filename;
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
            'location' => $data['location'] ?? $stadium->location,
        ]);

        File::ensureDirectoryExists(public_path('uploads/stadiums/profile-image'));
        File::ensureDirectoryExists(public_path('uploads/stadiums/banner-image'));

        if ($request->hasFile('profile_image')) {
            if ($stadium->profile_image && File::exists(public_path($stadium->profile_image))) {
                File::delete(public_path($stadium->profile_image));
            }

            $filename = Str::random(40) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $request->file('profile_image')->move(public_path('uploads/stadiums/profile-image'), $filename);
            $stadium->profile_image = 'uploads/stadiums/profile-image/' . $filename;
        }

        if ($request->hasFile('banner_image')) {
            if ($stadium->banner_image && File::exists(public_path($stadium->banner_image))) {
                File::delete(public_path($stadium->banner_image));
            }

            $filename = Str::random(40) . '.' . $request->file('banner_image')->getClientOriginalExtension();
            $request->file('banner_image')->move(public_path('uploads/stadiums/banner-image'), $filename);
            $stadium->banner_image = 'uploads/stadiums/banner-image/' . $filename;
        }

        $stadium->save();

        return response()->json(new StadiumResource($stadium));
    }

    public function destroy(Stadium $stadium): JsonResponse
    {
        if ($stadium->profile_image && File::exists(public_path($stadium->profile_image))) {
            File::delete(public_path($stadium->profile_image));
        }

        if ($stadium->banner_image && File::exists(public_path($stadium->banner_image))) {
            File::delete(public_path($stadium->banner_image));
        }

        $stadium->delete();

        return response()->json(null, 204);
    }
}
