@component('mail::message')
# Hey

Confirm your email!

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
    Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
