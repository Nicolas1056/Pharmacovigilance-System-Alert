<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AlertController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function (){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/medications/search', [OrderController::class, 'search']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::get('/customers/{id}', [CustomerController::class, 'show']);
    Route::post('/alerts/send', [AlertController::class, 'send']);
});