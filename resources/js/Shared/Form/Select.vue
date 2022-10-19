<template>
    <div>
        <label
            v-if="label"
            :for="$attrs.id"
            class="block text-sm font-medium text-gray-700"
        >
            {{ label }}
        </label>
        <select
            v-bind="$attrs"
            @input="onChanged"
            :value="modelValue"
            :required="required"
            class="mt-1 block w-full pl-3 pr-10 py-2 text-base focus:outline-none rounded-lg"
            :class="[
                errors.length
                    ? 'border-red-300 text-red-900 focus:ring-red-500 focus:border-red-500'
                    : 'border-gray-300 focus:ring-green-500 focus:border-green-500',
            ]"
        >
            <option v-if="placeholder" value="" v-text="placeholder" />

            <option
                v-for="(label, key) in options"
                :value="key"
                v-text="label"
            />
        </select>
        <p v-if="errors.length" class="mt-2 text-sm text-red-600">
            <span>{{ errors }}</span>
        </p>
    </div>
</template>

<script>
export default {
    name: "Select",
    methods: {
        onChanged(event) {
            this.$emit("update:modelValue", event.currentTarget.value);
        },
    },
    emits: ["update:modelValue"],
    props: {
        modelValue: String,
        multiple: {
            type: Boolean,
            default: false,
        },
        required: {
            type: Boolean,
            default: false,
        },
        placeholder: {
            type: String,
            required: false,
        },
        options: {
            type: Object,
            default: () => {},
        },
        errors: {
            default: [],
        },
        label: {
            type: String,
            default: "",
        },
    },
};
</script>
