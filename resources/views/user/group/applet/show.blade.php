@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>{{ $applet->name }}</h2>
                        <div class="row">
                            <div class="col-12 my-1">
                                <a href="{{ route('group.applet.edit', [$group->uuid, $applet->id]) }}"
                                    class="btn text-center btn-primary float-end">Settings</a>
                            </div>

                            <div class="col-12 my-1">
                                <div class="card bg-white">
                                    <div class="card-header">
                                        Applet
                                    </div>
                                    <div class="card-body">
                                        <h1><b>{{ $applet->name }}</b></h1>
                                        @if ($applet->status)
                                            <span class="badge bg-success">enabled</span>
                                        @else
                                            <span class="badge bg-secondary">disabled</span>
                                        @endif

                                        <div class="my-3">
                                            <h4>
                                                <b>If</b>
                                            </h4>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="if_device">Device Name</label>
                                                    <input type="text" class="form-control" value="{{$if_applet->device->name}}" readonly>

                                                </div>
                                                <div class="col-3">
                                                    <label for="if_meta">Meta</label>
                                                    <input class="form-control" id="if_meta" value="{{$if_applet->entity->name}}" readonly>
                                                </div>
                                                <div class="col-2">
                                                    <label for="if_condition">Condition</label>
                                                    <input class="form-control" id="if_condition" value="{{$if_applet->condition}}"
                                                    readonly>
                                                </div>
                                                <div class="col-3">
                                                    <label for="if_value">Value</label>
                                                    <input type="float" class="form-control" value="{{$if_applet->value}}"
                                                        id="if_value_text" readonly>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="my-3">
                                            <h4><b>Do</b></h4>

                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="do_device">Device Name</label>
                                                    <input  class="form-control" id="do_device" value="{{$do_applet->device->name}}" readonly>
                                                </div>
                                                <div class="col-3">
                                                    <label for="do_meta">Meta</label>
                                                    <input class="form-control" id="do_meta" value="{{$do_applet->entity->name}}" readonly>
                                                </div>
                                                <div class="col-5">
                                                    <label for="do_value">Value</label>
                                                    <input class="form-control" id="do_value_select" value="{{$do_applet->value}}" readonly>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
@endsection
