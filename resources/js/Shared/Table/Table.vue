<template>
    <div>
        <div
            class="flex space-y-4 mx-2 md:space-y-0 flex-col md:flex-row items-center justify-between mb-2"
        >
            <Link
                color="green"
                outline
                class="w-full sm:w-auto"
                :href="createLink"
                v-if="createLink"
            >
                <PlusIcon class="w-5 h-5 rtl:ml-1 ltr:mr-1" />
                <span v-text="createLabel"></span>
            </Link>

            <div
                class="w-full flex flex-col md:justify-end flex-col-reverse space-y-reverse space-y-2 md:space-y-0 md:space-x-2 md:flex-row items-center"
            >
                <div v-if="hasFilters" class="w-full md:w-auto">
                    <Popover class="relative">
                        <PopoverButton
                            class="relative flex items-start w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-gray-100"
                        >
                            <FunnelIcon
                                class="w-5 h-5"
                                :class="{
                                    'text-gray-500': !appliedFiltersCount,
                                    'text-primary': appliedFiltersCount,
                                }"
                            />

                            <div
                                v-if="appliedFiltersCount"
                                class="absolute -top-1 -right-2 bg-primary rounded-full h-4 w-4"
                            >
                                <div
                                    v-text="appliedFiltersCount"
                                    class="text-light text-xs font-semibold"
                                />
                            </div>
                        </PopoverButton>

                        <transition
                            enter-active-class="transition ease-out duration-100"
                            enter-from-class="transform opacity-0 scale-95"
                            enter-to-class="transform opacity-100 scale-100"
                            leave-active-class="transition ease-in duration-75"
                            leave-from-class="transform opacity-100 scale-100"
                            leave-to-class="transform opacity-0 scale-95"
                        >
                            <PopoverPanel
                                v-slot="{ close }"
                                class="absolute right-0 z-10 mt-2 w-56 px-3 py-2 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                            >
                                <div v-if="appliedFiltersCount">
                                    <Button
                                        @click="clearFilters(close)"
                                        width="w-full"
                                        color="light-gray"
                                        type="button"
                                    >
                                        Clear Filters
                                    </Button>
                                </div>
                                <div
                                    class="flex flex-col space-y-4 divide-y divide-gray-200"
                                >
                                    <template
                                        v-for="filter of filters"
                                        :key="filter.column"
                                    >
                                        <Select
                                            class="pt-2"
                                            v-model="
                                                appliedFilters[filter.column]
                                            "
                                            :label="filter.name"
                                            :options="filter.items"
                                        />
                                    </template>
                                </div>
                            </PopoverPanel>
                        </transition>
                    </Popover>
                </div>

                <Search
                    v-if="queryString"
                    :query-string="queryString"
                    :only="searchOnly"
                    class="flex items-start w-full md:w-auto"
                />
            </div>
        </div>
        <div class="overflow-x-auto">
            <div class="align-middle inline-block min-w-full">
                <div class="shadow border-b border-gray-200 sm:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <slot name="header" />
                            </tr>
                        </thead>
                        <tbody>
                            <slot
                                v-if="
                                    items.hasOwnProperty('data')
                                        ? items.data.length
                                        : items.length ?? 1
                                "
                            />

                            <tr class="bg-white" v-else>
                                <td
                                    colspan="100"
                                    class="px-6 py-4 whitespace-no-wrap text-center"
                                >
                                    <p
                                        class="text-sm leading-5 py-4 flex space-x-2 items-center justify-center text-gray-600"
                                    >
                                        <InboxIcon
                                            class="w-10 h-10 text-gray-500"
                                        />
                                        <span>No results found.</span>
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <pagination v-if="items.hasOwnProperty('links')" :items="items" />
    </div>
</template>

<script setup>
import Pagination from "@/Shared/Table/Pagination.vue";
import Link from "@/Shared/Link.vue";
import Search from "@/Shared/Search.vue";
import { PlusIcon, InboxIcon, FunnelIcon } from "@heroicons/vue/24/solid";
import { computed, provide, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import { Popover, PopoverButton, PopoverPanel } from "@headlessui/vue";
import Select from "@/Shared/Form/Select.vue";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";
import Button from "@/Shared/Form/Button.vue";

const props = defineProps({
    items: {
        type: Object,
        default: {},
    },
    createLink: {
        type: String,
        default: () => window.location.pathname + "/create",
    },
    createLabel: {
        type: String,
        default: "أضف",
    },
    queryString: {
        type: String,
        default: "search",
    },
    searchOnly: Array,
    hasFilters: {
        type: Boolean,
        default: false,
    },
});

let sort = ref({
    name: "",
    direction: "",
    by(column) {
        if (!column) return;

        const params = new URLSearchParams(window.location.search);
        if (sort.value.name === column) {
            column = (sort.value.direction === "asc" ? "-" : "") + column;
        }

        params.set("sort", column);

        Inertia.get(
            window.location.pathname,
            useQueryStringToJSON(params.toString()),
            {
                preserveState: true,
                preserveScroll: true,
                onSuccess: setupSortAndFilters,
            }
        );
    },
});

const filters = ref(Inertia.page.props.filters ?? []);
const hasFilters =
    filters.value.length > 0 ||
    props.hasFilters ||
    Inertia.page.props.hasFilters;

let appliedFilters = ref({});
const appliedFiltersCount = computed(
    () => Object.values(appliedFilters.value).filter((val) => val).length
);
filters.value.forEach((filter) => {
    appliedFilters.value[filter.column] = "";
});

setupSortAndFilters();
provide("sort", sort);

watch(
    appliedFilters,
    (_) => {
        const params = new URLSearchParams(window.location.search);
        for (const [key, value] of Object.entries(appliedFilters.value)) {
            if (!value) {
                params.delete(`filter[${key}]`);
                continue;
            }

            params.set(`filter[${key}]`, value);
        }

        Inertia.get(
            window.location.pathname,
            useQueryStringToJSON(params.toString()),
            {
                preserveScroll: true,
                preserveState: true,
                onSuccess: setupSortAndFilters,
            }
        );
    },
    { deep: true }
);

function setupSortAndFilters() {
    const params = new URLSearchParams(window.location.search);
    const sortParams = params.get("sort");
    if (sortParams) {
        sort.value.direction = sortParams[0] === "-" ? "desc" : "asc";
        sort.value.name =
            sortParams[0] === "-" ? sortParams.substring(1) : sortParams;
    }

    filters.value = Inertia.page.props.filters ?? [];

    filters.value.forEach((filter) => {
        appliedFilters.value[filter.column] =
            params.get(`filter[${filter.column}]`) ?? "";
    });
}

function clearFilters(close) {
    for (const [key, value] of Object.entries(appliedFilters.value)) {
        appliedFilters.value[key] = "";
    }

    close();
}
</script>
