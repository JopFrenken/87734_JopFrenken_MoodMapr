<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\MoodController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::apiResource('user', AuthController::class);
Route::post('login', [AuthController::class, 'login']);

Route::apiResource('mood', MoodController::class);
Route::post('getDate', [MoodController::class, 'getDate']);
Route::get('getMoods', [MoodController::class, 'getMoods']);
Route::post('getUserMoods', [MoodController::class, 'getUserMoods']);
Route::post('getMonth', [MoodController::class, 'getMonth']);
Route::post('getWeek', [MoodController::class, 'getWeek']);