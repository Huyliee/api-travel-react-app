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
use App\Http\Controllers\Api\AnalyticController;
use App\Http\Controllers\Api\GuideController;

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
Route::get('/user/detail/{id}',[UserController::class,'show']);
Route::post('/user/store',[UserController::class,'store']);
Route::put('/user/changepass/{id}',[UserController::class,'changePass']);
Route::delete('/user/delete/{id}',[UserController::class,'destroy']);
Route::get('/user/show/{id}',[UserController::class,'show']);
Route::put('/user/update/{id}',[UserController::class,'update']);
Route::get('/user/social/{email}',[UserController::class,'getCustomer']);
Route::get('/user/faceio/{email}',[UserController::class,'findUserFace']);
//location
Route::get('/location/{mien}',[LocationController::class,'loadLocation']);
//tin tức 
Route::get('/news',[NewsController::class,'index']);
Route::post('/news/store',[NewsController::class,'store']);
Route::get('/news/detail/{id}',[NewsController::class,'show']);
Route::delete('/news/delete/{id}',[NewsController::class,'destroy']);
//Danh sách ngày đi
Route::get('/datego',[DateGoController::class,'index']);
Route::get('/datego/order/{id}',[DateGoController::class,'order']);
Route::post('/datego/store',[DateGoController::class,'store']);
//Thanh toán online
Route::post('/create-payment',[PaymentsController::class,'createPayments']);
Route::post('/momo-payment',[PaymentsController::class,'MomoPayment']);
Route::post('/momo-payment/store',[PaymentsController::class,'paymentsOrder']);
// Đơn đặt tour
Route::get('/order',[OrderController::class,'index']);
Route::get('/order/{id}',[OrderController::class,'findOrshder']);
Route::get('/order/detail/{id}',[OrderController::class,'show']);
Route::get('/order/{id}',[OrderController::class,'orderCustomer']);
Route::delete('/order/delete/{id}',[OrderController::class,'destroy']);
Route::put('/order/accept/{id}',[OrderController::class,'accept']);
//Thống kê
Route::get('/analytic/quantityData',[AnalyticController::class,'quantityDataTable']);
Route::get('/analytic/revenueData',[AnalyticController::class,'revenueData']);
//Hướng dẫn viên
Route::get('/guide',[GuideController::class,'index']);
