<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    function login(LoginRequest $request) {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        if ($user->tokens()->exists()) {
            $user->tokens()->delete();
        }
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }   
    function register(RegisterRequest $request) {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'User already exists'], 409);
        }
        $user = User::create($request->validated());
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    } 
    function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }
    function me(Request $request) {
        $user = $request->user()->load('roles.permissions');
        return [
            'id' => $user->id,
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'email' => $user->email,
            'roles' => $user->roles->map(function ($role) {
                return [
                    'name' => $role->name,
                    'permissions' => $role->permissions->map(function ($permission) {
                        return $permission->name;
                    }),
                ];
            }),
            'permissions' => $user->roles->flatMap(function ($role) {
                return $role->permissions->pluck('name');
            })->unique()->values()->all(),
        ];
    }
}
