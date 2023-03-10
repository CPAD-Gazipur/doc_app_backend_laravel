<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\DoctorsController;

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
Route::post('/login',[UsersController::class,'login']);
Route::post('/register',[UsersController::class,'register']);

Route::middleware('auth:sanctum')->group(function(){
    Route::get('/user',[UsersController::class,'index']);
    Route::post('/logout',[UsersController::class,'logout']);
    Route::post('/store-favorite',[UsersController::class,'storeFavoriteDoctor']);
    Route::post('/book-appointment',[AppointmentsController::class,'store']);
    Route::post('/complete-appointment',[DoctorsController::class,'store']);
    Route::get('/appointments',[AppointmentsController::class,'index']);
});
