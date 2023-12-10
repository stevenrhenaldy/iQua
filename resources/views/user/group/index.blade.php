@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Group Management</h2>
                        <div class="row">
                            <div class="col-md-12 my-1">
                                <a href="{{route("group.create")}}" class="btn text-center btn-primary float-end">Create new group</a>
                            </div>
                            @foreach ($groups as $group)
                            <a href="{{route("group.show", $group->uuid)}}" class="text-black text-decoration-none">

                                <div class="col-12 my-1">
                                    <div class="card bg-white">
                                        <div class="card-body">
                                            <h4 class="text-primary"><b>{{$group->name}}</b></h4>
                                            <div class="row">
                                                <div class="col-6">
                                                    Members: {{$group->users()->count()}}
                                                </div>
                                                <div class="col-6">
                                                    Devices: {{$group->devices()->count()}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </a>
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
