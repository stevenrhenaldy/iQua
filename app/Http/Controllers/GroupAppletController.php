<?php

namespace App\Http\Controllers;

use App\Models\Applet;
use App\Models\Group;
use Illuminate\Http\Request;

class GroupAppletController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        $applets = $group->applets;
        return view('user.group.applet.index', compact('group', 'applets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Group $group, Request $request)
    {
        if($request->ajax()){
            $request->validate([
                "device" => ["required", "exists:devices,serial_number"],
            ]);
            $device = $group->devices()->where('serial_number', $request->device)->first();
            $entitites = $device->type->entities;
            // dd($device->type->entities);
            return response()->json([
                "status" => "success",
                "data" => $entitites
            ]);
        }
        $devices = $group->devices;
        return view('user.group.applet.create', compact('group', 'devices'));
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
    public function show(Group $group, Applet $applet)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, Applet $applet)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group, Applet $applet)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, Applet $applet)
    {
        //
    }
}
