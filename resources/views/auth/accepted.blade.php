@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card bg-white">
                <div class="card-header">{{ __('Invitation accepted') }}</div>

                <div class="card-body">
                    @if(!$already_accepted)
                    <div class="alert alert-success" role="alert">
                        {{$user->username}} {{ __(' has been linked with') }} {{$group->name}}.
                    </div>
                    @endif
                    {{ __('Your account')}} <b>{{$user->username}}</b> {{ _('has been linked with') }} <b>{{$group->name}}</b>.

                    @auth
                        @if(Auth::user()->id == $user->id)
                            <br><br>
                            <a href="{{ route('group.show', $group->uuid) }}" class="btn btn-primary">{{ __('Go to ') }}{{$group->name}}</a>
                        @endif
                    @endauth

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
