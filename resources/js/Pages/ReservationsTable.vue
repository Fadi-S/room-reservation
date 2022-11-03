<template>
    <Head title="جدول حجز الغرف" />

    <Tabs v-model="day" :tabs="nextWeekDays" />

    <div class="overflow-x-auto bg-white">
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
                    <template v-if="reservation && !reservation.empty">
                        <div class="flex flex-col space-y-1">
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

                                    <InertiaLink class="text-gray-50" v-if="user.isAdmin" :href="reservation.edit">
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
import { computed, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import Tabs from "@/Shared/Tabs.vue";
import { ArrowPathIcon, ClockIcon, PencilSquareIcon } from "@heroicons/vue/20/solid";
import useUser from "@/Composables/useUser.js";

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
