@extends('layouts.app')

@push('styles')
    <style>
        .custom-switch {
            position: relative;
            display: inline-block;
            width: 60px;
            height: 34px;
        }

        .custom-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            -webkit-transition: .4s;
            transition: .4s;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 26px;
            width: 26px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            -webkit-transition: .4s;
            transition: .4s;
        }

        input:checked+.slider {
            background-color: #2196F3;
        }

        input:focus+.slider {
            box-shadow: 0 0 1px #2196F3;
        }

        input:checked+.slider:before {
            -webkit-transform: translateX(26px);
            -ms-transform: translateX(26px);
            transform: translateX(26px);
        }

        .slider.round {
            border-radius: 34px;
        }

        .slider.round:before {
            border-radius: 50%;
        }
    </style>
@endpush

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
                            </div>
                        </div>
                        <div class="card bg-white">
                            <div class="card-body">
                                <div>
                                    <canvas id="myChart"></canvas>
                                  </div>
                                <table class="table">
                                    <tbody>
                                        {{-- <tr>
                                            <th class="col-3" scope="row">Status</th>
                                            <td class="col-9" id="meta-status">{{$device->status}}</td>
                                        </tr> --}}
                                        @foreach ($device->meta as $meta)
                                            @php
                                                $entity = $meta->entity;
                                            @endphp
                                            @if ($entity->type == 'input')
                                                <tr>
                                                    <th scope="row">{{ $entity->name }}</th>
                                                    @if ($entity->data_type == 'switch')
                                                        <td>
                                                            <label class="custom-switch">
                                                                {{-- <input type="text" value="0"> --}}
                                                                <input type="checkbox" class="meta-switch"
                                                                    name="{{ $entity->name }}" value="{{ $meta->value }}">
                                                                <span class="slider round"></span>
                                                            </label>
                                                        </td>
                                                    @elseif ($entity->data_type == 'button')
                                                        <td>
                                                            @if ($entity->data_type == 'button')
                                                                <button type="button" class="btn btn-primary meta-button"
                                                                    name="{{ $entity->name }}"
                                                                    value="0">{{ $entity->options[0] }}</button>
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @elseif ($meta->entity->type == 'output')
                                                <tr>
                                                    <th scope="row">{{ $entity->name }}</th>
                                                    @php
                                                        $entity = $meta->entity;
                                                        // dd($entity);
                                                        if (is_null($entity)) {
                                                            $metaOption = $meta->value;
                                                        }
                                                        $options = $entity->options;
                                                        if (is_null($options)) {
                                                            $metaOption = $meta->value;
                                                        }
                                                        if ($options && $entity) {
                                                            $options = $entity->options;
                                                            $metaOption = $options[$meta->value];
                                                        }
                                                    @endphp
                                                    <td id="meta-{{ $entity->name }}">{{ $metaOption }}</td>
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

    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
    <script type="module">
        $(function() {
            const group_uuid = "{{ $group->uuid }}";
            const device_uuid = "{{ $device->serial_number }}";

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




            $.ajax({
                dataType: "json",
                url: "{{route('group.device.chart', [$group->uuid, $device->serial_number])}}",
                data: {
                    csrf : "{{csrf_token()}}",
                    interval: "daily",
                    entity: "lux"
                },
                success: function(data){
                    console.log(data);
                    const ctx = document.getElementById('myChart');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Array.from(Array(24), (_, i) => i),
                    datasets: [{
                        label: 'Lux',
                        data: data.data,
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
                }
            })



            const socket = io("https://realtime-iqua.atrest.xyz/");
            socket.on('connect', function(msg) {
                // console.log("test")

                socket.emit("set_room_id", group_uuid);
                console.log(`set_room_id = ${group_uuid}`)
            });

            socket.on('meta', (msg) => {
                if (msg.device != device_uuid) return;
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

            $('.meta-switch').change(function() {
                let meta = $(this).attr("name");
                // let value = $(this).attr("value");
                let value = $(this).is(":checked") ? 1 : 0;
                console.log(meta, value);
                socket.emit("action", {
                    "type": "action",
                    "device": device_uuid,
                    "group": group_uuid,
                    "event": meta,
                    "value": value
                });
            });


            console.log("invalidate")
            socket.on("event", (msg) => {
                let data = msg

                console.log(data.device)
                if (data.device == device_uuid) {
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
