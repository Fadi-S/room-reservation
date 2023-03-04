<template>
    <Head title="جدول حجز الغرف" />

    <Tabs v-model="day" :tabs="nextWeekDays" />

    <div class="overflow-x-auto bg-white mt-3">
        <table id="table" class="scale-[0.4] sm:scale-[0.8] origin-top-right">
            <colgroup span="2"></colgroup>
            <colgroup span="2"></colgroup>
            <tr>
                <td class="border border-gray-500 p-2" colspan="2"></td>
                <th
                    class="border border-gray-500 p-2"
                    v-for="(time, index) in timeSteps.slice(0, -1)"
                >
                    <div class="flex items-center justify-center space-x-0.5">
                        <div>
                            <time :datetime="day + ' ' + time.id">
                                {{ time.name }}
                            </time>
                        </div>

                        <span v-if="(timeSteps[index + 1] ?? null) != null">
                            &nbsp;-
                        </span>

                        <div v-if="(timeSteps[index + 1] ?? null) != null">
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
                    {{ room.location.name }}
                </th>
                <th
                    v-if="room.location.rooms.length > 1"
                    class="border border-gray-500 p-2"
                >
                    {{ room.name }} {{ room.description }}
                </th>

                <td
                    class="border border-gray-500 p-2 text-gray-700"
                    v-for="reservation in reservationsInTime[room.id]"
                    :colspan="reservation.numberOfTimeSlots ?? 1"
                    :style="{
                        backgroundColor: reservation?.color?.bg,
                    }"
                >
                    <template v-if="reservation && !reservation.empty">
                        <div class="flex flex-col space-y-2">
                            <span
                                :style="{
                                    color: reservation.color.text,
                                }"
                            >
                                {{ reservation.displayName }}
                                {{ reservation.service }}
                            </span>

                            <span
                                class="text-sm font-semibold opacity-60"
                                :style="{
                                    color: reservation.color.text,
                                }"
                            >
                                {{ reservation.room }}
                            </span>
                            <div class="text-sm font-semibold opacity-80">
                                <div
                                    class="flex flex-col items-start justify-center"
                                >
                                    <div class="flex items-center">
                                        <div
                                            v-if="reservation.isRepeating"
                                            class="rounded-full"
                                            :style="{
                                                color: reservation.color.text,
                                            }"
                                        >
                                            <ArrowPathIcon class="w-6 h-6" />
                                        </div>

                                        <div
                                            v-else
                                            class="rounded-full"
                                            :style="{
                                                color: reservation.color.text,
                                            }"
                                        >
                                            <ClockIcon class="w-6 h-6" />
                                        </div>

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
                                        class="mt-2 text-gray-800/60 bg-gray-100/50 p-1 border-gray-800 rounded-full"
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
import { Inertia } from "@inertiajs/inertia";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import Tabs from "@/Shared/Tabs.vue";
import {
    ArrowPathIcon,
    ClockIcon,
    PencilSquareIcon,
} from "@heroicons/vue/20/solid";
import useUser from "@/Composables/useUser.js";
import Input from "@/Shared/Form/Input.vue";

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

    Inertia.get(
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

    // element.addEventListener("touchend", (event) => {
    //     // console.log('touchend', event);
    //     // Reset image to its original format
    //     element.style.transform = "";
    //     element.style.WebkitTransform = "";
    //     element.style.zIndex = "";
    // });
};

onMounted(() => {
    pinchZoom(document.getElementById("table"));
});
</script>
