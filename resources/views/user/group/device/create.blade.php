@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>Register a device</h2>

                        <form action="{{route("group.device.store", $group->uuid)}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="productId" class="form-label">Product ID</label>
                                    <input type="text" class="form-control" id="productId" name="serial_number" value="">
                                </div>
                                <div class="">
                                    <a href="{{route("group.show", $group->uuid)}}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Register Device</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
