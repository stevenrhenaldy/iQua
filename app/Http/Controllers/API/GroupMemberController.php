<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\SendInvitationMail;
use App\Models\Group;
use App\Models\GroupUser;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class GroupMemberController extends Controller
{
    public function __construct(Request $request)
    {

        $this->middleware(function ($request, $next) {

            $group_uuid = $request->group->uuid;
            $group = \App\Models\Group::where("uuid", $group_uuid)->firstOrFail();
            // dd($group);
            $user = $request->user();
            $user_count = $group->users()->where(['user_id' => $user->id])->count();

            if(!$user_count){
                abort(403);
            }

            return $next($request);
        });

    }
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        $users = $group->usersAll;
        return response()->json([
            "status" => "success",
            "users" => $users,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Group $group)
    {
        $request->validate([
            "username" => ["required", "string"]
        ]);

        $expires_at = Carbon::now()->addDays(3)->hour(23)->minute(59)->second(59);

        $user = User::where("username", $request->username)->first();

        if(!$user){
            return response()->json([
                "status" => "error",
                "message" => __("User not found! Please check the username"),
            ], 400);
        }

        $groupUser = GroupUser::create([
            "group_id" => $group->id,
            "user_id" => $user->id,
            "role" => "member",
            "active_until" => $expires_at
        ]);

        $code = Crypt::encrypt($groupUser->id);
        $invitation_link = route("invitation.verify", $code);
        $mailData = [
            "initiator" => Auth::user()->name,
            "link" => $invitation_link
        ];

        Mail::to($user->email)->send(new SendInvitationMail($group, $groupUser, $mailData));

        return response()->json([
            "status" => "success",
            "message" => __("Invitation Mail has been sent"),
        ], 200);

    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, GroupUser $member)
    {
        $groupUser = $member;
        $user = $member->user;
        return response()->json([
            "status" => "success",
            "group_user" => $groupUser,
            // "user" => $user,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group, GroupUser $member)
    {
        $request->validate([
            "name" => ["nullable", "string", "max:255"],
        ]);

        $member->name_alias = $request->name;

        if($member->role != "owner"){
            $request->validate([
                "role" => ['required', 'string', 'in:administrator,member']
            ]);
            $member->role = $request->role;
        }


        $member->save();
        return response()->json([
            "status" => "success",
            "message" => "Member has been updated!",
            "member" => $member,
        ], 200);
        // return redirect()->back()->with("success", "Member has been updated!");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, GroupUser $member)
    {
        if($member->role != "owner"){
            $member->delete();
        }
        return response()->json([
            "status" => "success",
            "message" => __("Member has been unlinked from the group!"),
        ], 200);
    }
}
