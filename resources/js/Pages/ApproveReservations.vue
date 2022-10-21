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
            class="bg-white rounded-lg p-4"
            v-for="reservation in reservations"
        >
            {{ reservation.displayName }}
        </div>
    </div>
</template>

<script setup>
defineProps({
    reservations: Array,
});
</script>
