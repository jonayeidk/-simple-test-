<?php

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

use App\Http\Controllers\AuthController;
 
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
  
Route::group(['middleware' => 'jwt.auth'], function () {
    
    Route::post('send-user-list',[AuthController::class, 'send_user_list']);
    Route::post('user-list',[AuthController::class, 'user_list']);
    Route::post('logout', [AuthController::class, 'logout']);
  
});

// unauthenticate data

Route::post('test-user-list',[AuthController::class, 'user_list']);