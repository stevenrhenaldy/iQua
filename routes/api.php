<?php

use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\GroupDeviceController;
use App\Http\Controllers\API\GroupMemberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::group(['middleware' => ['auth:api']], function(){
    Route::get('group/timezone', [GroupController::class, 'timezones']);
    Route::apiResource('group', GroupController::class);
    Route::apiResource('group.device', GroupDeviceController::class);
    Route::apiResource('group.member', GroupMemberController::class);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
