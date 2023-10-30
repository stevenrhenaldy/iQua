@extends('layouts.app')
@push('styles')
    <!-- CSS  -->
    <link href="https://vjs.zencdn.net/7.2.3/video-js.css" rel="stylesheet">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
@endpush

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
                            <video controls id="livestream" class="video-js">
                                <source src="{{ asset('assets/video/test.mp4') }}">
                                Your browser does not support the video tag.
                            </video>

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
                                                                        <span
                                                                            id="meta-{{ $device->serial_number }}-{{ $meta->entity->name }}">{{ $metaOption }}</span>
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
                                        @endforeach
                                    </div>

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

    <script src="https://cdn.socket.io/4.6.0/socket.io.min.js"
        integrity="sha384-c79GN5VsunZvi+Q/WObgk2in0CbZsHnjEqvFxC5DxHn9lTfNce2WW6h2pH6u/kF+" crossorigin="anonymous">
    </script>
    {{-- <link href="https://unpkg.com/video.js/dist/video-js.css" rel="stylesheet"> --}}
    {{-- <script src="https://vjs.zencdn.net/8.6.1/video.min.js"></script> --}}
    {{-- <script src="https://unpkg.com/video.js/dist/video.js"></script>
    <script src="https://unpkg.com/videojs-contrib-hls/dist/videojs-contrib-hls.js"></script> --}}
    <script src="https://vjs.zencdn.net/ie8/ie8-version/videojs-ie8.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/videojs-contrib-hls/5.14.1/videojs-contrib-hls.js"></script>
    <script src="https://vjs.zencdn.net/7.2.3/video.js"></script>

    <script>
        var player = new videojs('livestream');
        player.play();
    </script>
    <script type="module">
        const group_uuid = "{{ $group->uuid }}";
        const socket = io("https://realtime-iqua.atrest.xyz/");
        socket.on('connect', function(msg) {
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
