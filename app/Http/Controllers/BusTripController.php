<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripRequest;
use App\Http\Requests\RemoveTripRequest;
use App\Models\BusTrip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusTripController extends Controller
{
    public function add(CreateTripRequest $request): JsonResponse
    {
        $input = $request->all();

        $trip = DB::table('bus_trips')
            ->where('name', $input['name'])
            ->first();
        if ($trip != null) {
            return response()->json([
                'message' => 'Bus trip with name already exists',
                'station' => $trip
            ])->setStatusCode(400);
        }

        $trip = BusTrip::create($input);
        return response()->json([
            'message' => 'Trip created successfully',
            'trip' => $trip
        ]);
    }

    public function remove(RemoveTripRequest $request): JsonResponse
    {
        $input = $request->all();
        $trip = BusTrip::find($input['id'])->delete();
        return response()->json([
            'message' => 'Trip deleted successfully'
        ]);
    }
}