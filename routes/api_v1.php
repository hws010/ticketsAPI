<?php

use App\Http\Controllers\Api\V1\AuthersController;
use App\Http\Controllers\Api\V1\TicketsController;
use App\Http\Controllers\AutherTicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function(){
    Route::apiResource('tickets', TicketsController::class)->except('update');
    Route::put('tickets/{ticket}', [TicketsController::class, 'replace']);
    Route::patch('tickets/{ticket}', [TicketsController::class, 'update']);

    Route::apiResource('authers', AuthersController::class);


    // this here is a nested resource Laravel would see 'authers' '.' 'tickets' and knows that 'tickets
    // belongs to 'auther' so it would generate a route like this:
    // /authers/{auther}/tickets
    // and 4 more routes like this for the rest of the CRUD
    Route::apiResource('authers.tickets', AutherTicketsController::class)->except('update');
    Route::put('authers/{auther}/tickets/{ticket}', [AutherTicketsController::class, 'replace']);
    Route::patch('authers/{auther}/tickets/{ticket}', [AutherTicketsController::class, 'update']);

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

});
