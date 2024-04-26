<?php

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Auth\UserAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\V2ApiController;
use App\Models\User;

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);


Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('credit', [ApiController::class, 'credit']);
    Route::post('fetch_senders', [ApiController::class, 'fetchSenders']);
    Route::post('send/{sender?}', [ApiController::class, 'send']);
    Route::get('report', [ApiController::class, 'report']);
});

Route::group(["prefix" => "v2"], function() {
    Route::post('/login', [UserAuthController::class,'login']);
    // Route::post('/register', [UserAuthController::class,'register']);

    Route::group(['middleware' => ['auth:api']], function() {
        Route::post('logout', [UserAuthController::class, 'logout']);
        Route::post('send_message', [V2ApiController::class, 'sendMessage']);
        Route::post('get_delivery_report', [V2ApiController::class, 'getDeliveryReport']);
        Route::post('credit', [V2ApiController::class, 'credit']);

        // Route::post('get_user', [V2ApiController::class, 'get_user']);
        // Route::post('fetch_senders', [V2ApiController::class, 'fetchSenders']);
    });
});