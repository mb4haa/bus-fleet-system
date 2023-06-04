<?php

namespace App\Http\Controllers;

use App\Models\Reservations;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\JsonResponse;

class ReservationsController extends Controller
{
    public function available(int $sourceCityId, int $destinationCityId):JsonResponse
    {
        // For simplification, this assumes 2 stations A,B can only exist with the same order A before B in at most 1 trip
        // However another trip, B before A may exist normally
        $trip = DB::table('trip_stations as ts1')
            ->select('ts1.trip_id')
            ->where('ts1.station_id', $destinationCityId)
            ->where('ts1.station_order', '>', function ($query) use ($sourceCityId) {
                $query->select('ts2.station_order')
                    ->from('trip_stations as ts2')
                    ->where('ts2.station_id', $sourceCityId)
                    ->whereRaw('ts2.trip_id = ts1.trip_id');
            })
            ->first();
        if (is_null($trip)) {
            return response()->json([
                'message' => 'Invalid route or stations.'
            ]);
        }

        $availableSeats = self::getAvailableSeats($sourceCityId, $destinationCityId, $trip);
        return response()->json([
            'message' => 'Available seats on trip retrieved successfully.',
            'data' => $availableSeats
        ]);
    }

    public function reserve(int $sourceCityId, int $destinationCityId, int $seat):JsonResponse
    {
        $trip = DB::table('trip_stations as ts1')
            ->select('ts1.trip_id')
            ->where('ts1.station_id', $destinationCityId)
            ->where('ts1.station_order', '>', function ($query) use ($sourceCityId) {
                $query->select('ts2.station_order')
                    ->from('trip_stations as ts2')
                    ->where('ts2.station_id', $sourceCityId)
                    ->whereRaw('ts2.trip_id = ts1.trip_id');
            })
            ->first();
        if (is_null($trip)) {
            return response()->json([
                'message' => 'Invalid route or stations.'
            ]);
        }
        $availableSeats = self::getAvailableSeats($sourceCityId, $destinationCityId, $trip);
        if(!(in_array($seat, $availableSeats))){
            return response()->json([
                'message' => 'Seat unavailable.'
            ]);
        }
        $user = auth()->user();
        $input = [
            'trip_id' => $trip->trip_id,
            'user_id' => $user->id,
            'start_station_id' => $sourceCityId,
            'end_station_id' => $destinationCityId,
            'seat_num' => $seat
        ];

        $reservation = Reservations::create($input);
        return response()->json([
            'message' => 'Reservation created successfully',
            'station' => $reservation
        ]);
    }

    public static function getAvailableSeats(int $sourceCityId, int $destinationCityId, $trip)
    {
        $stations = DB::table('trip_stations')
            ->select('station_id', 'station_order')
            ->where('trip_id', '=', $trip->trip_id)
            ->orderBy('station_order')
            ->get()->toArray();

        foreach ($stations as $station) {
            $stationId = $station->station_id;
            $stationOrder = $station->station_order;
            if ($stationId == $sourceCityId)
                $startOrder = $stationOrder;
            elseif ($stationId == $destinationCityId)
                $endOrder = $stationOrder;
        }

        $reservations = DB::table('reservations')
            ->select('start_station_id', 'end_station_id', 'seat_num')
            ->where('reservations.trip_id', '=', $trip->trip_id)->get();

        $takenSeats = [];
        foreach($reservations as $res){
            $getsOn = self::findStationOrder($stations, $res->start_station_id);
            $getsOff = self::findStationOrder($stations, $res->end_station_id);
            if(($getsOn < $endOrder && $getsOff > $startOrder)){
                array_push($takenSeats, $res->seat_num);
            }
        }
        $takenSeats = array_unique($takenSeats);
        $availableSeats = array_diff(range(1, 12), $takenSeats);
        return $availableSeats;
    }

    public static function findStationOrder(array $stations, int $target_station_id)
    {
        foreach ($stations as $station) {
            if ($station->station_id == $target_station_id) {
                return $station->station_order;
            }
        }
        return null;
    }

}