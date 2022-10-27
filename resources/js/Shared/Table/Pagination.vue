<template>
    <div
        v-show="items.links.prev || items.links.next"
        class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6"
    >
        <div class="flex-1 flex justify-between sm:hidden">
            <InertiaLink
                :href="items.links.prev"
                :preserve-scroll="true"
                :preserve-state="true"
                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
                السابق
            </InertiaLink>
            <span class="text-center">
                {{ items.meta.to }}
                of
                {{ items.meta.total }} results
            </span>
            <InertiaLink
                :href="items.links.next"
                :preserve-scroll="true"
                :preserve-state="true"
                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
            >
                التالي
            </InertiaLink>
        </div>
        <div
            class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between"
        >
            <div>
                <p class="text-sm text-gray-700">
                    Showing
                    {{ " " }}
                    <span class="font-medium" v-text="items.meta.from"></span>
                    {{ " " }}
                    to
                    {{ " " }}
                    <span class="font-medium" v-text="items.meta.to"></span>
                    {{ " " }}
                    of
                    {{ " " }}
                    <span class="font-medium" v-text="items.meta.total"></span>
                    {{ " " }}
                    results
                </p>
            </div>
            <div>
                <nav
                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px"
                    aria-label="Pagination"
                >
                    <InertiaLink
                        v-if="items.links.prev"
                        :preserve-scroll="true"
                        :preserve-state="true"
                        :href="items.links.prev"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                        <span class="sr-only">Previous</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </InertiaLink>

                    <span
                        v-else
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-300"
                    >
                        <span class="sr-only">Previous</span>
                        <ChevronRightIcon class="h-5 w-5" aria-hidden="true" />
                    </span>

                    <template v-for="(link, key) in items.meta.links">
                        <template
                            v-if="
                                key !== 0 && key < items.meta.links.length - 1
                            "
                        >
                            <Component
                                :is="link.active ? 'span' : 'InertiaLink'"
                                v-if="link.url"
                                :href="link.url"
                                :preserve-scroll="true"
                                :preserve-state="true"
                                :class="[
                                    link.active
                                        ? 'z-10 bg-red-50 border-red-500 text-red-600'
                                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                                ]"
                                v-html="link.label"
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                            />

                            <span
                                v-else
                                class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                                v-text="link.label"
                            />
                        </template>
                    </template>

                    <InertiaLink
                        :preserve-scroll="true"
                        :preserve-state="true"
                        v-if="items.links.next"
                        :href="items.links.next"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                        <span class="sr-only">Next</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </InertiaLink>

                    <span
                        v-else
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-300"
                    >
                        <span class="sr-only">Next</span>
                        <ChevronLeftIcon class="h-5 w-5" aria-hidden="true" />
                    </span>
                </nav>
            </div>
        </div>
    </div>
</template>

<script>
import { ChevronLeftIcon, ChevronRightIcon } from "@heroicons/vue/24/solid";

export default {
    components: {
        ChevronLeftIcon,
        ChevronRightIcon,
    },
    props: {
        items: {
            type: Object,
            default: () => {},
        },
    },
};
</script>
