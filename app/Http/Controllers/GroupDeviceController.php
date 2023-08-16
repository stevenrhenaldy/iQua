<?php

namespace App\Http\Controllers;

use App\Models\DeviceEvent;
use App\Models\Devices;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class GroupDeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Group $group)
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Group $group)
    {
        return view("user.group.device.create",[
            "group" => $group,
        ]);
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

        return redirect()->route("group.device.show", [$group->uuid, $device->serial_number])->with("success", __("Device Added to Group Successfully"));
    }

    /**
     * Display the specified resource.
     */
    public function show(Group $group, Devices $device, Request $request)
    {
        if($device->group_id != $group->id){
            abort(404);
        }
        if ($request->ajax()) {
            $logs = DeviceEvent::query();
            $logs->where("device_id", $device->id);
            // $data = User::select('*');
            return DataTables::of($logs)
                ->addIndexColumn()
                ->rawColumns(['action'])
                ->make(true);
        }
        return view("user.group.device.show", [
            "group" => $group,
            "device" => $device,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Group $group, Devices $devices)
    {
        //
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
