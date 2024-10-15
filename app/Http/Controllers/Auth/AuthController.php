<?php

namespace App\Http\Controllers\Auth;

use App\Events\UserCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginGoogleRequest;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Error;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Google\Client as GoogleClient;

class AuthController extends Controller
{
    function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }
        // if ($user->tokens()->exists()) {
        //     $user->tokens()->delete();
        // }
        
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }

    function google(LoginGoogleRequest $request)
    {
        $token = $request->input('token');
        // > composer require google/apiclient
        try {
            $client = new GoogleClient(['client_id' => env("GOOGLE_CLIENT_ID")]);
            $payload = $client->verifyIdToken($token);
    
            // if (!$payload['email']) {
            //     throw new Error("Token unvalide");
            // }

            $email = $payload['email'];
    
            $user = User::where('email', $email)->first();
    
            if (!$user) {
                $user = User::create([
                    'first_name' => $payload['given_name'],
                    'last_name' => $payload['family_name'],
                    'email' => $email,
                    'password' => bcrypt(Str::random(16)),
                ]);
                event(new UserCreated($user));
            }
    
            $token = $user->createToken('auth_token')->plainTextToken;
                
            return response()->json([
                'token' => ['access_token' => $token, 'token_type' => 'Bearer'],
                'data' => [
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
                    'permissions' => $user->permissions->map(function ($permission) {
                        return $permission->name;
                    }),
                ]
            ]);
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }
    }

    function register(RegisterRequest $request)
    {
        if (User::where('email', $request->email)->exists()) {
            return response()->json(['message' => 'User already exists'], 409);
        }
        $user = User::create($request->validated());
        event(new UserCreated($user));
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json(['access_token' => $token, 'token_type' => 'Bearer']);
    }
    function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout successful']);
    }
    function me(Request $request)
    {
        $user = $request->user();
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
            'permissions' => $user->permissions->map(function ($permission) {
                return $permission->name;
            }),
        ];
    }
}
