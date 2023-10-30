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
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "status" => ["nullable", "in:0, 1"],
            "if_device" => ["required", "string"],
            "if_meta" => ["required", "exists:entities,id"],
            "if_condition" => ["required", "in:==, !=, >, <, >=, <="],
            "if_value" => ["required", "string"],
            "do_device" => ["required", "string"],
            "do_meta" => ["required", "exists:entities,id"],
            "do_value" => ["required", "string"],
        ]);
        $if_device = $group->devices()->where([['serial_number', $request->if_device], ['group_id', $group->id]])->first();
        if(!$if_device) return redirect()->back()->with('error', 'Device Invalid.');
        $do_device = $group->devices()->where([['serial_number', $request->do_device], ['group_id', $group->id]])->first();
        if(!$do_device) return redirect()->back()->with('error', 'Device Invalid.');

        $applet = Applet::create([
            "user_id" => $request->user()->id,
            "group_id" => $group->id,
            "name" => $request->name,
            "status" => $request->status
        ]);

        $if_applet = $applet->nodes()->create([
            "type" => "trigger",
            "applet_id" => $applet->id,
            "group_id" => $group->id,
            "device_id" => $if_device->id,
            "entity_id" => $request->if_meta,
            "condition" => $request->if_condition,
            "value" => $request->if_value,
        ]);

        $do_applet = $applet->nodes()->create([
            "type" => "action",
            "applet_id" => $applet->id,
            "group_id" => $group->id,
            "device_id" => $do_device->id,
            "entity_id" => $request->do_meta,
            "value" => $request->do_value,
        ]);

        return redirect()->route('group.applet.show', [$group->uuid, $applet->id])->with('success', 'Applet has been created.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, Applet $applet)
    {
        if(!$group->applets()->where('id', $applet->id)->first()){
            return redirect()->route('group.applet.index', $group->uuid)->with('error', 'Applet not found.');
        }
        $if_applet = $applet->nodes()->where('type', 'trigger')->first();
        $do_applet = $applet->nodes()->where('type', 'action')->first();
        return view('user.group.applet.show', compact('group', 'applet', 'if_applet', 'do_applet'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, Applet $applet, Request $request)
    {
        if($request->ajax()){
            $request->validate([
                "device" => ["required", "exists:devices,serial_number"],
            ]);
            $device = $group->devices()->where('serial_number', $request->device)->first();
            $entitites = $device->type->entities;
            return response()->json([
                "status" => "success",
                "data" => $entitites
            ]);
        }
        $devices = $group->devices;

        $if_applet = $applet->nodes()->where('type', 'trigger')->first();
        $do_applet = $applet->nodes()->where('type', 'action')->first();

        return view('user.group.applet.edit', compact('group', 'devices', 'if_applet', 'do_applet', 'applet'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group, Applet $applet)
    {
        $request->validate([
            "name" => ["required", "string", "max:255"],
            "status" => ["nullable", "in:0, 1"],
            "if_device" => ["required", "string"],
            "if_meta" => ["required", "exists:entities,id"],
            "if_condition" => ["required", "in:==, !=, >, <, >=, <="],
            "if_value" => ["required", "string"],
            "do_device" => ["required", "string"],
            "do_meta" => ["required", "exists:entities,id"],
            "do_value" => ["required", "string"],
        ]);
        $if_device = $group->devices()->where([['serial_number', $request->if_device], ['group_id', $group->id]])->first();
        if(!$if_device) return redirect()->back()->with('error', 'Device Invalid.');
        $do_device = $group->devices()->where([['serial_number', $request->do_device], ['group_id', $group->id]])->first();
        if(!$do_device) return redirect()->back()->with('error', 'Device Invalid.');

        $applet->update([
            "name" => $request->name,
            "status" => $request->status
        ]);

        $if_applet = $applet->nodes()->where('type', 'trigger')->first();
        $do_applet = $applet->nodes()->where('type', 'action')->first();
        $if_applet->update([
            "device_id" => $if_device->id,
            "entity_id" => $request->if_meta,
            "condition" => $request->if_condition,
            "value" => $request->if_value,
        ]);

        $do_applet->update([
            "device_id" => $do_device->id,
            "entity_id" => $request->do_meta,
            "value" => $request->do_value,
        ]);

        return redirect()->route('group.applet.show', [$group->uuid, $applet->id])->with('success', 'Applet has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, Applet $applet)
    {
        //
    }
}
