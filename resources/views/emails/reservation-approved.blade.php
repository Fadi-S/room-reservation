<x-mail::message>
    # تم الموافقة علي الحجز

    يوم  {{ $dayOfWeek }}  من {{ \Carbon\Carbon::parse($reservation->start)->translatedFormat("h:i a") }} الي {{ \Carbon\Carbon::parse($reservation->end)->translatedFormat("h:i a") }}

</x-mail::message>
