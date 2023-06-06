<template>
    <Head title="جدول حجز الغرف" />

    <div
        class="flex flex-col-reverse md:flex-row items-center justify-start space-x-reverse space-y-reverse space-y-6 sm:space-y-0 sm:space-x-2"
    >
        <Tabs v-model="day" />

        <div class="w-full md:w-auto pr-2 pl-2">
            <Link
                normal
                color="green"
                class="w-full"
                outline
                :href="`/table/print?date=${day}`"
            >
                <span>طباعة</span>
            </Link>
        </div>
    </div>

    <div class="overflow-x-auto mt-3">
        <table
            id="table"
            class="bg-white scale-[0.4] lg:scale-[0.9] sm:scale-100 print:block origin-top-right"
        >
            <colgroup span="2"></colgroup>
            <colgroup span="2"></colgroup>
            <tr>
                <td class="border border-gray-500 p-2" colspan="2"></td>
                <th
                    class="border border-gray-500 p-2"
                    v-for="(time, index) in timeSteps.slice(0, -1)"
                >
                    <div
                        class="flex flex-col font-normal items-center justify-center"
                    >
                        <div class="font-semibold">
                            <time :datetime="day + ' ' + time.id">
                                {{ time.name }}
                            </time>
                        </div>
                        <div>
                            <time
                                :datetime="day + ' ' + timeSteps[index + 1].id"
                            >
                                {{ timeSteps[index + 1].name }}
                            </time>
                        </div>
                    </div>
                </th>
            </tr>

            <tr class="border border-gray-500 p-2" v-for="room in rooms">
                <th
                    v-if="room.location.rooms[0]?.id === room.id"
                    :rowspan="room.location.rooms.length"
                    :colspan="room.location.rooms.length > 1 ? 1 : 2"
                    class="border border-gray-500 p-2"
                >
                    <div class="flex flex-col space-y-1 items-center">
                        <span v-text="room.location.name" />
                        <Link
                            padding="p-2"
                            rounded="rounded"
                            normal
                            color="green"
                            plain
                            :href="`/table/print/${room.location.id}?date=${day}`"
                        >
                            <PrinterIcon class="w-6 h-6" />
                        </Link>
                    </div>
                </th>
                <th
                    v-if="room.location.rooms.length > 1"
                    class="border border-gray-500 p-2 whitespace-nowrap sticky"
                >
                    <div class="flex flex-col">
                        <span
                            class="font-semibold font-sans text-gray-800"
                            v-text="room.name"
                        />
                        <span
                            class="text-gray-600 text-sm"
                            v-text="room.description"
                        />
                    </div>
                </th>

                <td
                    class="relative border border-gray-500 p-2 text-gray-700"
                    v-for="reservation in reservationsInTime[room.id]"
                    :colspan="reservation.numberOfTimeSlots ?? 1"
                    :style="{
                        backgroundColor: reservation?.color?.bg,
                    }"
                >
                    <template v-if="reservation && !reservation.empty">
                        <div
                            class="flex flex-col space-y-1 text-lg text-center"
                        >
                            <span
                                :style="{
                                    color: reservation.color.text,
                                }"
                            >
                                {{ reservation.displayName }}
                                {{ reservation.service }}
                            </span>

                            <span
                                class="text-sm opacity-80 font-sans"
                                :style="{
                                    color: reservation.color.text,
                                }"
                            >
                                {{
                                    room.location.rooms.length > 1
                                        ? reservation.room
                                        : room.location.name
                                }}
                            </span>
                            <div class="text-sm font-normal mt-2">
                                <div
                                    class="flex flex-col items-center justify-center"
                                >
                                    <StatusPill
                                        light
                                        :reservation="reservation"
                                    />
                                    <div class="flex items-center">
                                        <span class="text-gray-800">
                                            <time
                                                :datetime="
                                                    day +
                                                    ' ' +
                                                    reservation.start.time
                                                "
                                                >{{
                                                    reservation.start.formatted
                                                }}</time
                                            >
                                            ->
                                            <time
                                                :datetime="
                                                    day +
                                                    ' ' +
                                                    reservation.end.time
                                                "
                                                >{{
                                                    reservation.end.formatted
                                                }}</time
                                            >
                                        </span>
                                    </div>

                                    <InertiaLink
                                        class="print:hidden absolute text-gray-800/80 bg-gray-100/50 p-2 border-gray-800 rounded-full bottom-0 right-0 m-2"
                                        v-if="user?.isAdmin"
                                        :href="reservation.edit"
                                    >
                                        <PencilSquareIcon class="w-6 h-6" />
                                    </InertiaLink>
                                </div>
                            </div>
                        </div>
                    </template>
                </td>
            </tr>
        </table>
    </div>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import Tabs from "@/Shared/Tabs.vue";
