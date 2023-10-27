<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{

    public function show(Request $request)
    {
        return $request->user();
    }

    public function edit_profile(Request $request)
    {
        $request->validate([
            "name" => ['required', "string", "max:255"],
            "email" => ['nullable', "string", "email", "max:255", "unique:users,email,".auth()->user()->id],
        ]);

        $user = $request->user();


        if($request->email && $request->email != $user->email){
            $user->email = $request->email;
            $user->email_verified_at = null;
            event(new Registered($user));
        }
        $user->name = $request->name;

        $user->save();

        return [
            "status" => "success",
            "message" => "User profile has been updated.",
            "user" => $user
        ];
    }
}
