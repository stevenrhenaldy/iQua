<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\GroupUser;
use Illuminate\Http\Request;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        return redirect()->route('group.edit', $group->uuid);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Group $group)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Group $group)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, GroupUser $member)
    {
        // dd($member);
        $groupUser = $member;
        return view("user.group.member.edit",[
            'group' => $group,
            'groupUser' => $groupUser
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, GroupUser $groupUser)
    {
        //
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
        return redirect()->back()->with("success", __("Member has been updated!"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, GroupUser $member)
    {
        if($member->role != "owner"){
            $member->delete();
        }
        return redirect()->route("group.edit", $group->uuid)->with("success", "Member has been deleted!");
    }
}
