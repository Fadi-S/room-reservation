<x-mail::message>
# لقد تم ارسال طلب لتسجيل الدخول

للدخول اضغط علي الزر

<x-mail::button :url="$url">
تسجيل دخول
</x-mail::button>

{{ config('app.name') }}
</x-mail::message>
