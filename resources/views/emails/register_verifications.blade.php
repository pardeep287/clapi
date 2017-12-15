@component('mail::message')
# Just one more step
##### Hey {{ ($user->name) ? $user->name . ',' : '' }}
You are just one step away from finally being part of ClubJB platform.
Use this code to complete the verification process.
# `{{ $verificationCode }}`

Thanks,<br>
{{ config('app.name') }}
@endcomponent