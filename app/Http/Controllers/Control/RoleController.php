<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreRoleRequest;
use App\Http\Requests\Control\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Roles\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{

    function __construct()
    {
        $this->modelName = 'role';
        $this->authorizeResource(Role::class, 'role');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RoleResource::collection(Role::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        Role::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return RoleResource::make($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $role->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
    }
}
