<template>
    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a day</label>
            <select
                id="tabs"
                @change="onChanged($event.target.value)"
                name="tabs"
                class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
            >
                <option
                    v-for="tab in tabs"
                    :key="tab.id"
                    :value="tab.id"
                    :selected="tab.id === modelValue"
                >
                    {{ tab.name }}
                </option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button
                        v-for="tab in tabs"
                        :key="tab.name"
                        type="button"
                        @click="onChanged(tab.id)"
                        :class="[
                            tab.id === modelValue
                                ? 'border-green-500 text-green-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm',
                        ]"
                        :aria-current="
                            tab.id === modelValue ? 'page' : undefined
                        "
                    >
                        {{ tab.name }}
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    tabs: {
        required: true,
    },
    modelValue: {
        type: Array,
        default: [],
    },
});

let emit = defineEmits(["update:modelValue"]);

function onChanged(tab) {
    emit("update:modelValue", tab);
}
</script>
