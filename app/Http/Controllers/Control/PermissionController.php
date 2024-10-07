<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Requests\Control\StorePermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Roles\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{

    function __construct()
    {
        $this->modelName = 'permission';
        $this->authorizeResource(Permission::class, 'permission');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return PermissionResource::collection(Permission::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePermissionRequest $request)
    {
        Permission::create($request->validated());
    }

    /**
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return PermissionResource::make($permission);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Permission $permission)
    {
        $permission->update($request->validated());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Permission $permission)
    {
        $permission->delete();
    }
}
