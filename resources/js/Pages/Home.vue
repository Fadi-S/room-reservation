<template>
    <Head title="جدول الغرف" />

    <div class="max-w-7xl mx-auto">
        <Tabs v-model="day" />

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
                                        class="flex items-center justify-between"
                                    >
                                        <div
                                            class="flex flex-col space-y-0.5 px-3"
                                        >
                                            <div
                                                class="flex items-center space-x-2 rtl:space-x-reverse"
                                            >
                                                <div
                                                    class="rounded-full w-4 h-4"
                                                    :style="{
                                                        backgroundColor:
                                                            reservation.color
                                                                .original,
                                                    }"
                                                ></div>
                                                <span>
                                                    {{
                                                        reservation.displayName
                                                    }}
                                                    {{ reservation.service }}
                                                </span>
                                            </div>

                                            <div class="flex items-center pb-3">
                                                <span
                                                    class="px-2 py-1 rounded-full bg-green-50 text-green-800 border-green-800 border"
                                                >
                                                    <time
                                                        :datetime="
                                                            reservation.start
                                                                .time
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
                                        </div>

                                        <InertiaLink
                                            class="text-gray-600 bg-gray-100 border-gray-800 rounded-full"
                                            v-if="user?.isAdmin"
                                            :href="reservation.edit"
                                        >
                                            <PencilSquareIcon class="w-6 h-6" />
                                        </InertiaLink>

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
import { computed, ref, watch } from "vue";
import {
    PlusIcon,
    CalendarDaysIcon,
    PencilSquareIcon,
} from "@heroicons/vue/24/solid";
import Tabs from "@/Shared/Tabs.vue";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import useUser from "@/Composables/useUser.js";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    reservations: Object,
    locations: Array,
    days: Array,
});

let user = useUser();

let day = ref(new Date().getDay());
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

const reservationsForDay = computed(() => props.reservations);
</script>
