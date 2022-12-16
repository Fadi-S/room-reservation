<x-mail::message>
# تم الموافقة علي الحجز

 خدمة  {{ $reservation->service->name }}

 **{{ $reservation->room->fullName }}**

يوم ** {{ $dayOfWeek }} {{ ($reservation->is_repeating ? "" : $reservation->date->format("d/m")) }}** من **{{ \Carbon\Carbon::parse($reservation->start)->translatedFormat("h:i a") }}** الي **{{ \Carbon\Carbon::parse($reservation->end)->translatedFormat("h:i a") }}**

<x-mail::button :url="$nextDate">
    رؤية الحجز
</x-mail::button>
</x-mail::message>
