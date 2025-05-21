<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * @OA\Tag(
 *     name="Follows",
 *     description="Volgen en ontvolgen van gebruikers, clubs en stadions"
 * )
 */
class FollowController extends Controller
{
    /**
     * Volg een gebruiker, team of stadion
     *
     * @OA\Post(
     *     path="/api/follow",
     *     summary="Volg een team, stadion of user",
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
    public function follow(Request $request)
    {
        $request->validate([
            'followable_type' => 'required|string|in:team,stadium,user',
            'followable_id' => 'required|integer',
        ]);

        $user = $request->user();

        $modelClass = match (Str::lower($request->followable_type)) {
            'team' => \App\Models\Team::class,
            'stadium' => \App\Models\Stadium::class,
            'user' => \App\Models\User::class,
        };

        $followable = $modelClass::findOrFail($request->followable_id);

        if ($user->follows()->where([
            ['followable_id', $followable->id],
            ['followable_type', $modelClass]
        ])->exists()) {
            return response()->json(['message' => 'Je volgt dit al.'], 409);
        }

        $user->follows()->create([
            'followable_id' => $followable->id,
            'followable_type' => $modelClass,
        ]);

        return response()->json(['message' => 'Succesvol gevolgd.']);
    }

    /**
     * Ontvolg een gebruiker, team of stadion
     *
     * @OA\Delete(
     *     path="/api/unfollow",
     *     summary="Ontvolg een team, stadion of user",
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
    public function unfollow(Request $request)
    {
        $request->validate([
            'followable_type' => 'required|string|in:team,stadium,user',
            'followable_id' => 'required|integer',
        ]);

        $user = $request->user();

        $modelClass = match (Str::lower($request->followable_type)) {
            'team' => \App\Models\Team::class,
            'stadium' => \App\Models\Stadium::class,
            'user' => \App\Models\User::class,
        };

        $user->follows()->where([
            ['followable_id', $request->followable_id],
            ['followable_type', $modelClass],
        ])->delete();

        return response()->json(['message' => 'Ontvolgd.']);
    }

    /**
     * Lijst van alles wat de gebruiker volgt
     *
     * @OA\Get(
     *     path="/api/follows",
     *     summary="Overzicht van alles wat de gebruiker volgt",
     *     tags={"Follows"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst met volgitems",
     *         @OA\JsonContent(
     *             @OA\Property(property="follows", type="array", @OA\Items(type="object"))
     *         )
     *     )
     * )
     */
    public function index(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'follows' => $user->follows()->with('followable')->get(),
        ]);
    }
}
