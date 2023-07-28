@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>Create new group</h2>

                        <form action="{{route("admin.device.store")}}" method="post">
                            @csrf
                            <div class="row">
                                <div class="mb-3">
                                    <label for="inputType" class="form-label">Type</label>
                                    <input type="text" class="form-control" id="inputType" name="type" value="">
                                    @error('type')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="">
                                    <a href="{{route("admin.device.index")}}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Create</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
