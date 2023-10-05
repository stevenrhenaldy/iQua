<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationMail;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Carbon\Carbon;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class GroupController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $groups = Auth::user()->groups;
        // $groups = Group::all();
        return $groups;
    }

    public function timezones()
    {
        $timezone_identifiers = DateTimeZone::listIdentifiers();
        return $timezone_identifiers;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $timezone_identifiers = DateTimeZone::listIdentifiers();

        $request->validate([
            "name" => ["required", "string", "max:30"],
            "description" => ["nullable", "string", "max:255"],
            "timezone" => "required|string|max:255",
        ]);

        if(!in_array($request->timezone, $timezone_identifiers)){
            return response()->json([
                "status" => "error",
                "message" => "Invalid Timezone",
            ], 400);
        }

        $group = Group::create([
            "name" => $request->name,
            "description" => $request->description,
            "timezone" => $request->timezone,
        ]);

        GroupUser::create([
            "user_id" => Auth::user()->id,
            "group_id" => $group->id,
            "role" => "owner",
            "accepted_at" => Carbon::now(),
        ]);

        return response()->json([
            "status" => "success",
            "message" => "Group Created Successfully",
            "group" => $group,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $devices = $group->devices;
        return response()->json([
            "status" => "success",
            "group" => $group,
            "devices" => $devices,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            "name" => ["required", "string", "max:30"],
            "description" => ["nullable", "string", "max:255"],
            "timezone" => ["nullable", "string", "max:255"],
        ]);

        if(!in_array($request->timezone, DateTimeZone::listIdentifiers( DateTimeZone::ALL ))){
            return response()->json([
                "status" => "error",
                "message" => __("Invalid Timezone"),
            ], 400);
        }

        $group->update([
            "name" => $request->name,
            "description" => $request->description,
            "timezone" => $request->timezone,
        ]);

        return response()->json([
            "status" => "success",
            "message" => __("Data has been updated"),
            "group" => $group,
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }

}
