@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        <h2>{{__('User Profile')}}</h2>
                        <x-alert></x-alert>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card bg-white mb-3">
                                    <div class="card-header">
                                        {{__('Profile')}}
                                    </div>
                                    <form action="{{route("profile.profile")}}" method="post">
                                        @csrf
                                        <div class="card-body">
                                            <div class="row mb-1">
                                                <div class="col-3">
                                                    <span class="align-middle">{{__('Name')}}</span>
                                                </div>
                                                <div class="col-9">
                                                    <input type="text" class="form-control" placeholder="" name="name" value="{{$user->name}}">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-3 ">
                                                    <span class="align-middle">{{__('Email')}}</span>
                                                </div>
                                                <div class="col-9">
                                                    <input type="email" class="form-control" placeholder="" name="email" value="{{$user->email}}">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div>
                                                    <button type="submit" class="btn btn-primary">{{__('Update Profile')}}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="card bg-white mb-3">
                                    <div class="card-header">
                                        {{__('Change Password')}}
                                    </div>
                                    <div class="card-body">
                                        <form action="{{route("profile.password")}}" method="post">
                                            @csrf
                                            <div class="row mb-1">
                                                <div class="col-3">
                                                    <span class="align-middle">{{__('Current Password')}}</span>
                                                </div>
                                                <div class="col-9">
                                                    <input type="password" class="form-control" placeholder="" name="current_password" value="">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-3 ">
                                                    <span class="align-middle">{{__('New Password')}}</span>
                                                </div>
                                                <div class="col-9">
                                                    <input type="password" class="form-control" placeholder="" name="new_password" value="">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div class="col-3 ">
                                                    <span class="align-middle">{{__('Confirm New Password')}}</span>
                                                </div>
                                                <div class="col-9">
                                                    <input type="password" class="form-control" placeholder="" name="new_password_confirmation" value="">
                                                </div>
                                            </div>
                                            <div class="row mb-1">
                                                <div>
                                                    <button type="submit" class="btn btn-primary">{{__('Update Password')}}</button>
                                                </div>
                                            </div>
                                        </form>
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
