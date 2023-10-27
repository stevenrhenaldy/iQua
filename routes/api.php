<?php

use App\Http\Controllers\API\GroupController;
use App\Http\Controllers\API\GroupDeviceController;
use App\Http\Controllers\API\GroupMemberController;
use App\Http\Controllers\API\UserProfileController;
use App\Http\Controllers\API\UserRegisterController;
use App\Models\Group;
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

Route::post('register', [UserRegisterController::class, 'store']);

Route::group(['middleware' => ['auth:api', 'verified']], function(){
    Route::get('group/timezone', [GroupController::class, 'timezones']);
    Route::apiResource('group', GroupController::class);
    Route::apiResource('group.device', GroupDeviceController::class);
    Route::get('group/{group}/device/{device}/logs', [GroupDeviceController::class, 'logs']);
    Route::apiResource('group.member', GroupMemberController::class);
    Route::get('user', [UserProfileController::class, 'show']);
    Route::put('user/profile', [UserProfileController::class, 'edit_profile']);

});


// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
