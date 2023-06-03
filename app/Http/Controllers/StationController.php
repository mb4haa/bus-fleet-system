<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStationRequest;
use App\Http\Requests\RemoveStationRequest;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class StationController extends Controller
{
    public function add(CreateStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $station = Station::create($input);

        return response()->json([
            'message' => 'Station created successfully',
            'station' => $station
        ]);
    }

    public function remove(RemoveStationRequest $request):JsonResponse
    {
        $input = $request->all();
        $station = Station::find($input['id'])->delete();

        return response()->json([
            'message' => 'Station deleted successfully'
        ]);
    }
}