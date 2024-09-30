<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    protected string $modelName = "resource";
    function createdResponse($data = null)
    {
        $response = ['message' => "$this->modelName created successfully"];
        if (isset($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200);
    }
    function updatedResponse($data = null)
    {
        $response = ['message' => "$this->modelName updated successfully"];
        if (isset($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200);
    }
    function deletedResponse($data = null)
    {
        $response = ['message' => "$this->modelName deleted successfully"];
        if (isset($data)) {
            $response['data'] = $data;
        }
        return response()->json($response, 200);
    }
}
