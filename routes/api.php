<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\CustomAuthenticate;
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

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::post('/booking', [BookingController::class, 'createBooking']);

Route::prefix('/user')->middleware(CustomAuthenticate::class)->group(function () {
    Route::get('/', [UserController::class, 'getUserInfo']);
    Route::get('/booking', [BookingController::class, 'getUserBooking']);
});
