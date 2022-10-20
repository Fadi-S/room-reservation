<template>
    <Head title="جدول الغرف" />

    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a day</label>
            <select
                id="tabs"
                @change="day = $event.target.value"
                name="tabs"
                class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
            >
                <option
                    v-for="tab in days"
                    :key="tab.id"
                    :value="tab.id"
                    :selected="tab.id === day"
                >
                    {{ tab.name }}
                </option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button
                        v-for="tab in days"
                        :key="tab.name"
                        type="button"
                        @click="day = tab.id"
                        :class="[
                            tab.id === day
                                ? 'border-green-500 text-green-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm',
                        ]"
                        :aria-current="tab.id === day ? 'page' : undefined"
                    >
                        {{ tab.name }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="mt-3">
            <div v-if="!reservationsForDay" class="text-center">
                <CalendarDaysIcon class="mx-auto h-10 w-10 text-gray-400" />
                <h3 class="mt-2 text-sm font-medium text-gray-900">
                    لا يوجد حجوزات في هذا اليوم
                </h3>
                <div class="mt-4">
                    <InertiaLink
                        href="/reserve"
                        class="inline-flex items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        <PlusIcon
                            class="-mr-1 ml-2 h-5 w-5"
                            aria-hidden="true"
                        />
                        حجز جديد
                    </InertiaLink>
                </div>
            </div>

            <template v-else>
                <div class="flex flex-col space-y-6 p-4">
                    <div
                        class="bg-white rounded-lg shadow p-3"
                        v-for="location in locations"
                    >
                        <h2
                            class="text-gray-600 font-bold text-lg max-w-xs"
                            v-text="location.name"
                        />

                        <div
                            class="space-y-3 grid gap-6 grid-cols-1 sm:grid-cols-2 md:grid-cols-3"
                        >
                            <div
                                class="border mt-3 border-gray-200 overflow-hidden pb-2 rounded-lg"
                                v-for="room in location.rooms"
                                v-show="reservationsForDay[room.id]?.length > 0"
                            >
                                <h3
                                    class="text-gray-500 flex items-center justify-center bg-gray-200 py-2 w-full font-bold"
                                    v-text="room.name + ' ' + room.description"
                                />

                                <div class="mt-3 flex flex-col space-y-6">
                                    <div
                                        v-for="reservation in reservationsForDay[
                                            room.id
                                        ]"
                                        class="flex flex-col space-y-0.5 px-3"
                                    >
                                        <div
                                            class="flex items-center space-x-2 rtl:space-x-reverse"
                                        >
                                            <div
                                                class="rounded-full w-4 h-4"
                                                :style="{
                                                    backgroundColor:
                                                        reservation.color,
                                                }"
                                            ></div>
                                            <span>
                                                {{ reservation.displayName }}
                                                {{ reservation.service }}
                                            </span>
                                        </div>

                                        <div class="flex items-center pb-3">
                                            <span
                                                class="px-2 py-1 rounded-full bg-green-50 text-green-800 border-green-800 border"
                                            >
                                                <time
                                                    :datetime="
                                                        reservation.start.time
                                                    "
                                                    v-text="
                                                        reservation.start
                                                            .formatted
                                                    "
                                                />
                                                -
                                                <time
                                                    :datetime="
                                                        reservation.end.time
                                                    "
                                                    v-text="
                                                        reservation.end
                                                            .formatted
                                                    "
                                                />
                                            </span>
                                        </div>

                                        <hr />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </template>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { PlusIcon, CalendarDaysIcon } from "@heroicons/vue/24/solid";

const props = defineProps({
    reservations: Object,
    locations: Array,
});

let day = ref(new Date().getDay());
const reservationsForDay = computed(() => props.reservations[day.value]);

const days = [
    {
        id: 0,
        name: "الأحد",
    },
    {
        id: 1,
        name: "الاثنين",
    },
    {
        id: 2,
        name: "الثلاثاء",
    },
    {
        id: 3,
        name: "الأربعاء",
    },
    {
        id: 4,
        name: "الخميس",
    },
    {
        id: 5,
        name: "الجمعة",
    },
    {
        id: 6,
        name: "السبت",
    },
];
</script>
