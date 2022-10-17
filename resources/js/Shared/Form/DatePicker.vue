<template>
    <div :dir="dir">
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium text-gray-700"
        >
            {{ label }} <span v-if="required" class="text-red-600">*</span>
        </label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <flat-pickr
                :id="id"
                v-bind="$attrs"
                :config="config"
                :required="required"
                dir="ltr"
                :class="[
                    errors.length
                        ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 focus:ring-primary focus:border-primary',
                ]"
                class="block w-full pr-10 focus:outline-none rounded-lg"
            />

            <div
                v-if="errors.length"
                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
            >
                <ExclamationCircleIcon
                    class="h-5 w-5 text-red-500"
                    aria-hidden="true"
                />
            </div>
        </div>
        <p v-if="errors.length" class="mt-2 text-sm text-red-600">
            <span>{{ errors }}</span>
        </p>
    </div>
</template>

<script setup>
import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
import flatPickr from "vue-flatpickr-component";
import "flatpickr/dist/flatpickr.css";
import { onMounted, ref } from "vue";

const props = defineProps({
    config: {
        type: Object,
        required: false,
        default: {
            altInput: true,
            altFormat: "F j, Y",
            disableMobile: true,
        },
    },
    type: {
        type: String,
        default: "text",
    },
    dir: {
        type: String,
        default: "ltr",
    },
    errors: {
        default: [],
    },
    value: {
        type: String,
        default: "",
    },
    required: {
        type: Boolean,
        default: false,
    },
    id: {
        type: String,
        default: "",
    },
    label: {
        type: String,
        default: "",
    },
    time: {
        type: Boolean,
        default: false,
    },
});

props.config.position = "center";

if (props.time) {
    props.config.enableTime = true;
    props.config.altFormat = "F j, Y G:i K";
}
</script>
