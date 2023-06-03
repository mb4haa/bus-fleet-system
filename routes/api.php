<?php

use App\Http\Controllers\BusTripController;
use App\Http\Controllers\StationController;
use App\Http\Controllers\TripStationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'auth.role:admin'])->group(function () {
    Route::post('/addStation', [StationController::class, 'add']);
    Route::delete('/removeStation', [StationController::class, 'remove']);
    Route::post('/addTripStation', [TripStationController::class, 'add']);
    Route::delete('/removeTripStation', [TripStationController::class, 'remove']);
    Route::post('/addBusTrip', [BusTripController::class, 'add']);
    Route::delete('/removeBusTrip', [BusTripController::class, 'remove']);
});

