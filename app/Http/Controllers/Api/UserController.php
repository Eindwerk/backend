<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(): JsonResponse
    {
        $users = User::with('posts')->get();
        return response()->json(UserResource::collection($users));
    }

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
