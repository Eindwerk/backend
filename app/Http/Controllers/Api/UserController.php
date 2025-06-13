<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

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
 *
 * @OA\Get(
 *     path="/api/users/{id}",
 *     summary="Haal een specifieke gebruiker op",
 *     tags={"Gebruikers"},
 *     security={{"sanctum":{}}},
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Response(
 *         response=200,
 *         description="Details van de gebruiker",
 *         @OA\JsonContent(ref="#/components/schemas/User")
 *     ),
 *     @OA\Response(response=404, description="Gebruiker niet gevonden")
 * )
 */
class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = User::all();
        return response()->json(UserResource::collection($users));
    }

    public function show(string $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Gebruiker niet gevonden.'], 404);
        }

        return response()->json(new UserResource($user));
    }
}
