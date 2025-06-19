<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @OA\Tag(
 *     name="Users",
 *     description="Gebruikers ophalen en bekijken"
 * )
 */
class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Toon alle gebruikers",
     *     tags={"Users"},
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
        $users = User::with('posts')->get();
        return response()->json(UserResource::collection($users));
    }

    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Toon één gebruiker op ID",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID van de gebruiker",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Details van een gebruiker",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=404, description="Gebruiker niet gevonden")
     * )
     */
    public function show(string $id): JsonResponse
    {
        $user = User::with(['posts'])->find($id);

        if (!$user) {
            return response()->json([
                'message' => 'Gebruiker niet gevonden.',
            ], 404);
        }

        return response()->json(new UserResource($user));
    }

    /**
     * @OA\Get(
     *     path="/me",
     *     summary="Toon huidige ingelogde gebruiker",
     *     tags={"Users"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Eigen gebruikersprofiel",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=401, description="Niet ingelogd")
     * )
     */
    public function me(): JsonResponse
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json(['message' => 'Niet ingelogd.'], 401);
        }

        $user->load('posts');

        return response()->json(new UserResource($user));
    }
}
