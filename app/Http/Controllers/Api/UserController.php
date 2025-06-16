<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

/**
 * @OA\Tag(
 *     name="Gebruikers",
 *     description="Gebruikers beheren en opvragen"
 * )
 */
class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/users",
     *     summary="Haal alle gebruikers op",
     *     tags={"Gebruikers"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Lijst van gebruikers",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/User")
     *         )
     *     )
     * )
     */
    public function index(): JsonResponse
    {
        $users = User::with('posts')->get(); // eager load posts if needed
        return response()->json(UserResource::collection($users));
    }

    /**
     * @OA\Get(
     *     path="/api/users/{id}",
     *     summary="Haal een specifieke gebruiker op",
     *     tags={"Gebruikers"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de gebruiker",
     *         @OA\Schema(type="string", example="usr_84R7mLp0Kg")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details van de gebruiker",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Gebruiker niet gevonden")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with(['posts'])->find($id); // optional: also load follows or followers

        if (!$user) {
            return response()->json([
                'message' => 'Gebruiker niet gevonden.',
            ], 404);
        }

        return response()->json(new UserResource($user));
    }
}
