<template>
    <component
        :is="normal ? 'a' : 'InertiaLink'"
        :href="href"
        v-bind="$attrs"
        class="inline-flex flex justify-center text-center items-center border font-medium text-base focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors duration-300"
        :class="finalColor + ' ' + padding + ' ' + rounded"
    >
        <slot />
    </component>
</template>

<script setup>
import { colors } from "@/stores/buttonColors.js";

const props = defineProps({
    href: {
        type: String,
        default: "submit",
    },
    normal: {
        type: Boolean,
        default: false,
    },
    color: {
        type: String,
        default: "primary",
    },
    padding: {
        type: String,
        default: "px-4 py-2",
    },
    rounded: {
        type: String,
        default: "rounded-lg",
    },
    outline: {
        type: Boolean,
        default: false,
    },
    plain: {
        type: Boolean,
        default: false,
    },
});

const suffix = props.outline ? "outline" : props.plain ? "plain" : "solid";

let finalColor =
    colors[props.color + "-" + suffix] ?? colors["primary-" + suffix];
</script>
