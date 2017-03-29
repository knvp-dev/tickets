@component('mail::message')
# You have been invited!

{{ $invitation->team->owner->name }} invited you to join his team: {{ $invitation->team->title }}.

@component('mail::button', ['url' => 'http://v54.app/login'])
Accept it now!
@endcomponent

@component('mail::panel')
	If you do not have an account you can sign up with the email address that received this email and accept the invitation.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
