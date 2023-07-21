<x-mail::message>
# Invitation to join {{$group->name}}

{{$mailData['initiator']}} has sent you an invitation to join {{$group->name}} on iQua.\
Please click the link below to accept the invitation.

<x-mail::button :url="$mailData['link']">
Accept Invitation
</x-mail::button>
Expires at {{$groupUser->active_until}}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
