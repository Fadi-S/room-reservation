<template>
    <div>
        <div class="relative w-full rounded-md shadow-sm">
            <input
                type="text"
                :name="queryString"
                :id="queryString"
                v-model="search"
                autocomplete="off"
                dir="auto"
                class="focus:ring-green-500 focus:border-green-500 block w-full pr-10 border-gray-300 rounded-lg"
                placeholder="بحث"
            />
            <div
                class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none"
            >
                <MagnifyingGlassIcon
                    class="h-5 w-5 text-gray-400"
                    aria-hidden="true"
                />
            </div>
        </div>
    </div>
</template>

<script setup>
import { MagnifyingGlassIcon } from "@heroicons/vue/24/solid";
import debounce from "lodash/debounce";
import { onMounted, ref, watch } from "vue";
import { router } from "@inertiajs/vue3";
import useQueryStringToJSON from "@/Composables/useQueryStringToJSON.js";

const props = defineProps({
    queryString: {
        type: String,
        default: "search",
    },
    url: {
        type: String,
        default: () => window.location.pathname,
    },
    only: Array,
});

let search = ref("");
let debouncedGetSearch;

onMounted(() => {
    debouncedGetSearch = debounce(getSearch, 200);

    const urlParams = new URLSearchParams(window.location.search);
    search.value = urlParams.get(props.queryString);
});

watch(search, () => debouncedGetSearch());

function getSearch() {
    const params = new URLSearchParams(window.location.search);
    if (search.value) {
        params.set(props.queryString, search.value);
        params.delete("page");
    } else params.delete(props.queryString);

    router.get(props.url, useQueryStringToJSON(params.toString()), {
        preserveState: true,
        preserveScroll: true,
        only: props.only,
    });
}
</script>
