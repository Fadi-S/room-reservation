<template>
    <RadioGroupOption
        as="template"
        :key="id"
        :value="value"
        v-slot="{ checked, active }"
    >
        <div
            :class="[
                checked ? 'border-transparent' : 'border-gray-300',
                active ? 'ring-2 ring-green-500' : '',
                'relative flex cursor-pointer rounded-lg border bg-white p-4 shadow-sm focus:outline-none',
            ]"
        >
            <span class="flex flex-1 ml-1">
                <span class="flex flex-col">
                    <RadioGroupLabel
                        as="span"
                        class="block text-sm font-medium text-gray-900"
                    >
                        {{ label }}
                    </RadioGroupLabel>
                </span>
            </span>
            <CheckCircleIcon
                v-if="checked"
                class="h-5 w-5 text-green-600"
                aria-hidden="true"
            />
            <span
                :class="[
                    active ? 'border' : 'border-2',
                    checked ? 'border-green-500' : 'border-transparent',
                    'pointer-events-none absolute -inset-px rounded-lg',
                ]"
                aria-hidden="true"
            />
        </div>
    </RadioGroupOption>
</template>
<script setup>
import { RadioGroupLabel, RadioGroupOption } from "@headlessui/vue";
import { CheckCircleIcon } from "@heroicons/vue/24/solid";

defineProps({
    label: String,
    id: String,
    value: Object,
});
</script>
