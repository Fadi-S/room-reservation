<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Table - StgSporting Rooms</title>
</head>
<body>
<body dir="rtl" class="h-full font-vazirmatn">
<div class="overflow-x-auto bg-white">
    <div class="bg-gray-200 text-2xl text-center p-3">
        {{ $date->translatedFormat("l j F Y") }}
    </div>

    <table id="table" class="mt-4">
        <colgroup span="2"></colgroup>
        <colgroup span="2"></colgroup>
        <tr>
            <td class="border border-gray-500 p-2" colspan="2"></td>
            @foreach($timeSteps as $time)
                <th
                    class="border border-gray-500 p-2"
                >
                    <div class="flex items-center justify-center space-x-0.5">
                        <div>
                            <time datetime="{{ $date->format("Y-m-d") . ' ' . $time["time"]->format("H:i:s") }}">
                                {{ $time["time"]->translatedFormat("h:i") }}
                            </time>
                        </div>

                        <span> &nbsp;- </span>

                        <div>
                            <time datetime="{{ $date->format("Y-m-d") . ' ' . $time["nextTime"]->format("H:i:s") }}">
                                {{ $time["nextTime"]->translatedFormat("h:i") }}
                            </time>
                        </div>
                    </div>
                </th>
            @endforeach
        </tr>

        @foreach($rooms as $room)
            <tr class="border border-gray-500 p-2">
                @if($room->location->rooms[0]?->id === $room->id)
                    <th
                        rowspan="{{ $room->location->rooms->count() }}"
                        colspan="{{ $room->location->rooms->count() > 1 ? 1 : 2 }}"
                        class="border border-gray-500 p-2"
                    >
                        {{ $room->location->name }}
                    </th>
                @endif
                @if($room->location->rooms->count() > 1)
                    <th
                        class="border border-gray-500 p-2"
                    >
                        {{ $room->name }} {{ $room->description }}
                    </th>
                @endif

                @foreach($reservations[$room->id] as $reservation)
                    <td
                        class="border border-gray-500 p-2 text-gray-700"
                        colspan="{{ $reservation['numberOfTimeSlots'] ?? 1 }}"
                        @style([
                            isset($reservation['color']) ? ('background-color: ' . $reservation['color']['bg']) : '',
                        ])
                    >
                        @if($reservation && !$reservation['empty'])
                            <div class="flex flex-col space-y-2">
                            <span
                                @style([
                                    'color: ' . $reservation['color']['text'],
                                ])
                            >
                                {{ $reservation['displayName'] }}
                                {{ $reservation['service'] }}
                            </span>

                                <span
                                    class="text-sm font-semibold opacity-60"
                                    @style([
                                        'color: ' . $reservation['color']['text'],
                                    ])
                                >
                                {{ $reservation['room'] }}
                            </span>
                                <div class="text-sm font-semibold opacity-80">
                                    <div
                                        class="flex flex-col items-start justify-center"
                                    >
                                        <div class="flex items-center">
                                            <span class="text-gray-800">
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
