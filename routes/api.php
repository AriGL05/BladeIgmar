<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ImageController;
use Illuminate\Support\Facades\Log;


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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    Log::stack(['single','slack'])->info('Checking user with Sanctum!');
    return $request->user();
});
Route::post('register2', [AuthController::class,'register2']);
Route::post('login2', [AuthController::class,'login2']);
Route::post('logout2', [AuthController::class,'logout2']);

Route::group([
    'middleware' => 'auth:sanctum'
], function ($router) {

    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

    Route::post('image2',[ImageController::class,'uploadPhoto2']);
    Route::post('name2',[ImageController::class,'See2']);
    Route::post('look2/{filename}',[ImageController::class,'Find2']);
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('register', [AuthController::class,'register']);
    Route::post('login', [AuthController::class,'login']);
    Route::post('logout', [AuthController::class,'logout']);
    Route::post('refresh', [AuthController::class,'refresh']);
    Route::post('me', [AuthController::class,'me']);

    Route::post('image',[ImageController::class,'uploadPhoto']);
    Route::post('name',[ImageController::class,'See']);
    Route::post('look/{filename}',[ImageController::class,'Find']);
});


