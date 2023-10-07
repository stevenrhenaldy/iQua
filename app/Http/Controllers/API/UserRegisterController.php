<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Inertia\Response;

class UserRegisterController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'grant_type' => ['required', 'string'],
            'client_id' => ['required', 'string'],
            'client_secret' => ['required', 'string']
        ]);

        if($request->grant_type !== "register"){
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $check_client = DB::table('oauth_clients')->where([
            "id" => $request->client_id,
            "secret" => $request->client_secret
        ])->count();

        if(!$check_client){
            return response()->json([
                'message' => 'Unauthenticated'
            ], 401);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => ['required', 'regex:/\w*$/', 'max:255', 'unique:users,username'],
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        // $data = Auth::login($user);

        return [
            "status" => "success",
            "message" => "User has been added."
        ];
    }
}
