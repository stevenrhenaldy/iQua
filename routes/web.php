<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupDeviceController;
use App\Http\Controllers\GroupMemberController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

URL::forceScheme('https');

// Route::get('/', function () {
//     return view('welcome');
// })->name('home');

Auth::routes();

// Route::get('/email/verify', function () {
//     return view('auth.verify');
// })->middleware('auth')->name('verification.notice');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect()->route("home");
})->middleware(['auth', 'signed'])->name('verification.verify');



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(["auth"])->group(function(){
    // Route::get("/admin", [AdminController::class, "index"])->name("admin.home");

    Route::resource('group', GroupController::class);
    Route::resource('group.member', GroupMemberController::class);
    Route::resource('group.device', GroupDeviceController::class);

    Route::get('/invitation/{code}', [GroupController::class, "verify_invite"])->name('invitation.verify');
});

Route::middleware(["auth"])->prefix("admin")->as("admin.")->group(function(){
    Route::get("/", [AdminController::class, "index"])->name("home");

    Route::resource('device', DeviceController::class);
    // Route::resource('group.member', GroupMemberController::class);
    // Route::get('/invitation/{code}', [GroupController::class, "verify_invite"])->name('invitation.verify');
});
