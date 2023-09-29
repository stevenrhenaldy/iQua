<?php

use App\Http\Controllers\API\GroupController;
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
    Route::get('group/{group}/user', [GroupController::class, 'group_user']);
    Route::apiResource('group', GroupController::class);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
