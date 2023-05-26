<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Api\Location;

class LocationController extends Controller
{
    public function loadLocation($mien){
        $location = Location::where('domain',$mien)->take(5)->get();

        return response()->json([
            'success' => true,
            'data' => $location
        ]);
    }
}
