@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2 class="my-0">{{ $device->name }}</h2>

                        <div class="card my-1">
                            <div class="card-header">
                                Device Info
                            </div>
                            <div class="card-body">
                                <h5 class="my-0"><b>{{$device->name}}</b></h5>
                                <small class="my-0 text-muted">{{$device->serial_number}}</small>
                                <p class="my-0">{{__('Type: ')}}{{ $device->type }}</p>
                                <p class="my-0">{{__('Status: ')}}{{ $device->status }}</p>
                                {{-- <p class="my-0">{{__('Battery: ')}}{{ $device->status }}</p> --}}
                            </div>
                        </div>
                        <div class="card my-1">
                            <div class="card-header">
                                Device Events
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">

                                        <table id="device_event_table" class="table table-striped" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Initiator</th>
                                                    <th>Type</th>
                                                    <th>Event</th>
                                                    <th>Value</th>
                                                    <th>Time</th>
                                                </tr>

                                            </thead>

                                        </table>
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

    <script type="module">

        $(function () {
            $("#device_event_table").dataTable({
                processing: true,
                serverSide: true,
                ajax: window.location.href,
                columns: [
                    {data: 'initiator', name: 'initiator'},
                    {data: 'type', name: 'type'},
                    {data: 'event', name: 'event'},
                    {data: 'value', name: 'value'},
                    {data: 'created_at', name: 'created_at'},
                ]
            });

        });
    </script>
@endsection
