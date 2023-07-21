<?php

namespace App\Http\Controllers;

use App\Mail\SendInvitationMail;
use App\Models\Group;
use App\Models\GroupUser;
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
        return view("user.group.index", [
            "groups" => $groups,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("user.group.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", "string", "max:30"],
            "description" => ["nullable", "string", "max:255"],
            // "timezone" => "required|string|max:255",
        ]);

        $group = Group::create([
            "name" => $request->name,
            "description" => $request->description,
            "timezone" => "Asia/Taipei",
        ]);

        GroupUser::create([
            "user_id" => Auth::user()->id,
            "group_id" => $group->id,
            "role" => "owner",
            "accepted_at" => Carbon::now(),
        ]);

        return redirect()->route("group.index");
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group)
    {
        $devices = $group->devices;
        return view("user.group.show", [
            "group" => $group,
            "devices" => $devices
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group)
    {
        $timezones = DateTimeZone::listIdentifiers( DateTimeZone::ALL );
        return view("user.group.edit", [
            "group" => $group,
            "timezones" => $timezones
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group)
    {
        $request->validate([
            "action" => ['required', 'string', 'in:edit,add-member'],
        ]);

        if($request->action == "add-member"){

            $request->validate([
                "email" => ["required", "email"]
            ]);

            $expires_at = Carbon::now()->addDays(3)->hour(23)->minute(59)->second(59);
            $groupUser = GroupUser::create([
                "group_id" => $group->id,
                "role" => "member",
                "active_until" => $expires_at
            ]);

            $code = Crypt::encrypt($groupUser->id);
            $invitation_link = route("invitation.verify", $code);
            $mailData = [
                "initiator" => Auth::user()->name,
                "link" => $invitation_link
            ];

            Mail::to($request->email)->send(new SendInvitationMail($group, $groupUser, $mailData));

            return redirect()->route("group.edit", $group->id)->with("success", "Email has been sent successfully");

        }else if($request->action == "edit"){

            $request->validate([
                "name" => ["required", "string", "max:30"],
                "description" => ["nullable", "string", "max:255"],
                "timezone" => ["nullable", "string", "max:255"],
            ]);

            if(!in_array($request->timezone, DateTimeZone::listIdentifiers( DateTimeZone::ALL ))){
                return redirect()->route("group.edit", $group->id)->with("success", "Error Timezone");
            }

            $group->update([
                "name" => $request->name,
                "description" => $request->description,
                "timezone" => $request->timezone,
            ]);

            return redirect()->route("group.edit", $group->id)->with("success", __("Data has been updated"));

        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group)
    {
        //
    }

    public function verify_invite($code){
        $id = Crypt::decrypt("$code");
        $groupUser = GroupUser::where("id", $id)->first();
        if(!$groupUser){
            return "error";
        }
        if($groupUser->expires){
            return "error";
        }
        $user_id = Auth::user()->id;
        $group = $groupUser->group;
        $check_other = GroupUser::where([["group_id", $group->id], ["user_id", $user_id]])->first();

        if($check_other){
            return "error";
        }
        $groupUser->user_id = $user_id;
        $groupUser->accepted_at = Carbon::now();
        $groupUser->save();
        // $group->id;
        return redirect()->route('group.show', $group->id);
    }
}
