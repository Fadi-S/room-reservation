<template>
    <div
        aria-live="assertive"
        class="fixed inset-0 flex items-end px-4 py-6 z-40 pointer-events-none sm:p-6 sm:items-start"
    >
        <div class="w-full flex flex-col items-center space-y-4 sm:items-end">
            <transition
                enter-active-class="transform ease-out duration-300 transition"
                enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                leave-active-class="transition ease-in duration-100"
                leave-from-class="opacity-100"
                leave-to-class="opacity-0"
            >
                <div
                    v-if="show"
                    class="max-w-sm w-full bg-white shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden"
                >
                    <div class="p-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <CheckCircleIcon
                                    v-if="type === 'success'"
                                    class="h-6 w-6 text-green-400"
                                    aria-hidden="true"
                                />

                                <ClipboardIcon
                                    v-if="type === 'clipboard'"
                                    class="h-6 w-6 text-blue-400"
                                    aria-hidden="true"
                                />

                                <ExclamationCircleIcon
                                    v-if="type === 'error'"
                                    class="h-6 w-6 text-red-400"
                                    aria-hidden="true"
                                />

                                <ExclamationTriangleIcon
                                    v-if="type === 'warning'"
                                    class="h-6 w-6 text-orange-400"
                                    aria-hidden="true"
                                />

                                <ExclamationTriangleIcon
                                    v-if="type === 'info'"
                                    class="h-6 w-6 text-blue-400"
                                    aria-hidden="true"
                                />
                            </div>
                            <div class="mr-3 w-0 flex-1 pt-0.5">
                                <p class="text-sm font-medium text-gray-900">
                                    <span v-if="type === 'success'">
                                        تم بنجاح
                                    </span>

                                    <span v-if="type === 'error'"> خطأ </span>

                                    <span v-if="type === 'warning'">
                                        احظر
                                    </span>

                                    <span v-if="type === 'info'"> انتبه </span>
                                </p>
                                <p
                                    class="mt-1 text-sm text-gray-500"
                                    v-text="message"
                                ></p>
                            </div>
                            <div class="mr-4 flex-shrink-0 flex">
                                <button
                                    @click="show = false"
                                    class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                >
                                    <span class="sr-only">Close</span>
                                    <XMarkIcon
                                        class="h-5 w-5"
                                        aria-hidden="true"
                                    />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </transition>
        </div>
    </div>
</template>

<script setup>
import {
    CheckCircleIcon,
    ExclamationCircleIcon,
    ExclamationTriangleIcon,
    ClipboardIcon,
} from "@heroicons/vue/24/outline";
import { XMarkIcon } from "@heroicons/vue/24/solid";
import { ref, watch } from "vue";
import { usePage } from "@inertiajs/inertia-vue3";

let show = ref(false);
let type = ref("success");
let message = ref("");
let important = ref(true);

let page = usePage();

watch(
    () => page,
    () => setFlash(page.props.value.flash),
    { deep: true }
);

window.addEventListener("flash", (e) => setFlash(e.detail));

function setFlash(flash) {
    message.value = flash.message ?? flash.success;

    important.value = isNaN(flash.important) ? flash.important : false;

    show.value = !!message.value;

    type.value = flash.type ?? "success";

    if (!important.value) {
        setTimeout(
            () => {
                show.value = false;
                message.value = "";
            },
            parseInt(flash.important) > 0 ? flash.important : 5000
        );
    }
}
</script>
