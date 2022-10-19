<template>
    <Head title="جدول الغرف" />

    <div>
        <div class="sm:hidden">
            <label for="tabs" class="sr-only">Select a day</label>
            <select
                id="tabs"
                @change="day = $event.target.value"
                name="tabs"
                class="block w-full rounded-md border-gray-300 focus:border-green-500 focus:ring-green-500"
            >
                <option
                    v-for="tab in days"
                    :key="tab.id"
                    :selected="tab.id === day"
                >
                    {{ tab.name }}
                </option>
            </select>
        </div>
        <div class="hidden sm:block">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <button
                        v-for="tab in days"
                        :key="tab.name"
                        type="button"
                        @click="day = tab.id"
                        :class="[
                            tab.id === day
                                ? 'border-green-500 text-green-600'
                                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                            'w-1/4 py-4 px-1 text-center border-b-2 font-medium text-sm',
                        ]"
                        :aria-current="tab.id === day ? 'page' : undefined"
                    >
                        {{ tab.name }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="mt-3"></div>
    </div>
</template>

<script setup>
import { ref } from "vue";

defineProps({
    reservations: Object,
    locations: Array,
});

let day = ref(new Date().getDay());

const days = [
    {
        id: 0,
        name: "الأحد",
    },
    {
        id: 1,
        name: "الاثنين",
    },
    {
        id: 2,
        name: "الثلاثاء",
    },
    {
        id: 3,
        name: "الأربعاء",
    },
    {
        id: 4,
        name: "الخميس",
    },
    {
        id: 5,
        name: "الجمعة",
    },
    {
        id: 6,
        name: "السبت",
    },
];
</script>
