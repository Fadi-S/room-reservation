<template>
    <div
        class="bg-white md:rounded-lg shadow p-4 border-t-4 flex flex-col justify-between"
        :style="{
            borderColor: reservation.service.color,
        }"
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

                <StatusPill :reservation="reservation" />
            </div>

            <div>
                <span
                    v-text="
                        reservation.room.location.name +
                        ' ' +
                        reservation.room.name +
                        ' '
                    "
                    :style="{
                        color: reservation.service.color,
                    }"
                    class="font-bold"
                />
                <span
                    v-if="reservation.room.description"
                    :style="{
                        color: reservation.service.color,
                    }"
                    v-text="'(' + reservation.room.description + ')'"
                    class="text-gray-500 text-sm"
                />
            </div>
        </div>

        <div class="mt-4 flex items-center text-gray-500">
            <template v-if="showCreator">
                <span>عبر</span>&nbsp;
                <span v-text="reservation.reservedBy.name" class="font-bold" />
                &nbsp;
                <span>|</span>
                &nbsp;
            </template>
            <span v-text="reservation.created_at" class="font-bold" />
        </div>

        <div class="mt-6"></div>
        <hr />
        <slot name="actions" />
    </div>
</template>

<script setup>
import { ArrowPathIcon, ClockIcon } from "@heroicons/vue/24/outline/index.js";
import StatusPill from "@/Shared/StatusPill.vue";

defineProps({
    reservation: {
        type: Object,
        required: true,
    },
    showCreator: {
        type: Boolean,
        default: true,
    },
});
</script>
