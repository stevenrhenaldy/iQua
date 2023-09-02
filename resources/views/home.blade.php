@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card bg-white">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if( Auth::check() )

                        @if (is_null(Auth::user()->email_verified_at))
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">{{_("Verify Your Email Address!")}}</h4>
                                <p>{{_("Before proceeding, please check your email for a verification link.")}}</p>
                                <hr class="my-2">

                                <form class=" my-0 py-0" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success  m-0">{{ __('Resend') }}</button>.
                                </form>
                            </div>
                        @endif
                        @endif

                        {{-- {{ __('You are logged in!') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
