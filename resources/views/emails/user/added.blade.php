@component('mail::message')

{{__('front.user_added_id')}}:
{{ $public_id }}

{{__('front.your_login')}}: 
{{ $email }}

{{__('front.user_added_password')}}:
{{ $password }}

{{__('Please log in once via this url to validate your account.')}}
{{__('This link will expire in 24 hours.')}}
@component('mail::button', ['url' => $url])
{{__('Verify Email Address')}}
@endcomponent


{{__('front.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
