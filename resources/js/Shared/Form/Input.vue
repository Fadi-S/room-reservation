<template>
    <div :class="width">
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium text-gray-700"
        >
            {{ label }}
        </label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <input
                :id="id"
                v-bind="$attrs"
                :type="type"
                @input="onChanged"
                :value="modelValue"
                :required="required"
                :dir="dir"
                :class="{
                    'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500':
                        errors.length,
                    'border-gray-300 focus:ring-green-600 focus:border-green-600':
                        !errors.length,

                    'pr-10': dir === 'ltr' && type !== 'time',
                    'pl-10': dir === 'rtl' && type !== 'time',
                }"
                class="block w-full focus:outline-none rounded-lg"
            />
            <div
                v-if="errors.length || icon"
                :class="[dir === 'ltr' ? 'right-0 pr-3' : 'left-0 pl-3']"
                class="absolute inset-y-0 flex items-center pointer-events-none"
            >
                <ExclamationCircleIcon
                    v-if="errors.length"
                    class="h-5 w-5 text-red-500"
                    aria-hidden="true"
                />
                <component :is="icon" class="h-5 w-5 text-gray-500" />
            </div>
        </div>
        <p v-if="errors.length" class="mt-2 text-sm text-red-600">
            <span>{{ errors }}</span>
        </p>
    </div>
</template>

<script setup>
import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";

defineProps({
    modelValue: String,
    type: {
        type: String,
        default: "text",
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
    dir: {
        type: String,
        default: "ltr",
    },
    icon: Function | Object,
    width: String,
});

let emit = defineEmits(["update:modelValue"]);

function onChanged(event) {
    emit("update:modelValue", event.currentTarget.value);
}
</script>
