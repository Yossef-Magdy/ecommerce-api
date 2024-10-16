<?php

namespace App\Http\Controllers\Control;

use App\Http\Controllers\Controller;
use App\Http\Resources\PermissionResource;
use App\Models\Roles\Permission;

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
     * Display the specified resource.
     */
    public function show(Permission $permission)
    {
        return PermissionResource::make($permission);
    }

}
