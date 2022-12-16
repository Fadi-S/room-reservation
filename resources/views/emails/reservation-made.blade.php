<x-mail::message>
# حجز جديد

{{ $reservation->reservedBy->name }} من خدمة {{ $reservation->service->name }}

يريد حجز **{{ $reservation->room->fullName }}**

 يوم ** {{ $dayOfWeek }} {{ ($reservation->is_repeating ? "" : $reservation->date->format("d/m")) }}** من **{{ \Carbon\Carbon::parse($reservation->start)->translatedFormat("h:i a") }}** الي **{{ \Carbon\Carbon::parse($reservation->end)->translatedFormat("h:i a") }}**

<x-mail::button :url="$approveUrl">
    اضغط هنا للموافقة أو الرفض
</x-mail::button>
</x-mail::message>
