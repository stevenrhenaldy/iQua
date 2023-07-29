<?php

namespace App\Http\Controllers;

use App\Models\Devices;
use App\Models\Group;
use Illuminate\Http\Request;

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
    public function show(Group $group, Devices $device)
    {
        $device = $group->devices()->findOrFail($device->serial_number);
        dd($device);
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
