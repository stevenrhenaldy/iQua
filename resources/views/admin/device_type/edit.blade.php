@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Device Type</h2>
                        <x-alert></x-alert>
                        <form action="{{ route('admin.device_type.update', $device_type->id) }}" method="post">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="mb-3">
                                    <label for="GroupName" class="form-label">Type</label>
                                    <input type="text" class="form-control" id="GroupName" name="name"
                                        value="{{ $device_type->name }}">
                                </div>

                                <div class="mb-3">
                                    <label for="GroupName" class="form-label">Meta</label>
                                    <div class="meta-table">
                                        @if (0 == count($device_type->entities))
                                        <div class="row">
                                            <div class="col-2">
                                                <select name="entity[0][type]" class="form-select mb-2" id="">
                                                    <option value="input">Input</option>
                                                    <option value="output">Output</option>
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <input type="text" class="form-control mb-2" name="entity[0][name]"
                                                    placeholder="name">
                                            </div>
                                            <div class="col-2">
                                                <select name="entity[0][data_type]" class="form-select mb-3" id="">
                                                    @foreach (\App\Models\Entity::$DataType as $datatype)
                                                        <option value="{{ $datatype }}">{{ $datatype }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control mb-2"
                                                    name="entity[0][default_value]" placeholder="Default Value">
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control mb-2" name="entity[0][options]"
                                                    placeholder="Options (JSON ARRAY)">
                                            </div>
                                        </div>
                                        @endif
                                        @foreach ($device_type->entities as $key => $entity)
                                        <div class="row">
                                            <div class="col-2">
                                                <select name="entity[{{$key}}][type]" class="form-select mb-2" id="">
                                                    <option value="input" @if($entity->type == "input") selected @endif>Input</option>
                                                    <option value="output" @if($entity->type == "output") selected @endif>Output</option>
                                                </select>
                                            </div>
                                            <div class="col-2">
                                                <input type="text" class="form-control mb-2" name="entity[{{$key}}][name]"
                                                    placeholder="name" value="{{$entity->name}}">
                                            </div>
                                            <div class="col-2">
                                                <select name="entity[{{$key}}][data_type]" class="form-select mb-3" id="">
                                                    @foreach (\App\Models\Entity::$DataType as $datatype)
                                                        <option value="{{ $datatype }}" @if ($entity->data_type == $datatype) selected @endif>{{ $datatype }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control mb-2"
                                                    name="entity[{{$key}}][default_value]" placeholder="Default Value" value="{{ $entity->default_value}}">
                                            </div>
                                            <div class="col-3">
                                                <input type="text" class="form-control mb-2" name="entity[{{$key}}][options]"
                                                    placeholder="Options (JSON ARRAY)" value="{{ json_encode($entity->options) }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>


                                </div>
                                <center>
                                    <button class="btn btn-success" type="button" id="add-meta">
                                        Add
                                    </button>
                                </center>
                            </div>


                            <div class="">
                                <a href="{{ route('admin.device_type.index', $device_type->uuid) }}"
                                    class="btn btn-secondary">Back</a>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                    </div>
                    </form>

                </div>
            </div>
        </div>
        <div class="col-12">

        </div>
    </div>
    </div>
    <script type="module">
        let count = {{$device_type->entities->count()}};
        $("#add-meta").click(function() {

            $(".meta-table").append(`
            <div class="row">

            <div class="col-2">
                <select name="entity[${count}][type]" class="form-select mb-2" id="">
                    <option value="input">Input</option>
                    <option value="output">Output</option>
                </select>
            </div>
            <div class="col-2">
                <input type="text" class="form-control mb-2" name="entity[${count}][name]"
                    placeholder="name">
            </div>
            <div class="col-2">
                <select name="entity[${count}][data_type]" class="form-select mb-3" id="">
                    @foreach (\App\Models\Entity::$DataType as $datatype)
                        <option value="{{ $datatype }}">{{ $datatype }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-3">
                <input type="text" class="form-control mb-2"
                    name="entity[${count}][default_value]" placeholder="Default Value">
            </div>
            <div class="col-3">
                <input type="text" class="form-control mb-2" name="entity[${count}][options]"
                    placeholder="Options (JSON ARRAY)">
            </div>
            </div>
            `);
            count++;
        });
    </script>
@endsection
