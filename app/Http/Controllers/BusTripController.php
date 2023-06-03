<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateTripRequest;
use App\Http\Requests\RemoveTripRequest;
use App\Models\BusTrip;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class BusTripController extends Controller
{
    public function add(CreateTripRequest $request):JsonResponse
    {
        $input = $request->all();
        $trip = BusTrip::create($input);

        return response()->json([
            'message' => 'Trip created successfully',
            'trip' => $trip
        ]);
    }

    public function remove(RemoveTripRequest $request):JsonResponse
    {
        $input = $request->all();
        $trip = BusTrip::find($input['id'])->delete();

        return response()->json([
            'message' => 'Trip deleted successfully'
        ]);
    }
}
