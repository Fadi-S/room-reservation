<template>
    <Head title="جدول حجز الغرف" />

    <Tabs v-model="day" :tabs="nextWeekDays" />

    <div class="overflow-x-auto">
        <table class="mt-3">
            <colgroup span="2"></colgroup>
            <colgroup span="2"></colgroup>
            <tr>
                <td class="border border-gray-500 p-2" colspan="2"></td>
                <th
                    class="border border-gray-500 p-2"
                    v-for="time in timeSteps"
                >
                    {{ time.name }}
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
                    <span
                        v-if="reservation && !reservation.empty"
                        :style="{
                            color: reservation.color.text,
                        }"
                    >
                        {{ reservation.displayName }}
                        {{ reservation.service }}
                    </span>
                </td>
            </tr>
        </table>
    </div>
</template>

<script setup>
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import Tabs from "@/Shared/Tabs.vue";

const props = defineProps({
    reservations: Object,
    locations: Array,
    nextWeekDays: Array,
    timeSteps: Array,
});

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

        for (let i = 0; i < props.timeSteps.length; i++) {
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
</script>
