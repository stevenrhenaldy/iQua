<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\DeviceController;
use App\Http\Controllers\Admin\DeviceTypeController;
use App\Http\Controllers\GroupAppletController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\GroupDeviceController;
use App\Http\Controllers\GroupMemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

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

Route::get('/email/verify/{id}/{hash}', function (Request $request) {

    $id = Crypt::decrypt($request->id);

    $user = App\Models\User::find($id);
    if(!$user){
        return abort(401);
    }

    if ($user->email_verification_key != $request->hash) {
        return abort(401);
    }

    $user->email_verified_at = Carbon\Carbon::now();
    $user->save();

    return view('auth.verified');

    // return redirect()->route("home");
})->name('verification.verify');



Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::middleware(["auth"])->group(function(){
    // Route::get("/admin", [AdminController::class, "index"])->name("admin.home");
    Route::get("profile", [ProfileController::class, "index"])->name("profile.index");
    Route::post("profile/profile", [ProfileController::class, "store_profile"])->name("profile.profile");
    Route::get("profile/password", function(){
        return redirect()->route("profile.index");
    });
    Route::get("profile/profile", function(){
        return redirect()->route("profile.index");
    });
    Route::post("profile/password", [ProfileController::class, "store_password"])->name("profile.password");
    Route::resource('group', GroupController::class);
    Route::resource('group.member', GroupMemberController::class);
    Route::resource('group.device', GroupDeviceController::class);
    // Route::get('group/{group}/applet', [GroupAppletController::class, "meta"])->name('group.applet.meta');
    Route::resource('group.applet', GroupAppletController::class);
    Route::get('/invitation/{code}', [GroupController::class, "verify_invite"])->name('invitation.verify');

});

Route::middleware(["auth"])->prefix("admin")->as("admin.")->group(function(){
    Route::get("/", [AdminController::class, "index"])->name("home");

    Route::resource('device', DeviceController::class);
    Route::resource('device_type', DeviceTypeController::class);
    // Route::resource('group.member', GroupMemberController::class);
    // Route::get('/invitation/{code}', [GroupController::class, "verify_invite"])->name('invitation.verify');
});
