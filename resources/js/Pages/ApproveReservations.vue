<template>
    <Head title="Approve" />

    <div
        v-if="reservations.length === 0"
        class="bg-white rounded-xl shadow-md mx-4 md:mx-auto max-w-xl p-3"
    >
        <div class="text-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="mx-auto h-12 w-12 text-gray-400"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                />
            </svg>

            <h3 class="mt-4 text-lg font-medium text-gray-600">
                لا يوجد حجوزات للموافقة عليها
            </h3>
        </div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
            class="bg-white rounded-lg p-4 border-t-4 flex flex-col justify-between"
            :style="{
                borderColor: reservation.service.color,
            }"
            v-for="reservation in reservations"
        >
            <h3
                class="font-semibold text-gray-600 text-lg"
                v-text="reservation.name"
            />

            <div class="mt-4 space-y-6">
                <div class="text-xl">
                    <span
                        :style="{
                            color: reservation.service.color,
                        }"
                        class="font-bold"
                    >
                        {{ reservation.dayName }}
                        <template v-if="!reservation.isRepeating">
                            {{ reservation.date }}
                        </template>
                    </span>
                    من
                    <time
                        :datetime="reservation.start"
                        :style="{
                            color: reservation.service.color,
                        }"
                        class="font-bold"
                        v-text="reservation.start"
                    />
                    إلي
                    <time
                        :datetime="reservation.end"
                        :style="{
                            color: reservation.service.color,
                        }"
                        class="font-bold"
                        v-text="reservation.end"
                    />

                    <div class="flex items-center">
                        <div
                            v-if="reservation.isRepeating"
                            class="text-green-800 bg-green-200 rounded-full p-1"
                        >
                            <ArrowPathIcon class="w-6 h-6" />
                        </div>

                        <div
                            v-else
                            class="text-red-800 bg-red-200 rounded-full p-1"
                        >
                            <ClockIcon class="w-6 h-6" />
                        </div>
                    </div>
                </div>

                <div class="text-xl">
                    <span
                        v-text="
                            reservation.room.location.name +
                            ' ' +
                            reservation.room.name
                        "
                        :style="{
                            color: reservation.service.color,
                        }"
                        class="font-bold"
                    />&nbsp;
                </div>
            </div>

            <div class="mt-4 flex items-center text-gray-500">
                <span>عبر</span>&nbsp;
                <span
                    v-text="'أ/ ' + reservation.reservedBy.name"
                    class="font-bold"
                />&nbsp;
            </div>

            <div class="mt-8 flex items-center justify-between">
                <Link
                    color="green"
                    as="button"
                    method="POST"
                    :href="reservation.links.approve"
                >
                    موافقة
                </Link>

                <Link
                    color="red"
                    outline
                    as="button"
                    method="DELETE"
                    :href="reservation.links.delete"
                >
                    مسح
                </Link>
            </div>
        </div>
    </div>
</template>

<script setup>
import Link from "@/Shared/Link.vue";
import { ArrowPathIcon, ClockIcon } from "@heroicons/vue/24/outline";

defineProps({
    reservations: Array,
});
</script>
