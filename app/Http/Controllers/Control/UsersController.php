<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\UserStoreRequest;
use App\Http\Requests\Control\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public User $user;

    function __construct() {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if ($this->user->cannot('viewAny', User::class)) {
            return response()->json(['message' => 'forbidden'], 403);
        }
        return UserResource::collection(User::with('roles', 'permissions')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        DB::transaction(function() use($request) {
            $user = User::create([
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'email'=>$request->email,
                'password'=>Hash::make($request->password),
            ]);
            if ($request->roles) {
                $user->roles()->attach($request->roles);
            }
            if ($request->permissions) {
                $user->permissions()->attach($request->permissions);
            }
        }, 5);
        return response()->json(['message'=>'user added successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if ($this->user->cannot('view', $user)) {
            return response()->json(['message' => 'forbidden'], 403);
        }
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, User $user)
    {
        $roles = $request->roles;
        $permissions = $request->permissions;
        if (isset($roles)) {
            $user->roles()->sync($roles);
        }
        if (isset($permissions)) {
            $user->permissions()->sync($permissions);
        }
        return response()->json(['message'=>'user updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($this->user->cannot('delete', $user)) {
            return response()->json(['message' => 'forbidden'], 403);
        }
        $user->delete();
        return response()->json(['message'=>'user deleted successfully']);
    }
}