import { PencilSquareIcon } from "@heroicons/vue/20/solid";
import { PrinterIcon } from "@heroicons/vue/24/outline";
import useUser from "@/Composables/useUser.js";
import { router } from "@inertiajs/vue3";
import StatusPill from "@/Shared/StatusPill.vue";
import Button from "@/Shared/Form/Button.vue";
import Link from "@/Shared/Link.vue";

const props = defineProps({
    reservations: Object,
    locations: Array,
    nextWeekDays: Array,
    timeSteps: Array,
});

let user = useUser();

const rooms = computed(() => {
    return props.locations.reduce((acc, location) => {
        let rooms = location.rooms.map((room) => {
            room.location = location;

            return room;
        });

        return [...acc, ...rooms];
    }, []);
});

const reservationsInTime = computed(() => {
    let reservations = {};

    for (let room of rooms.value) {
        reservations[room.id] = [];

        for (let i = 0; i < props.timeSteps.length - 1; i++) {
            let time = props.timeSteps[i];
            if (
                props.reservations[room.id] &&
                props.reservations[room.id][time.id]
            ) {
                let reservation = props.reservations[room.id][time.id][0];
                reservation.empty = false;
                reservations[room.id].push(reservation);

                i += reservation.numberOfTimeSlots - 1;
                continue;
            }

            reservations[room.id].push({
                empty: true,
            });
        }
    }

    return reservations;
});

const day = ref(new Date().getDay());
function setupStateFromURL() {
    const params = new URLSearchParams(window.location.search);

    day.value = params.get("day") || day.value;
}

setupStateFromURL();

watch(day, () => {
    const params = new URLSearchParams(window.location.search);
    params.set("day", day.value.toString());

    router.get(
        window.location.pathname,
        useQueryStringToJSON(params.toString()),
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: setupStateFromURL,
        }
    );
});

const pinchZoom = (element) => {
    let scale = 0.4;

    let start = {};

    // Calculate distance between two fingers
    const distance = (event) => {
        return Math.hypot(
            event.touches[0].pageX - event.touches[1].pageX,
            event.touches[0].pageY - event.touches[1].pageY
        );
    };

    const map = (x, newStart, newEnd) => {
        return x * (newEnd - newStart) + newStart;
    };

    element.addEventListener("touchstart", (event) => {
        // console.log('touchstart', event);
        if (event.touches.length === 2) {
            event.preventDefault();

            // Calculate where the fingers have started on the X and Y axis
            start.x = (event.touches[0].pageX + event.touches[1].pageX) / 2;
            start.y = (event.touches[0].pageY + event.touches[1].pageY) / 2;
            start.distance = distance(event);
        }
    });

    element.addEventListener("touchmove", (event) => {
        // console.log('touchmove', event);
        if (event.touches.length === 2) {
            event.preventDefault(); // Prevent page scroll

            const deltaDistance = distance(event);
            scale = map(deltaDistance / start.distance, 0.2, 0.4);

            // scale = Math.max(Math.min(scale, 0.4), 0.2);

            // Transform the image to make it grow and move with fingers
            const transform = `scale(${scale})`;
            element.style.transform = transform;
            element.style.WebkitTransform = transform;
            // element.style.zIndex = "9999";
        }
    });
};

onMounted(() => {
    const table = document.getElementById("table");
    pinchZoom(table);
});
</script>
