<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripStationRequest;
use App\Http\Requests\RemoveTripStationRequest;
use App\Models\TripStation;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class TripStationController extends Controller
{
    public function add(CreateTripStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $tripStation = TripStation::create($input);

        return response()->json([
            'message' => 'Trip Station created successfully',
            'station' => $tripStation
        ]);
    }

    public function remove(RemoveTripStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $tripStation = TripStation::find($input['id'])->delete();

        return response()->json([
            'message' => 'Trip Station deleted successfully'
        ]);
    }
}
