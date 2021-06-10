@component('mail::message')

{{__('front.user_added_id')}}:
{{ $public_id }}

{{__('front.user_added_password')}}:
{{ $password }}

{{__('Please log in once via this url to validate your account.')}}
@component('mail::button', ['url' => $url])
{{__('Verify Email Address')}}
@endcomponent
{{__('The link is valide within 24 hours.')}}

{{__('front.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
