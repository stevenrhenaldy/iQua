@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if (is_null(Auth::user()->email_verified_at))
                            <div class="alert alert-warning" role="alert">
                                <h4 class="alert-heading">Verify Your Email Address!</h4>
                                <p>Before proceeding, please check your email for a verification link.</p>
                                <hr class="my-2">

                                <form class=" my-0 py-0" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-success  m-0">{{ __('Resend') }}</button>.
                                </form>
                            </div>
                        @endif

                        {{-- {{ __('You are logged in!') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
