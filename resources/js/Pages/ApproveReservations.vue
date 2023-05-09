<template>
    <Head title="Approve" />

    <div
        v-if="reservations.length === 0"
        class="bg-white rounded-xl shadow-md mx-4 md:mx-auto max-w-xl p-3"
    >
        <div class="text-center">
            <svg
                xmlns="http://www.w3.org/2000/svg"
                fill="none"
                viewBox="0 0 24 24"
                stroke-width="1.5"
                stroke="currentColor"
                class="mx-auto h-12 w-12 text-gray-400"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"
                />
            </svg>

            <h3 class="mt-4 text-lg font-medium text-gray-600">
                لا يوجد حجوزات للموافقة عليها
            </h3>
        </div>
    </div>

    <div v-else class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <ReservationCard
            v-for="reservation in reservations"
            :reservation="reservation"
        >
            <template v-slot:actions>
                <div
                    class="mt-2 flex items-center justify-center md:justify-start rtl:space-x-reverse space-x-6"
                >
                    <Link
                        color="green"
                        as="button"
                        method="POST"
                        padding="p-3"
                        rounded="rounded-full"
                        :href="reservation.links.approve"
                    >
                        <CheckIcon class="w-6 h-6" />
                    </Link>

                    <Link
                        color="blue"
                        outline
                        method="GET"
                        padding="p-3"
                        rounded="rounded-full"
                        :href="reservation.links.edit"
                    >
                        <PencilSquareIcon class="w-6 h-6" />
                    </Link>

                    <div>
                        <Button
                            color="red"
                            outline
                            padding="p-3"
                            rounded="rounded-full"
                            @click="deleteModal = true"
                        >
                            <TrashIcon class="w-6 h-6" />
                        </Button>
                    </div>
                </div>

                <Modal dir="rtl" :fixed="false" v-model="deleteModal">
                    <div
                        class="hidden sm:block absolute top-0 left-0 pt-4 pl-4"
                    >
                        <button
                            type="button"
                            class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            @click="deleteModal = false"
                        >
                            <span class="sr-only">Close</span>
                            <XMarkIcon class="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>

                    <div class="sm:flex sm:items-start">
                        <div
                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10"
                        >
                            <TrashIcon
                                class="h-6 w-6 text-red-600"
                                aria-hidden="true"
                            />
                        </div>
                        <div
                            class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left"
                        >
                            <DialogTitle
                                as="h3"
                                class="text-lg rtl:md:text-right mr-2 leading-6 font-medium text-gray-900"
                            >
                                <span>مسح الحجز</span>
                            </DialogTitle>
                            <div class="mt-2">
                                <p class="text-sm text-gray-500">
                                    سيتم مسح الحجز من البرنامج
                                </p>
                            </div>
                        </div>
                    </div>

                    <template #footer>
                        <div
                            class="flex items-center justify-between w-full sm:w-auto rtl:space-x-reverse space-x-4"
                        >
                            <Link
                                normal
                                color="red"
                                as="button"
                                method="DELETE"
                                class="w-full"
                                :href="reservation.links.delete"
                            >
                                مسح
                            </Link>

                            <Button
                                @click="deleteModal = false"
                                color="light-gray"
                                width="w-full"
                            >
                                اغلاق
                            </Button>
                        </div>
                    </template>
                </Modal>
            </template>
        </ReservationCard>
    </div>
</template>

<script setup>
import Link from "@/Shared/Link.vue";
import {
    TrashIcon,
    PencilSquareIcon,
    XMarkIcon,
} from "@heroicons/vue/24/outline";
import { CheckIcon } from "@heroicons/vue/24/solid";
import ReservationCard from "@/Shared/ReservationCard.vue";
import Modal from "@/Shared/Modal.vue";
import Button from "@/Shared/Form/Button.vue";
import { DialogTitle } from "@headlessui/vue";
import { ref } from "vue";

const deleteModal = ref(false);

defineProps({
    reservations: Array,
});
</script>
