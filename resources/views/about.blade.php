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
                        <h4>Vision</h4>
                        <p>
                            We envision a world where the splendors of marine life are seamlessly integrated into the daily lives of individuals across the globe. Our vision is to be at the forefront of this aquatic renaissance, fostering a global community of aquarists who are not only well-informed and conscientious but who also experience the joy and wonder of the oceans within their homes.
                        </p>
                        <h4>
                            Mission
                        </h4>
                        <p>
                            Our mission is to spearhead the realm of aquarium innovation by developing and delivering cutting-edge technology solutions. We aim to empower and elevate aquarium enthusiasts' experiences, facilitating the creation and maintenance of vibrant underwater ecosystems, all while promoting responsible and informed aquarist practices.
                        </p>


                        {{-- {{ __('You are logged in!') }} --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
