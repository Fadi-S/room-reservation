<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Naskh+Arabic:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <title>Table - StGeorge Rooms</title>
</head>
<body>
<body dir="rtl" class="h-full font-noto">
<div class="bg-gray-200 text-xl text-center p-1">
    {{ $date->translatedFormat("l j F Y") }}
</div>
<div class="overflow-x-auto bg-white">
    <table id="table" class="mt-3">
        <colgroup span="2"></colgroup>
        <colgroup span="2"></colgroup>
        <tr>
            <td class="border border-gray-500 p-2" colspan="2"></td>
            @foreach($timeSteps as $time)
                <th
                    class="border border-gray-500 p-2"
                >
                    <div class="flex flex-col items-center justify-center">
                        <div>
                            <time datetime="{{ $date->format("Y-m-d") . ' ' . $time["time"]->format("H:i:s") }}">
                                {{ $time["time"]->translatedFormat("h:i") }}
                            </time>
                        </div>
                        <div class="font-normal">
                            <time datetime="{{ $date->format("Y-m-d") . ' ' . $time["nextTime"]->format("H:i:s") }}">
                                {{ $time["nextTime"]->translatedFormat("h:i") }}
                            </time>
                        </div>
                    </div>
                </th>
            @endforeach
        </tr>

        @foreach($rooms as $room)
            @if($location_id !== $room->location_id && $location_id !== null)
                @continue
            @endif

            <tr class="border border-gray-500 p-2">
                @if($room->location->rooms[0]?->id === $room->id)
                    <th
                        rowspan="{{ $room->location->rooms->count() }}"
                        colspan="{{ $room->location->rooms->count() > 1 ? 1 : 2 }}"
                        class="border border-gray-500 p-2s sticky"
                    >
                        {{ $room->location->name }}
                    </th>
                @endif
                @if($room->location->rooms->count() > 1)
                    <th
                        class="border border-gray-500 p-2 whitespace-nowrap sticky"
                    >
                        <div class="flex flex-col">
                            <span class="font-semibold font-sans text-gray-800">{{ $room->name }}</span>
                            <span class="text-gray-600 text-sm">{{ $room->description }}</span>
                        </div>
                    </th>
                @endif

                @foreach($reservations[$room->id] as $reservation)
                    <td
                        class="border border-gray-500 p-1 text-gray-700"
                        colspan="{{ $reservation['numberOfTimeSlots'] ?? 1 }}"
                        @style([
                            isset($reservation['color']) ? ('background-color: ' . $reservation['color']['bg']) : '',
                        ])
                    >
                        @if($reservation && !$reservation['empty'])
                            <div class="flex flex-col space-y-1 text-lg text-center">
                                <span
                                    class="font-semibold"
                                    @style([
                                        'color: ' . $reservation['color']['text'],
                                    ])
                                >
                                    {{ $reservation['displayName'] }}
                                    {{ $reservation['service'] }}
                                </span>

                                <span
                                    class="text-sm opacity-80 font-sans"
                                    @style([
                                        'color: ' . $reservation['color']['text'],
                                    ])
                                >
                                    {{ $room->location->rooms->count() > 1 ? $reservation['room'] : $room->location->name }}
                                </span>
                                <div class="text-sm font-normal mt-2">
                                    <div
                                        class="flex flex-col items-center justify-center"
                                    >
                                        <div class="flex items-center">
                                            <span
                                                @style([
                                                    'color: ' . $reservation['color']['text'],
                                                ])
                                            >
                                            <time
                                                datetime="{{ $date->format("Y-m-d") . "" . $reservation['start']['time'] }}">
                                                {{ $reservation['start']['formatted'] }}
                                            </time>
                                            ->
                                            <time
                                                datetime="{{ $date->format("Y-m-d") . "" . $reservation['end']['time'] }}">
                                                {{ $reservation['end']['formatted'] }}
                                            </time>
                                        </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </td>
                @endforeach
            </tr>
        @endforeach
    </table>
</div>
</body>
</body>
</html>
