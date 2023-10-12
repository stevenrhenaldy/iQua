<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        return view("user.profile.index", [
            "user" => $user
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_profile(Request $request)
    {
        $request->validate([
            "name" => ['required', "string", "max:255"],
            "email" => ['required', "string", "email", "max:255", "unique:users,email,".auth()->user()->id],
        ]);

        $user = Auth::user();
        $user->update([
            "name" => $request->name,
            "email" => $request->email,
        ]);

        return redirect()->route("profile.index")->with("success", "Profile updated.");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_password(Request $request)
    {
        $request->validate([
            "current_password" => ['required', "string", "max:255"],
            "new_password" => ['required', "string", "min:8", "confirmed"],
        ]);

        // dd($request);

        $user = Auth::user();
        $user->update([
            "password" => $request->new_password,
        ]);

        return redirect()->route("profile.index")->with("success", "Password has been updated.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
