@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>{{ $device->serial_number }}</h2>
                        <p class="my-0">{{ $device->type->name }}</p>
                        <p class="my-0">{{ $device->status }}</p>
                        <div class="card bg-white my-1">
                            <div class="card-header">
                                Group Info
                            </div>
                            <div class="card-body">
                                @if ($device->group_id)
                                <h5 class="my-0"><b>{{$device->group->name}}</b></h5>
                                <p class="my-0">{{$device->group->uuid}}</p>
                                <p class="my-0">Assigned By: {{$device->assigned_by}}</p>
                                @else
                                <p class="my-0">Not yet assigned to any group</p>
                                @endif
                            </div>
                        </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
