<?php

namespace App\Http\Controllers;

use App\Models\DeviceEvent;
use App\Models\Devices;
use App\Models\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $user = Auth::user();
        $user_is_assigned = $group->users()->where(['user_id' => $user->id])->first();
        if(!$user_is_assigned){
            abort(401);
        }
        if($device->group_id != $group->id){
            abort(404);
        }
        $entities = $device->type->entities;
        if ($request->ajax()) {
            // dd($device->type);

            $logs = DeviceEvent::query();
            $logs->where("device_id", $device->id);
            return DataTables::of($logs)
                ->addIndexColumn()
                ->addColumn('value', function ($row) use ($entities){
                    $entity = $entities->where("name", $row->event)->first();
                    if(is_null($entity)){
                        return $row->value;
                    }
                    $options = $entity->options;
                    if(is_null($options)){
                        return $row->value;
                    }
                    $options = $entity->options;
                    // dd($row->value);
                    return $options[$row->value];
                })
                ->addColumn('time', function ($row) use ($group) {
                    return $row->created_at->setTimezone($group->timezone)->format("d/m/Y H:i:s");
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view("user.group.device.show", [
            "group" => $group,
            "device" => $device,
            "entities" => $entities,
        ]);
    }

    public function show_chart(Request $request, Group $group, Devices $device){
        $interval = $request->interval;
        $entity = $request->entity;
        if($interval == "daily"){

            $data= DB::select("SELECT *
            FROM device_events
            WHERE created_at IN (
                SELECT MAX(created_at)
                FROM device_events
                WHERE DATE(created_at) = '2023-11-03'
                AND device_id = ?
                AND event = ?
                GROUP BY HOUR(created_at)
            )
            AND device_id = ?
            AND event = ?
            ;", [$device->id, $entity, $device->id, $entity]);
            $data = collect($data);

            $item = collect();
            for($i=0; $i<24; $i++){
                $item->push(0);
            }
            foreach($data as $row){
                $item[intval(Carbon::parse($row->created_at)->setTimezone($group->timezone)->format("H"))] = $row->value;
            }


            return response()->json([
                "data" => $item,
            ]);
        }

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
