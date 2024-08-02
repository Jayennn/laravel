<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{


    function index(): JsonResponse {
        $users = User::all();
        if($users->isEmpty()) {
            return $this->notFoundResponse($users,'users not found');
        }
        return $this->successResponse($users, 'Users retrieved successfully.', 201);
    }

    function register(Request $request): JsonResponse {

        $credentials = Validator::make($request->all() , [
            'name' => 'required|string|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if($credentials->fails()) {
            return $this->invalidFieldResponse($credentials->errors());
        }

        $validateData =  $credentials->validated();

        // Hash the password before creating the user
        $validateData['password'] = Hash::make($validateData['password']);

        $user = User::create($validateData);
        return $this->successResponse($user, 'User registered successfully.', 201);
    }

    function login(Request $request): JsonResponse {
        $credentials = Validator::make($request->all() , [
            'name' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if($credentials->fails()) {
            return $this->invalidFieldResponse($credentials->errors());
        }

        try {
            $user = User::where('name', $request->name)->first();

            if(!$user || !Hash::check($request->password, $user->password)) {
                return $this->errorResponse(null, 'Invalid credentials', 401);
            }

            $token = $user->createToken('auth_token')->plainTextToken;
            dd($token);
            return $this->successResponse($users, 'User login successfully.', 201);

        } catch (\Throwable $exception) {
            return $this->errorResponse($exception->getMessage());
        }
    }
}
