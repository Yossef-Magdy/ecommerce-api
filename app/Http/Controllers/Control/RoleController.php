<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StoreRoleRequest;
use App\Http\Requests\Control\UpdateRoleRequest;
use App\Http\Resources\RoleResource;
use App\Models\Roles\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        DB::beginTransaction();
        try {
            $role = Role::create($request->validated());
            $role->permissions()->attach($request->permissions);
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 500);
        }
        return $this->createdResponse();
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
        DB::beginTransaction();
        try {
            $role->update($request->validated());
            $role->permissions()->sync($request->permissions);
            DB::commit();
        } catch (Exception $error) {
            DB::rollBack();
            return response()->json([
                'message' => $error->getMessage()
            ], 500);
        }
        return $this->updatedResponse();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $role->delete();
        return $this->deletedResponse();
    }
}
