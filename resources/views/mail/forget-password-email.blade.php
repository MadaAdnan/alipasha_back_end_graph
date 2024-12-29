<x-mail::message>
# مرحبا

لقد قمت بطلب تغيير كلمة المرور , إذا لم تكن أنت تجاهل هذه الرسالة

    ##  الكود الخاص بك

    # {{$code}}


   {{-- <x-mail::button :url="''">
Button Text
</x-mail::button>
--}}
شكراً لك,<br>
{{ config('app.name') }}
</x-mail::message>
