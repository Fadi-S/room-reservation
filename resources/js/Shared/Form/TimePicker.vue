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
            <Datepicker
                dir="ltr"
                :required="required"
                input-class-name="block w-full focus:outline-none rounded-lg"
                :uid="id"
                timePicker
                :is24="false"
                auto-apply
                preview-format="hh:mm a"
                format="hh:mm a"
                v-bind="$attrs"
                @update:modelValue="onChanged"
                :modelValue="modelValue"
            />
            <div
                v-if="errors.length"
                :class="[dir === 'ltr' ? 'right-0 pr-3' : 'left-0 pl-3']"
                class="absolute inset-y-0 flex items-center pointer-events-none"
            >
                <ExclamationCircleIcon
                    v-if="errors.length"
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

<script>
import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";
import Datepicker from "@vuepic/vue-datepicker";
import "@vuepic/vue-datepicker/dist/main.css";

export default {
    name: "TimePicker",
    inheritAttrs: false,
    components: {
        ExclamationCircleIcon,
        Datepicker,
    },
    methods: {
        onChanged(value) {
            this.$emit("update:modelValue", value);
        },
    },
    emits: ["update:modelValue"],
    props: {
        modelValue: Object,
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
        width: String,
    },
};
</script>
