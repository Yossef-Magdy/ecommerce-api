<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\GovernorateResource;
use App\Models\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GovernorateResource::collection(Governorate::all());
    }

    /**
     * Display the specified resource.
     */
    public function show(Governorate $governorate)
    {
        return GovernorateResource::make($governorate);
    }
}
