<x-mail::message>
# أهلا بك {{$user->name}}

يرجى تأكيد بريدك الإلكتروني للإستفادة من كامل خدمات تطبيق علي باشا

{{--<x-mail::button :url="''">
Button Text
</x-mail::button>--}}

شكرا لك,<br>
{{ config('app.name') }}
</x-mail::message>
