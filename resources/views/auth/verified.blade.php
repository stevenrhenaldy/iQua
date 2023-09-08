@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white">
                <div class="card-header">{{ __('Account verified') }}</div>

                <div class="card-body">
                    <div class="alert alert-success" role="alert">
                        {{ __('Your account has been verified.') }}
                    </div>

                    {{ __('Your account has been verified. Please reload your page to continue.') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
