<template>
    <button
        type="button"
        @click="$refs.input.click()"
        as="checkbox"
        :aria-label="`Toggle ${label} permission`"
        class="rounded-2xl flex items-center justify-center space-x-2 transition-colors focus:outline-none focus:ring focus:ring-primary duration-150 cursor-pointer my-2 p-2 group"
        :class="[
            {
                'bg-gray-200 text-gray-700 shadow-md active:hover:shadow-inner': !modelValue,
                'bg-light text-primary shadow-inner shadow-primary-2/30': modelValue,
            },
            this.class,
        ]"
    >
        <span v-if="!$slots.default" v-text="label" />
        <slot />

        <CheckCircleIcon
            class="w-6 h-6 transition-opacity"
            :class="{
                'opacity-100': modelValue,
                'opacity-0': !modelValue,
            }"
        />

        <input
            ref="input"
            :id="id"
            v-bind="$attrs"
            @change="onChanged"
            :checked="!!modelValue"
            value="1"
            type="checkbox"
            class="hidden"
        />
    </button>
</template>

<script>
import { CheckCircleIcon } from "@heroicons/vue/24/solid";

export default {
    name: "Checkbox",
    methods: {
        onChanged(event) {
            this.$emit("update:modelValue", event.currentTarget.checked);
        },
    },
    components: { CheckCircleIcon },
    emits: ["update:modelValue"],
    props: {
        modelValue: Boolean,
        class: String,
        name: {
            type: String,
            default: null,
        },
        id: {
            type: String,
            default: null,
        },
        label: {
            type: String,
            default: null,
        },
    },
};
</script>
