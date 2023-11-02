@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Applets</h2>
                        <p>Applet helps you to automate your jobs!</p>
                        <div class="row">
                            <div class="col-md-12 my-1">
                                <a href="{{route("group.show", $group->uuid)}}" class="btn text-center btn-secondary">Back</a>

                                <a href="{{route("group.applet.create", $group->uuid)}}" class="btn text-center btn-primary float-end">Create new applet</a>
                            </div>
                            @foreach ($applets as $applet)
                            <a href="{{route("group.applet.show", [$group->uuid, $applet->id])}}" class="text-black text-decoration-none">

                                <div class="col-12 my-1">
                                    <div class="card bg-white">
                                        <div class="card-body">
                                            <h4>{{$applet->name}}</h4>
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
