<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RsvpController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/rsvp', [RsvpController::class, 'store']);
Route::get('/validate-qr/{token}', [RsvpController::class, 'validateQr']);
Route::post('/mark-entry/{token}', [RsvpController::class, 'markEntry']);
Route::get('/guests', function () {
    return \App\Models\Guest::with('subGuests')->get();
});




Route::post('rsvp/individual', [RsvpController::class, 'storeIndividual']);
Route::post('rsvp/couple', [RsvpController::class, 'storeCouple']);
Route::post('rsvp/vip', [RsvpController::class, 'storeVip']);