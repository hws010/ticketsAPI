<?php

use App\Http\Controllers\Api\V1\AuthersController;
use App\Http\Controllers\Api\V1\TicketsController;
use App\Http\Controllers\AutherTicketsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->apiResource('tickets', TicketsController::class);

Route::middleware('auth:sanctum')->apiResource('authers', AuthersController::class);


// this here is a nested resource Laravel would see 'authers' '.' 'tickets' and knows that 'tickets
// belongs to 'auther' so it would generate a route like this:
// /authers/{auther}/tickets
// and 4 more routes like this for the rest of the CRUD
Route::middleware('auth:sanctum')->apiResource('authers.tickets', AutherTicketsController::class);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
