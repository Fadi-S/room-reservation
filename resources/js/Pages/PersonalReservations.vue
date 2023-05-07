<template>
    <Head title="حجوزاتي" />

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
                لا يوجد حجوزات
            </h3>
        </div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <ReservationCard
            v-for="reservation in reservations"
            :reservation="reservation"
            :show-creator="false"
        >
            <template v-slot:actions>
                <div class="mt-2 flex items-center justify-between">
                    <Link
                        color="blue"
                        as="button"
                        outline
                        method="GET"
                        :href="reservation.links.edit"
                    >
                        تعديل
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
            </template>
        </ReservationCard>
    </div>
</template>

<script setup>
import Link from "@/Shared/Link.vue";
import { ArrowPathIcon, ClockIcon } from "@heroicons/vue/24/outline";
import ReservationCard from "@/Shared/ReservationCard.vue";

defineProps({
    reservations: Array,
});
</script>
