<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Devices;
use Illuminate\Http\Request;
use DataTables;

class DeviceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $devices = Devices::query();
            // $data = User::select('*');
            return DataTables::of($devices)
                    ->addIndexColumn()
                    ->addColumn('is_assigned', function($row){
                        return $row->assigned_at ? "Yes" : "No";
                 })
                    ->addColumn('action', function($row){
                            $route = route("admin.device.show", $row->serial_number);
                            $btn = sprintf("<a href='%s' class='edit btn btn-primary btn-sm'>View</a>", $route);
                            // $btn = `<a href="{$route}" class="edit btn btn-primary btn-sm">View</a>`;
                            // dd($btn)

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view("admin.devices.index", [
            // "devices" => $devices,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.devices.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "type" => ['required', "string"]
        ]);
        $device = Devices::create([
            "type" => $request->type,
        ]);
        return redirect()->route("admin.device.show", $device->serial_number);
    }

    /**
     * Display the specified resource.
     */
    public function show(Devices $device)
    {
        return view("admin.devices.show",[
            "device" => $device,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Devices $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Devices $devices)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Devices $devices)
    {
        //
    }
}
