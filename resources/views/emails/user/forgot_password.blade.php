@component('mail::message')

{{__('front.your_password')}}:
{{ $password }}

{{__('front.thanks')}},<br>
{{ config('app.name') }}
@endcomponent