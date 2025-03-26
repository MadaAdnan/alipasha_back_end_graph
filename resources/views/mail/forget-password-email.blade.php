<x-mail::message>
# مرحبا

لقد قمت بطلب تغيير كلمة المرور , إذا لم تكن أنت تجاهل هذه الرسالة

    ##  الكود الخاص بك

    # {{$code}}


    <x-mail::button :url="route('change-password.ui',['code'=>$code])">
تغيير كلمة المرور
</x-mail::button>

شكراً لك,<br>
{{ config('app.name') }}
</x-mail::message>
