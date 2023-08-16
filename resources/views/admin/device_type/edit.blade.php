@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Device Type</h2>

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

                                        @foreach($device_type->meta as $meta)
                                        <input type="text" class="form-control mb-2" value="{{$meta}}" name="meta[]">
                                        @endforeach
                                        @if (!$device_type->meta)
                                        <input type="text" class="form-control mb-2" name="meta[]">
                                        @endif
                                    </div>
                                    <center>
                                        <button class="btn btn-success" type="button" id="add-meta">
                                            Add
                                        </button>
                                    </center>
                                </div>


                                <div class="">
                                    <a href="{{route("admin.device_type.index", $device_type->uuid)}}"
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
        $("#add-meta").click(function () {
            $(".meta-table").append(`<input type="text" class="form-control mb-2" name="meta[]">`)
        });
    </script>
@endsection
