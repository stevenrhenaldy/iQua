@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2 class="my-0">{{ $device->name }}</h2>

                        <div class="card bg-white my-1">
                            <div class="card-header">
                                Device Info
                            </div>
                            <div class="card-body">
                                <h5 class="my-0"><b>{{ $device->name }}</b></h5>
                                <small class="my-0 text-muted">{{ $device->serial_number }}</small>
                                <p class="my-0">{{ __('Type: ') }}{{ $device->type->name }}</p>
                                {{-- <p class="my-0">{{ __('Status: ') }}{{ $device->status }}</p> --}}
                                {{-- <p class="my-0">{{__('Battery: ')}}{{ $device->status }}</p> --}}
                            </div>
                        </div>
                        <div class="card bg-white">
                            <div class="card-body">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th class="col-3" scope="row">Status</th>
                                            <td class="col-9" id="meta-status">{{$device->status}}</td>
                                        </tr>
                                        @foreach ($device->meta as $meta)
                                        @php
                                        $entity = $meta->entity;
                                        @endphp
                                        @if ($entity->type == "input")
                                        <tr>
                                            <th scope="row">{{$entity->name}}</th>
                                            <td>
                                                @if ($entity->data_type == "button")
                                                <button type="button" class="btn btn-primary meta-button" name="{{$entity->name}}" value="{{$entity->options[0]}}">{{$entity->options[0]}}</button>
                                                @endif
                                            </td>
                                        </tr>

                                        @elseif ($meta->entity->type == "output")
                                        <tr>
                                            <th scope="row">{{$entity->name}}</th>
                                            <td id="meta-{{$entity->name}}">{{$meta->value}}</td>
                                        </tr>
                                        @endif
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                        <div class="card bg-white my-1">
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

    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js" integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous"></script>
    <script type="module">
        $(function() {
            const group_uuid = "{{$group->uuid}}";
            const device_uuid = "{{$device->serial_number}}";

            var dt = $("#device_event_table").DataTable({
                processing: true,
                serverSide: false,
                ajax: window.location.href,
                order: [4, 'desc'],
                columns: [{
                        data: 'initiator',
                        name: 'initiator'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'event',
                        name: 'event'
                    },
                    {
                        data: 'value',
                        name: 'value'
                    },
                    {
                        data: 'time',
                        name: 'time'
                    },
                ]
            });

            const socket = io("https://realtime-iqua.atrest.xyz/");
            socket.on('connect', function (msg) {
                // console.log("test")

                socket.emit("set_room_id", group_uuid);
                console.log(`set_room_id = ${group_uuid}`)
            });

            socket.on('meta', (msg) => {
                if(msg.device != device_uuid) return;
                let ptr = `#meta-${msg.meta}`;
                console.log(msg, ptr);

                $(ptr).html(msg.value);
            });

            $('.meta-button').click(function() {
                let meta = $(this).attr("name");
                let value = $(this).attr("value");
                console.log(meta, value);
                socket.emit("action", {
                    "type": "action",
                    "device": device_uuid,
                    "group": group_uuid,
                    "event": meta,
                    "value": value
                });
            });

            // dt
            // .rows()
            // .invalidate()
            // .draw();
            console.log("invalidate")
            socket.on("event", (msg) => {
                let data = msg

                console.log(data.device)
                if(data.device == device_uuid){
                    console.log("Data received for this device");
                    let new_data = {
                            "initiator": data.initiator,
                            "type": data.type,
                            "event": data.event,
                            "value": data.value,
                            "time": data.time
                        };
                    dt.row.add(new_data).draw();
                }
            });
        });



    </script>
@endsection
