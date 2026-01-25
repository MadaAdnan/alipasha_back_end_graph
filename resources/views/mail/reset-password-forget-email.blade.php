<x-mail::message>
    # أهلا بك مجدداً

    تم تغيير كلمة المرور
    # الكلمة الجديدة هي
    {{$password}}
    {{--<x-mail::button :url="''">
        Button Text
    </x-mail::button>--}}

    شكراً لك,<br>
    {{ config('app.name') }}
</x-mail::message>
