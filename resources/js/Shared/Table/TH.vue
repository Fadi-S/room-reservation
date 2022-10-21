<template>
    <th
        @click.prevent="sort.by(sortable)"
        v-if="!empty"
        scope="col"
        :class="{ 'cursor-pointer': !!sortable }"
        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
    >
        <div class="flex items-center group">
            <span><slot /></span>

            <ChevronDownIcon
                v-if="!!sortable"
                :class="{
                    'rotate-180': sort.direction === 'asc',
                    'rotate-180 opacity-0 group-hover:opacity-60 transition-all duration-[0]':
                        sort.name !== sortable,
                }"
                class="w-6 h-6"
            />
        </div>
    </th>

    <th v-else scope="col" class="relative px-6 py-3">
        <span class="sr-only">
            <slot />
        </span>
    </th>
</template>

<script setup>
import { ChevronDownIcon } from "@heroicons/vue/20/solid";
import { inject } from "vue";

const props = defineProps({
    empty: {
        type: Boolean,
        default: false,
    },
    sortable: {
        type: String,
        default: null,
    },
});

const sort = inject("sort");
</script>
