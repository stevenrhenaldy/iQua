@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>{{ $group->name }}</h2>
                        <p>{{ $group->description }}</p>
                        <div class="row">
                            <div class="col-12 my-1">
                                <a href="{{ route('group.edit', $group->uuid) }}"
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

                                        <div class="my-2">
                                            <h5>
                                                <b>If</b>
                                            </h5>
                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="if_device">Device Name</label>
                                                    <input type="text" class="form-control" value="" readonly>


                                                </div>
                                                <div class="col-3">
                                                    <label for="if_meta">Meta</label>
                                                    <input name="if_meta" class="form-control" id="if_meta" readonly>
                                                </div>
                                                <div class="col-2">
                                                    <label for="if_condition">Condition</label>
                                                    <input name="if_condition" class="form-control" id="if_condition"
                                                    readonly>
                                                </div>
                                                <div class="col-3">
                                                    <label for="if_value">Value</label>
                                                    <input type="float" name="if_value" class="form-control"
                                                        id="if_value_text" readonly>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="my-2">
                                            <h5><b>Do</b></h5>

                                            <div class="row">
                                                <div class="col-4">
                                                    <label for="do_device">Device Name</label>
                                                    <select name="do_device" class="form-select" id="do_device">
                                                        <option value="">Select Device</option>

                                                    </select>

                                                </div>
                                                <div class="col-3">
                                                    <label for="do_meta">Meta</label>
                                                    <select name="do_meta" class="form-select" id="do_meta" disabled>

                                                    </select>
                                                </div>

                                                <div class="col-5">
                                                    <label for="do_value">Value</label>
                                                    <input type="float" name="do_value" class="form-control"
                                                        id="do_value_text" disabled>
                                                    <select name="do_value" class="form-select" id="do_value_select"
                                                        disabled hidden>

                                                    </select>
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
