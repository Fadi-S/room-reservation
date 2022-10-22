<template>
    <Head title="جدول حجز الغرف" />

    <Tabs v-model="day" :tabs="nextWeekDays" />

    <table class="mt-3 overflow-x-scroll">
        <colgroup span="2"></colgroup>
        <colgroup span="2"></colgroup>
        <tr>
            <td class="border border-gray-500 p-2" rowspan="2"></td>
            <th
                class="border border-gray-500 p-2"
                v-for="location in locations"
                :colspan="location.rooms.length"
                scope="colgroup"
            >
                {{ location.name }}
            </th>
        </tr>

        <tr>
            <th
                v-for="room in rooms"
                class="border border-gray-500 p-2"
                scope="col"
            >
                {{ room.name }} {{ room.description }}
            </th>
        </tr>

        <tr v-for="time in timeSteps">
            <th class="border border-gray-500 p-2" scope="row">
                {{ time.name }}
            </th>
            <td
                class="border border-gray-500 p-2 w-20"
                v-for="room in rooms"
            ></td>
        </tr>
    </table>
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
        return [...acc, ...location.rooms];
    }, []);
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
