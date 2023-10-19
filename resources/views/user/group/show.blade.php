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
                                        Devices
                                    </div>
                                    <div class="card-body">
                                        @foreach ($devices as $device)
                                            <div class="col-12 my-1">
                                                <div class="card bg-white">
                                                    <div class="card-body">
                                                        <h4>{{ $device->name }}</h4>
                                                        {{-- <div class="row"> --}}
                                                        {{-- <div class="col-6"> --}}

                                                        @foreach ($device->meta as $meta)
                                                            <div class="row">
                                                                <div class="col-4">
                                                                    {{ $meta->entity->name }}
                                                                </div>
                                                                <div class="col-8">

                                                                    @if ($meta->entity->type == 'output')
                                                                    @php
                                                                    $entity = $meta->entity;
                                                                    // dd($entity);
                                                                    if(is_null($entity)){
                                                                        $metaOption = $meta->value;
                                                                    }
                                                                    $options = $entity->options;
                                                                    if(is_null($options)){
                                                                        $metaOption = $meta->value;
                                                                    }
                                                                    if($options && $entity){
                                                                        $options = $entity->options;
                                                                        $metaOption =  $options[$meta->value];
                                                                    }
                                                                    @endphp
                                                                    <span id="meta-{{$device->serial_number}}-{{$meta->entity->name}}">{{ $metaOption }}</span>

                                                                    @else
                                                                        @if ($meta->entity->data_type == 'button')
                                                                            <button type="button"
                                                                                class="btn btn-primary meta-button btn-sm"
                                                                                name="{{ $device->serial_number }}_{{ $meta->entity->name }}"
                                                                                value="0">{{ $meta->entity->options[0] }}</button>

                                                                        @elseif ($meta->entity->data_type == 'switch')

                                                                        @endif
                                                                    @endif
                                                                </div>

                                                            </div>
                                                        @endforeach
                                                        {{-- </div> --}}


                                                        {{-- </div> --}}
                                                        <div class="mt-2">
                                                            <a href="{{ route('group.device.show', [$group->uuid, $device->serial_number]) }}"
                                                                class="d-flex btn btn-primary">
                                                                Check out more...
                                                            </a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    </div>
                                    @endforeach

                                    <div class="mt-1 d-grid">
                                        <a href="{{ route('group.device.create', $group->uuid) }}" class="btn btn-success">
                                            {{ __('Add Device') }}
                                        </a>
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
        const group_uuid = "{{$group->uuid}}";
        const socket = io("https://realtime-iqua.atrest.xyz/");
        socket.on('connect', function (msg) {
            socket.emit("set_room_id", group_uuid);
            console.log(`set_room_id = ${group_uuid}`)
        });

        socket.on('meta', (msg) => {
            // if(msg.device != device_uuid) return;
            let ptr = `#meta-${msg.device}-${msg.meta}`;
            console.log(msg, ptr);

            $(ptr).html(msg.value);
        });

        $('.meta-button').click(function() {
            let serial_meta = $(this).attr("name");
            let serial = serial_meta.split('_')[0];
            let meta = serial_meta.split('_')[1];
            let value = $(this).attr("value");
            console.log(meta, value);
            socket.emit("action", {
                "type": "action",
                "device": serial,
                "group": group_uuid,
                "event": meta,
                "value": value
            });
        });
    </script>
@endsection
