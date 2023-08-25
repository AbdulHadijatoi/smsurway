<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Models\User;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
    // return "Hello From user";

    // return $request->user()->msgs;
// });


Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);
// Route::get('report', [ApiController::class, 'report']);
// Route::post('report', function(Request $request){
    // return "Hello From API Report".$request->send_id;
    // return $request->email;
    // $user=User::find(1)->report($request->send_id);
    // return $user;
    // return $request->user()->msgs;

// });

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('credit', [ApiController::class, 'credit']);
    Route::post('send', [ApiController::class, 'send']);
    Route::get('report', [ApiController::class, 'report']);
    // Route::get('products/{id}', [ProductController::class, 'show']);
    // Route::post('create', [ProductController::class, 'store']);
    // Route::put('update/{product}',  [ProductController::class, 'update']);
    // Route::delete('delete/{product}',  [ProductController::class, 'destroy']);
});



// Route::post('register', [AuthController::class, 'register']);
// Route::post('signin', [AuthController::class, 'signin']);
// Route::apiResource('msg', MsgController::class)->middleware('auth:api');


// Route::post('register',[AuthController::class,'register']);
// Route::post('login',[AuthController::class,'login']);

// Route::group(['middleware' => ['auth:api']], function () {
//     Route::post('logout', [AuthController::class, 'logout']);
// });