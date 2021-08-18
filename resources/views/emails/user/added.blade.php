@component('mail::message')

{{__('front.user_added_id')}}:
{{ $public_id }}

{{__('front.your_login')}}: 
{{ $email }}

{{__('front.user_added_password')}}:
{{ $password }}

{{__('front.please_login_once')}}
{{__('font.link_will_expire')}}
@component('mail::button', ['url' => $url])
{{__('front.verify_email')}}
@endcomponent


{{__('front.thanks')}},<br>
{{ config('app.name') }}
@endcomponent
