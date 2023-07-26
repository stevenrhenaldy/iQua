@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <h2>{{ $group->name }}</h2>
                        <p>{{ $group->description }}</p>
                        <div class="row">
                            <div class="col-12 my-1">
                                <a href="{{ route('group.edit', $group->uuid) }}"
                                    class="btn text-center btn-primary float-end">Settings</a>
                            </div>
                            <div class="col-12 my-1">
                                <div class="card">
                                    <div class="card-header">
                                        Devices
                                    </div>
                                    <div class="card-body">
                                        @foreach ($devices as $device)
                                            <a href="{{ route('group.show', $device->uuid) }}"
                                                class="text-black text-decoration-none">

                                                <div class="col-12 my-1">
                                                    <div class="card">
                                                        <div class="card-body">
                                                            <h4>{{ $group->name }}</h4>
                                                            <div class="row">
                                                                <div class="col-6">
                                                                    Status: {{ $group->users()->count() }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection