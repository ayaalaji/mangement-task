<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(AuthController::class)->group(function () {
    Route::post('login', 'login');
    Route::post('register', 'register');
    Route::post('logout', 'logout');
    Route::post('refresh', 'refresh');
});
Route::middleware('auth:api')->group(function () {
    Route::controller(UserController::class)->group(function(){
        
    });
    Route::controller(TaskController::class)->group(function(){
        Route::get('tasks',[TaskController::class,'index']);
        Route::post('task',[TaskController::class,'store']);
        Route::get('task/{task}',[TaskController::class,'show']);
        Route::put('task/{task}',[TaskController::class,'update']);
        Route::put('task/{task}',[TaskController::class,'updateUser']);
        Route::delete('task/{task}',[TaskController::class,'destroy']);
    });
    Route::controller(UserController::class)->group(function(){
        Route::get('users',[UserController::class,'index']);
        Route::post('user',[UserController::class,'store']);
        Route::get('user/{user}',[UserController::class,'show']);
        Route::put('user/{user}',[UserController::class,'update']);
        Route::delete('user/{user}',[UserController::class,'destroy']);
    });
});