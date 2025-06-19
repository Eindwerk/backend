<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Follows",
 *     description="Volgen en ontvolgen van gebruikers, clubs en stadions"
 * )
 */
class FollowController extends Controller
{
    /**
     * @OA\Post(
     *     path="/follow",
     *     summary="Volg een team, stadion of gebruiker",
     *     tags={"Follows"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"followable_type", "followable_id"},
     *             @OA\Property(property="followable_type", type="string", enum={"team", "stadium", "user"}, example="team"),
     *             @OA\Property(property="followable_id", type="integer", example=3)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Succesvol gevolgd",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Succesvol gevolgd."))
     *     ),
     *     @OA\Response(response=409, description="Je volgt dit al."),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function follow(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'followable_type' => 'required|string|in:team,stadium,user',
            'followable_id' => 'required|integer',
        ]);

        $user = $request->user();
        $modelClass = $this->getFollowableClass($validated['followable_type']);
        $followable = $modelClass::findOrFail($validated['followable_id']);

        $alreadyFollowing = $user->follows()
            ->where('followable_type', $modelClass)
            ->where('followable_id', $followable->id)
            ->exists();

        if ($alreadyFollowing) {
            return response()->json(['message' => 'Je volgt dit al.'], 409);
        }

        $user->follows()->create([
            'followable_type' => $modelClass,
            'followable_id' => $followable->id,
        ]);

        return response()->json(['message' => 'Succesvol gevolgd.']);
    }

    /**
     * @OA\Delete(
     *     path="/unfollow",
     *     summary="Ontvolg een team, stadion of gebruiker",
     *     tags={"Follows"},
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"followable_type", "followable_id"},
     *             @OA\Property(property="followable_type", type="string", enum={"team", "stadium", "user"}, example="stadium"),
     *             @OA\Property(property="followable_id", type="integer", example=5)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Ontvolgd",
     *         @OA\JsonContent(@OA\Property(property="message", type="string", example="Ontvolgd."))
     *     ),
     *     @OA\Response(response=422, description="Validatiefout")
     * )
     */
    public function unfollow(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'followable_type' => 'required|string|in:team,stadium,user',
            'followable_id' => 'required|integer',
        ]);

        $user = $request->user();
        $modelClass = $this->getFollowableClass($validated['followable_type']);

        $user->follows()->where([
            ['followable_type', $modelClass],
            ['followable_id', $validated['followable_id']],
        ])->delete();

        return response()->json(['message' => 'Ontvolgd.']);
    }

    /**
     * @OA\Get(
     *     path="/following",
     *     summary="Lijst van gevolgde items door de gebruiker",
     *     tags={"Follows"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst met volgitems",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="follows",
     *                 type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="followable_type", type="string"),
     *                     @OA\Property(property="followable_id", type="integer"),
     *                     @OA\Property(property="followable", type="object")
     *                 )
     *             )
     *         )
     *     )
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $follows = $user->follows()->with('followable')->get();

        return response()->json(['follows' => $follows]);
    }

    private function getFollowableClass(string $type): string
    {
        return match (Str::of($type)->lower()->toString()) {
            'team' => \App\Models\Team::class,
            'stadium' => \App\Models\Stadium::class,
            'user' => \App\Models\User::class,
            default => abort(422, 'Invalid followable_type'),
        };
    }
}
