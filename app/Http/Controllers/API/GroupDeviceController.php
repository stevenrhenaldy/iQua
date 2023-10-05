<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\DeviceEvent;
use App\Models\Devices;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;

class GroupDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        $devices = $group->devices;
        return response()->json([
            "status" => "success",
            "devices" => $devices,
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Group $group)
    {
        $request->validate([
            "serial_number" => ['required', "string", "exists:devices,serial_number"]
        ]);

        $device = Devices::where("serial_number", $request->serial_number)->firstOrFail();
        $device->group_id = $group->id;
        $device->assigned_at = Carbon::now();
        $device->assigned_by_id = auth()->user()->id;
        $device->save();

        return response()->json([
            "status" => "success",
            "device" => $device,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, Devices $device)
    {
        if($device->group_id != $group->id){
            abort(404);
        }

        return response()->json([
            "status" => "success",
            "device" => $device,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function logs(Group $group, Devices $device, Request $request)
    {
        if($device->group_id != $group->id){
            abort(404);
        }

        $request->validate([
            "limit" => ['nullable', "integer", "min:1"],
            "page" => ['nullable', "integer", "min:1"],
        ]);

        $logs = DeviceEvent::query();
        $logs->where("device_id", $device->id);


        if($request->limit){
            $logs->limit($request->limit);
        }
        $logs->orderBy("created_at", "desc");
        if($request->page){
            $logs = $logs->paginate($request->page);
        }else{
            $logs = $logs->get();
        }


        return response()->json([
            "status" => "success",
            "device" => $device,
            "log" => $logs,
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Group $group, Devices $devices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Group $group, Devices $devices)
    {
        //
    }
}
