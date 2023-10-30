@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Create new applet</h2>
                        <x-alert></x-alert>
                        <form action="{{ route('group.applet.store', $group->uuid) }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="inputTitleEn" class="form-label">Applet Name</label>
                                    <input type="text" class="form-control" id="GroupName" name="name"
                                        value="{{ $applet->name }}">
                                </div>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select name="status" class="form-select" id="status">
                                        <option value="1" {{ $applet->status ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ !$applet->status ? 'selected' : '' }}>Disabled</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <h5><b>If Condition</b></h5>

                                    <div class="row">
                                        <div class="col-4">
                                            <label for="if_device">Device Name</label>
                                            <select name="if_device" class="form-select" id="if_device">
                                                <option value="">Select Device</option>
                                                @foreach ($devices as $device)
                                                    <option value="{{ $device->serial_number }}"
                                                        {{ $if_applet->device_id == $device->id ? 'selected' : '' }}>
                                                        {{ $device->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-3">
                                            <label for="if_meta">Meta</label>
                                            <select name="if_meta" class="form-select" id="if_meta">
                                                @foreach ($if_applet->device->type->entities->where("type", "output") as $entity)
                                                    <option value="{{ $entity->id }}"
                                                        {{ $if_applet->entity_id == $entity->id ? 'selected' : '' }}>
                                                        {{ $entity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-2">
                                            <label for="if_condition">Condition</label>
                                            <select name="if_condition" class="form-select" id="if_condition">
                                                @foreach (['==', '!=', '>', '<', '>=', '<='] as $condition)
                                                    <option value="{{ $condition }}"
                                                        {{ $if_applet->condition == $condition ? 'selected' : '' }}>
                                                        {{ $condition }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-3">
                                            <label for="if_value">Value</label>
                                            @php
                                                $options = $if_applet->entity->options;
                                            @endphp
                                            @if (!is_null($options))
                                                <input type="float" name="if_value" class="form-control"
                                                    id="if_value_text" hidden>
                                                <select name="if_value" class="form-select" id="if_value_select">
                                                    @foreach ($options as $option)
                                                        <option
                                                            value="{{ $option }} {{ $option == $if_applet->value ? 'selected' : '' }}">
                                                            {{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="float" name="if_value" class="form-control"
                                                    id="if_value_text">
                                                <select name="if_value" class="form-select" id="if_value_select" hidden>

                                                </select>
                                            @endif
                                        </div>

                                    </div>
                                </div>
                                <div class="mb-3">
                                    <h5><b>Do Action</b></h5>

                                    <div class="row">
                                        <div class="col-4">
                                            <label for="do_device">Device Name</label>
                                            <select name="do_device" class="form-select" id="do_device">
                                                <option value="">Select Device</option>
                                                @foreach ($devices as $device)
                                                    <option value="{{ $device->serial_number }}"
                                                        {{ $do_applet->device_id == $device->id ? 'selected' : '' }}
                                                        >{{ $device->name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>
                                        <div class="col-3">
                                            <label for="do_meta">Meta</label>
                                            <select name="do_meta" class="form-select" id="do_meta">
                                                @foreach ($if_applet->device->type->entities->where("type", "input") as $entity)
                                                    <option value="{{ $entity->id }}"
                                                        {{ $do_applet->entity_id == $entity->id ? 'selected' : '' }}
                                                        >{{ $entity->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-5">
                                            <label for="do_value">Value</label>
                                            @php
                                                $options = $do_applet->entity->options;
                                            @endphp
                                            @if (!is_null($options))
                                                <input type="float" name="do_value" class="form-control"
                                                    id="do_value_text" hidden>
                                                <select name="do_value" class="form-select" id="do_value_select">
                                                    @foreach ($options as $option)
                                                        <option
                                                            value="{{$option}}"
                                                            {{ $option == $do_applet->value ? 'selected' : '' }}
                                                            >{{ $option }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="float" name="do_value" class="form-control"
                                                    id="do_value_text">
                                                <select name="do_value" class="form-select" id="do_value_select" hidden>

                                                </select>
                                            @endif
                                        </div>

                                    </div>
                                </div>

                                <div class="">
                                    <a href="{{ route('group.applet.show', [$group->uuid, $applet->id]) }}"
                                        class="btn btn-secondary">Back</a>
                                    <button type="button" class="btn btn-danger">Delete</button>
                                    <button type="submit" class="btn btn-primary">Update</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="module">
        let metaDataIf = [];
        let metaDataDo = [];

        const group_uuid = "{{ $group->uuid }}";
        let if_device_uuid = $("#if_device").val();
        let do_device_uuid = $("#do_device").val();

        $.ajax({
            url: "/group/" + group_uuid + "/applet/create",
            data: {
                "_token": "{{ csrf_token() }}",
                "device": if_device_uuid
            },
            type: "GET",
            success: function(d) {
                const data = d.data;
                metaDataIf = data;
            },
            error: function(data) {
                console.log(data);
            }
        });

        $.ajax({
            url: "/group/" + group_uuid + "/applet/create",
            data: {
                "_token": "{{ csrf_token() }}",
                "device": do_device_uuid
            },
            type: "GET",
            success: function(d) {
                const data = d.data;
                metaDataDo = data;
            },
            error: function(data) {
                console.log(data);
            }
        });

        $("#if_device").change(function() {
            let device_uuid = $(this).val();
            $("#if_meta").empty();
            $("#if_condition").empty();
            $("#if_value_select").empty();
            $("#if_value_text").empty();

            $("#if_meta").prop("disabled", true);
            $("#if_condition").prop("disabled", true);
            $("#if_value_select").prop("disabled", true);
            $("#if_value_text").prop("disabled", true);

            if (device_uuid == "") return;
            // console.log(device_uuid);
            $.ajax({
                url: "/group/" + group_uuid + "/applet/create",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "device": device_uuid
                },
                type: "GET",
                success: function(d) {
                    const data = d.data;
                    // console.log(data);
                    metaDataIf = data;
                    $("#if_meta").empty();
                    $("#if_meta").append('<option value="">Select Meta</option>');
                    $.each(data, function(key, value) {
                        if (value.type == "input") return;
                        $("#if_meta").append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                    $("#if_meta").prop("disabled", false);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $("#if_meta").change(function() {
            const meta_id = $(this).val();
            $("#if_condition").empty();
            $("#if_value_select").empty();
            $("#if_value_text").val("");
            $("#if_value_select").prop("disabled", true);
            $("#if_value_text").prop("disabled", true);
            if (meta_id == "") return;
            const meta = metaDataIf.find(o => o.id == meta_id);

            let conditions = [];
            if (meta.data_type == "integer" || meta.data_type == "float") {
                conditions = (["==", "!=", ">", "<", ">=", "<="]);
                $("#if_value_select").prop("hidden", true);
                $("#if_value_text").prop("hidden", false);
            } else {
                conditions = (["==", "!="]);
                // console.log(meta.options);
                meta.options.forEach((value, key) => {
                    $("#if_value_select").append('<option value="' + value + '">' + value + '</option>');
                });
                $("#if_value_select").prop("hidden", false);
                $("#if_value_text").prop("hidden", true);

            }
            // console.log(conditions)
            conditions.forEach((value, key) => {
                $("#if_condition").append('<option value="' + value + '">' + value + '</option>');
            });

            $("#if_condition").prop("disabled", false);
            $("#if_value_select").prop("disabled", false);
            $("#if_value_text").prop("disabled", false);
        });


        $("#do_device").change(function() {
            const group_uuid = "{{ $group->uuid }}";
            let device_uuid = $(this).val();
            $("#do_meta").empty();
            $("#do_condition").empty();
            $("#do_value_select").empty();
            $("#do_value_text").empty();

            $("#do_meta").prop("disabled", true);
            $("#do_condition").prop("disabled", true);
            $("#do_value_select").prop("disabled", true);
            $("#do_value_text").prop("disabled", true);

            if (device_uuid == "") return;
            // console.log(device_uuid);
            $.ajax({
                url: "/group/" + group_uuid + "/applet/create",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "device": device_uuid
                },
                type: "GET",
                success: function(d) {
                    const data = d.data;
                    // console.log(data);
                    metaDataDo = data;
                    $("#do_meta").empty();
                    $("#do_meta").append('<option value="">Select Meta</option>');
                    $.each(data, function(key, value) {
                        if (value.type == "output") return;
                        $("#do_meta").append('<option value="' + value.id + '">' + value.name +
                            '</option>');
                    });
                    $("#do_meta").prop("disabled", false);
                },
                error: function(data) {
                    console.log(data);
                }
            });
        });

        $("#do_meta").change(function() {
            const meta_id = $(this).val();
            $("#do_condition").empty();
            $("#do_value_select").empty();
            $("#do_value_text").val("");
            $("#do_value_select").prop("disabled", true);
            $("#do_value_text").prop("disabled", true);
            if (meta_id == "") return;
            const meta = metaDataDo.find(o => o.id == meta_id);

            let conditions = [];
            if (meta.data_type == "integer" || meta.data_type == "float") {
                conditions = (["==", "!=", ">", "<", ">=", "<="]);
                $("#do_value_select").prop("hidden", true);
                $("#do_value_text").prop("hidden", false);
            } else {
                conditions = (["==", "!="]);
                // console.log(meta.options);
                meta.options.forEach((value, key) => {
                    $("#do_value_select").append('<option value="' + value + '">' + value + '</option>');
                });
                $("#do_value_select").prop("hidden", false);
                $("#do_value_text").prop("hidden", true);

            }
            // console.log(conditions)
            conditions.forEach((value, key) => {
                $("#do_condition").append('<option value="' + value + '">' + value + '</option>');
            });

            $("#do_condition").prop("disabled", false);
            $("#do_value_select").prop("disabled", false);
            $("#do_value_text").prop("disabled", false);
        });
    </script>
@endsection
