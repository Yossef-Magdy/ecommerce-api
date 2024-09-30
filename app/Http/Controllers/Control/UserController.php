<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\UserStoreRequest;
use App\Http\Requests\Control\UserUpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    function __construct()
    {
        $this->modelName = 'user';
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return UserResource::collection(User::with('roles', 'permissions')->paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            if ($request->roles) {
                $user->roles()->attach($request->roles);
            }
            if ($request->permissions) {
                $user->permissions()->attach($request->permissions);
            }
        }, 5);
        return $this->createdResponse();
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
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
        return $this->updatedResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();
        return $this->deletedResponse();
    }
}
