<template>
    <div class="border-none p-0" v-bind="$attrs">
        <select class="hidden" multiple @input="onChanged" v-bind="$attrs">
            <option
                v-for="item in options"
                :selected="isSelected(item)"
                :value="key(item)"
            >
                {{ print(item) }}
            </option>
        </select>

        <div
            class="flex w-full space-x-3 rtl:space-x-reverse justify-between items-center"
        >
            <div
                class="border flex w-full flex-col space-y-1 h-64 bg-gray-50 overflow-y-auto rounded-lg"
            >
                <button
                    v-for="item in notSelected"
                    type="button"
                    @click="add(item)"
                    class="hover:bg-gray-100 py-4 sm:py-2"
                >
                    {{ print(item) }}
                </button>
            </div>
            <div
                class="border flex w-full flex-col space-y-1 h-64 bg-gray-50 overflow-y-auto rounded-lg"
            >
                <button
                    v-for="item in selected"
                    type="button"
                    @click="remove(item)"
                    class="hover:bg-gray-100 py-4 sm:py-2"
                >
                    {{ print(item) }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import Button from "@/Shared/Form/Button.vue";
import { computed } from "vue";

const props = defineProps({
    options: {
        required: true,
    },
    modelValue: {
        type: Array,
        default: [],
    },
});

let emit = defineEmits(["update:modelValue"]);

function isSelected(item) {
    return props.modelValue.includes(key(item));
}

function key(item) {
    return Array.isArray(item) ? item[0] : item;
}

function add(item) {
    return props.modelValue.push(key(item));
}

function print(item) {
    return Array.isArray(item) ? item[1] : props.options[item];
}

function remove(item) {
    props.modelValue.splice(props.modelValue.indexOf(key(item)), 1);
}

function toggle(item) {
    if (isSelected(item)) remove(item);
    else add(item);
}

const notSelected = computed(() => {
    if (Array.isArray(props.options)) {
        return props.options.filter((item) => !props.modelValue.includes(item));
    }

    return Object.keys(props.options).filter(
        (key) => !props.modelValue.includes(key)
    );
});

const selected = computed(() => {
    if (Array.isArray(props.options)) {
        return props.options.filter((item) => props.modelValue.includes(item));
    }

    return Object.keys(props.options).filter((key) =>
        props.modelValue.includes(key)
    );
});

emit(
    "update:modelValue",
    props.modelValue.map((item) => item.toString())
);

function onChanged(event) {
    emit(
        "update:modelValue",
        Array.prototype.slice
            .call(event.currentTarget.selectedOptions)
            .map((o) => o.value)
    );
}
</script>
