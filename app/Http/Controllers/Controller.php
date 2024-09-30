<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    protected string $modelName = "resource";
    function createdResponse()
    {
        return response()->json([
            'message' => "$this->modelName created successfully"
        ], 200);
    }
    function updatedResponse()
    {
        return response()->json([
            'message' => "$this->modelName updated successfully"
        ], 200);
    }
    function deletedResponse()
    {
        return response()->json([
            'message' => "$this->modelName deleted successfully"
        ], 200);
    }
}
