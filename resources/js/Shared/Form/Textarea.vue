<template>
    <div :class="width">
        <label v-if="label" :for="id" class="block text-sm font-medium text-gray-700">
            {{ label }} <span v-if="required" class="text-red-600">*</span>
        </label>
        <div class="mt-1 relative rounded-md shadow-sm">
            <textarea
                :id="id"
                v-bind="$attrs"
                @input="onChanged"
                :value="modelValue"
                :required="required"
                :dir="dir"
                :class="[
                    errors.length
                        ? 'border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500'
                        : 'border-gray-300 focus:ring-primary focus:border-primary',
                ]"
                class="block w-full focus:outline-none rounded-lg"
            >
            </textarea>
            <div
                v-if="errors.length || icon"
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

<script>
import { ExclamationCircleIcon } from "@heroicons/vue/24/solid";

export default {
    name: "Input",
    inheritAttrs: false,
    components: {
        ExclamationCircleIcon,
    },
    methods: {
        onChanged(event) {
            this.$emit("update:modelValue", event.currentTarget.value);
        },
    },
    emits: ["update:modelValue"],
    props: {
        modelValue: String,
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
    },
};
</script>
