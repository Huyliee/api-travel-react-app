<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TourController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\LocationController;
use App\Http\Controllers\Api\NewsController;
use App\Http\Controllers\Api\DateGoController;
use App\Http\Controllers\Api\PaymentsController;
use App\Http\Controllers\Api\OrderController;

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
//tour
Route::get('/tour',[TourController::class,'index']);
Route::get('/detail/{id}',[TourController::class,'detail']);
Route::get('/pagnination/tour',[TourController::class,'pagnination']);
Route::get('/search',[TourController::class,'search']);
Route::post('/tour/store',[TourController::class,'store']);
Route::delete('/tour/delete/{id}',[TourController::class,'destroy']);
Route::get('/tour/show/{id}',[TourController::class,'show']);
Route::put('/tour/update/{id}',[TourController::class,'update']);
Route::post('/tour/checkout/{id}',[TourController::class,'checkout']);
Route::get('/order/detail/{id}',[TourController::class,'detailOrder']);
//Login and Register
Route::post('/login',[LoginController::class,'login']);
Route::post('/logout',[LoginController::class,'logout']);
Route::post('/signup',[LoginController::class,'register']);
//user
Route::get('/user',[UserController::class,'index']);
Route::post('/user/store',[UserController::class,'store']);
Route::delete('/user/delete/{id}',[UserController::class,'destroy']);
Route::get('/user/show/{id}',[UserController::class,'show']);
Route::put('/user/update/{id}',[UserController::class,'update']);
//location
Route::get('/location/{mien}',[LocationController::class,'loadLocation']);
//tin tức 
Route::get('/news',[NewsController::class,'index']);
Route::post('/news/store',[NewsController::class,'store']);
//Danh sách ngày đi
Route::get('/datego',[DateGoController::class,'index']);
//Thanh toán online
Route::post('/create-payment',[PaymentsController::class,'createPayments']);
Route::post('/momo-payment',[PaymentsController::class,'MomoPayment']);
// Đơn đặt tour
Route::get('/order',[OrderController::class,'index']);
Route::get('/order/{id}',[OrderController::class,'findOrder']);
Route::get('/order/{id}',[OrderController::class,'orderCustomer']);
