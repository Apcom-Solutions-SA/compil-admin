@component('mail::message')

{{__('front.user_added_message')}}:
{{ $key }}

{{__('Please log in once via this url to validate your account.')}}
@component('mail::button', ['url' => $url])
{{__('Verify Email Address')}}
@endcomponent

{{__('front.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
