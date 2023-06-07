<template>
    <div class="flex items-center whitespace-nowrap space-x-2 space-x-reverse">
        <div
            v-if="reservation.isRepeating"
            class="rounded-lg py-1 px-3 flex space-x-reverse space-x-1"
            :class="{
                'text-green-700 bg-green-100': !light,
                'text-gray-800': light,
            }"
        >
            <ArrowPathIcon
                :class="{
                    'w-6 h-6': !small,
                    'w-5 h-5': small,
                }"
            />
            <span
                :class="{
                    'font-semibold': !small,
                }"
                >كل اسبوع</span
            >
        </div>

        <div
            v-else
            class="rounded-lg py-1 px-3 flex space-x-reverse space-x-1"
            :class="{
                'text-red-700 bg-red-100': !light,
                'text-gray-800': light,
            }"
        >
            <ClockIcon
                :class="{
                    'w-6 h-6': !small,
                    'w-5 h-5': small,
                }"
            />
            <span
                :class="{
                    'font-semibold': !small,
                }"
                >مرة واحدة</span
            >
        </div>

        <div
            v-if="reservation.isRepeating && reservation.stopped_at"
            class="font-medium text-gray-500"
        >
            <span v-text="'حتى ' + reservation.stopped_at" />
        </div>
    </div>
</template>

<script setup>
import { ArrowPathIcon, ClockIcon } from "@heroicons/vue/24/solid";

defineProps({
    light: {
        type: Boolean,
        default: false,
    },
    small: {
        type: Boolean,
        default: false,
    },
    reservation: {
        type: Object,
        required: true,
    },
});
</script>
