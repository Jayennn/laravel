<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function index(): JsonResponse {
        $users = User::all();
        if($users->isEmpty()) {
            return $this->notFoundResponse($users,'users not found');
        }
        return $this->successResponse($users, 'Users retrieved successfully.');
    }

    function register(Request $request): JsonResponse {
        $validate = $request->validate([
            "name" => "required|string|max:255|unique:users",
            "password" => "required|string|confirmed",
        ]);

        $validate['password'] = Hash::make($validate['password']);
        $user = User::create($validate);
        return response()->json($user, 201);
    }

    function login(Request $request): JsonResponse {
        $validate = $request->validate([
            "name" => "required|string|max:255",
            "password" => "required|string|min:6|confirm",
        ]);

        $user = User::where('name', $request->name)->first();
        return response()->json($user, 201);
    }
}
