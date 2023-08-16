<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DeviceTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $deviceType = DeviceType::query();
            // $data = User::select('*');
            return DataTables::of($deviceType)
                    ->addIndexColumn()
                    ->addColumn('is_assigned', function($row){
                        return $row->assigned_at ? "Yes" : "No";
                 })
                    ->addColumn('action', function($row){
                            $route = route("admin.device_type.show", $row->id);
                            $btn = sprintf("<a href='%s' class='edit btn btn-primary btn-sm'>View</a>", $route);
                            // $btn = `<a href="{$route}" class="edit btn btn-primary btn-sm">View</a>`;
                            // dd($btn)

                            return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view("admin.device_type.index");
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.device_type.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            "type" => ['required', "string"]
        ]);
        $deviceType = DeviceType::create([
            "name" => $validated["type"],
        ]);
        return redirect()->route("admin.device_type.edit", $deviceType->id)->with("success", __("Device Type Created Successfully"));
    }

    /**
     * Display the specified resource.
     */
    public function show(DeviceType $deviceType)
    {
        return redirect()->route("admin.device_type.edit", $deviceType->id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DeviceType $deviceType)
    {
        return view("admin.device_type.edit",[
            "device_type" => $deviceType,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DeviceType $deviceType)
    {
        $validated = $request->validate([
            "name" => ['required', "string"],
            "meta" => ["array"],
            "meta.*" => ["required", "string"]
        ]);
        $deviceType->update($validated);
        return redirect()->route("admin.device_type.edit", $deviceType->id)->with("success", __("Device type Has been updated"));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeviceType $deviceType)
    {
        //
    }
}
