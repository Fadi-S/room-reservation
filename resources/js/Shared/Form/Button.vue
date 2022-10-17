<template>
    <button
        :form="forForm ? forForm : null"
        :disabled="form && (form.processing || (!form.isDirty && autoDisable))"
        :type="type"
        class="inline-flex flex justify-center text-center items-center border border-transparent text-base font-medium rounded-lg focus:outline-none focus:ring-2 focus:ring-offset-2"
        :class="finalColor + ' ' + padding + ' ' + width"
    >
        <div v-if="form">
            <PencilIcon v-show="!form.processing" class="w-5 h-5 mr-2" />
            <Spinner v-show="form.processing" class="w-6 h-6 mr-2" />
        </div>

        <slot />

        <span v-show="form && form.isDirty">&nbsp;*</span>
    </button>
</template>

<script setup>
import Spinner from "@/Shared/Spinner.vue";
import { PencilIcon } from "@heroicons/vue/24/solid";
import { colors } from "@/stores/buttonColors.js";

const props = defineProps({
    type: {
        type: String,
        default: "submit",
    },
    color: {
        type: String,
        default: "primary",
    },
    padding: {
        type: String,
        default: "px-4 py-2",
    },
    outline: {
        type: Boolean,
        default: false,
    },
    form: {
        required: false,
    },
    autoDisable: {
        type: Boolean,
        default: true,
    },
    width: {
        type: String,
        default: "w-full xs:w-auto",
    },
    plain: {
        type: Boolean,
        default: false,
    },
});

let forForm = typeof props.form === "string" ? props.form : false;
let form = typeof props.form === "object" ? props.form : null;

const suffix = props.outline ? "outline" : props.plain ? "plain" : "solid";

let finalColor = colors[props.color + "-" + suffix] ?? colors["primary-" + suffix];
</script>
