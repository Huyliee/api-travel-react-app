<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;

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
Route::get('/tour',[TourController::class,'index']);
Route::get('/pagnination/tour',[TourController::class,'pagnination']);
Route::get('/search',[TourController::class,'search']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout']);
Route::post('/signup',[LoginController::class,'register']);
Route::post('/tour/store',[TourController::class,'store']);
Route::delete('/tour/delete/{id}',[TourController::class,'destroy']);
Route::get('/tour/show/{id}',[TourController::class,'show']);
Route::put('/tour/update/{id}',[TourController::class,'update']);
//user
Route::get('/user',[UserController::class,'index']);
Route::post('/user/store',[UserController::class,'store']);
Route::delete('/user/delete/{id}',[UserController::class,'destroy']);
Route::get('/user/show/{id}',[UserController::class,'show']);
Route::put('/user/update/{id}',[UserController::class,'update']);
//location
Route::get('/location/{mien}',[LocationController::class,'loadLocation']);