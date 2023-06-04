<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripStationRequest;
use App\Http\Requests\RemoveTripStationRequest;
use App\Models\TripStation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class TripStationController extends Controller
{
    public function add(CreateTripStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $tripStation = DB::table('trip_stations')
        ->where('trip_id', $input['trip_id'])
        ->where('station_id', $input['station_id'])
        ->first();
        if($tripStation != null)
        {
            return response()->json([
                'message' => 'Trip Station already exists',
                'station' => $tripStation
            ])->setStatusCode(400);
        }
        $tripStation = TripStation::create($input);

        return response()->json([
            'message' => 'Trip Station created successfully',
            'station' => $tripStation
        ]);
    }

    public function remove(RemoveTripStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $tripStation = TripStation::find($input['id']);
        if ($tripStation == null) {
            return response()->json([
                'message' => 'Trip station does not exist.'
            ])->setStatusCode(400);
        }
        $tripStation->delete();
        return response()->json([
            'message' => 'Trip Station deleted successfully'
        ]);
    }
}
