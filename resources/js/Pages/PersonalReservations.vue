<template>
    <Head title="حجوزاتي" />

    <div
        v-for="[id, reservations] in finalReservations"
        class="flex flex-col space-y-6 divide-gray-500 divide-y"
    >
        <div>
            <div class="mt-8 mb-4 font-gray-800 text-2xl" v-if="id === 1">
                الحجوزات الموافق عليها
            </div>

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
                        لا يوجد حجوزات
                    </h3>
                </div>
            </div>

            <div
                v-else
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
            >
                <ReservationCard
                    v-for="reservation in reservations"
                    :reservation="reservation"
                    :show-creator="reservation.reservedBy.id !== user.id"
                >
                    <template v-slot:actions>
                        <div
                            class="mt-2 flex items-center justify-center md:justify-start rtl:space-x-reverse space-x-6"
                        >
                            <Link
                                v-if="!reservation.isApproved"
                                color="blue"
                                outline
                                method="GET"
                                padding="p-3"
                                rounded="rounded-full"
                                :href="reservation.links.edit"
                            >
                                <PencilSquareIcon class="w-6 h-6" />
                            </Link>

                            <div v-if="reservation.isApproved">
                                <Button
                                    color="blue"
                                    outline
                                    padding="p-3"
                                    rounded="rounded-full"
                                    @click="pauseModals[reservation.id] = true"
                                >
                                    <PauseIcon class="w-6 h-6" />
                                </Button>

                                <Modal
                                    :key="'pause-modal-' + reservation.id"
                                    :fixed="false"
                                    v-model="pauseModals[reservation.id]"
                                >
                                    <div class="sm:flex sm:items-start">
                                        <div
                                            class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10"
                                        >
                                            <PauseIcon
                                                class="h-6 w-6 text-blue-600"
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
                                                <span
                                                    >ايقاف
                                                    {{ reservation.name }}
                                                    مؤقتا</span
                                                >
                                            </DialogTitle>
                                        </div>
                                    </div>
                                    <form
                                        class="mt-4"
                                        method="POST"
                                        @submit.prevent="
                                            pause(
                                                reservation.links.pause,
                                                reservation.id
                                            )
                                        "
                                        :id="'pause-form-' + reservation.id"
                                    >
                                        <DatePicker
                                            v-model="form.date"
                                            required
                                            label="التاريخ"
                                            :time="false"
                                            :errors="form.errors.date"
                                        />
                                    </form>

                                    <template #footer>
                                        <div
                                            class="flex items-center justify-between w-full rtl:space-x-reverse space-x-4"
                                        >
                                            <Button
                                                :form="form"
                                                :for-form="
                                                    'pause-form-' +
                                                    reservation.id
                                                "
                                                color="green"
                                                class="w-full"
                                            >
                                                ايقاف مؤقت
                                            </Button>

                                            <Button
                                                type="button"
                                                @click="
                                                    pauseModals[
                                                        reservation.id
                                                    ] = false
                                                "
                                                color="light-gray"
                                                width="w-full"
                                            >
                                                اغلاق
                                            </Button>
                                        </div>
                                    </template>
                                </Modal>
                            </div>

                            <div>
                                <Button
                                    color="red"
                                    outline
                                    padding="p-3"
                                    rounded="rounded-full"
                                    @click="deleteModals[reservation.id] = true"
                                >
                                    <TrashIcon class="w-6 h-6" />
                                </Button>
                            </div>
                        </div>

                        <Modal
                            :key="'modal-' + reservation.id"
                            :fixed="false"
                            v-model="deleteModals[reservation.id]"
                        >
                            <div
                                class="hidden sm:block absolute top-0 left-0 pt-4 pl-4"
                            >
                                <button
                                    type="button"
                                    class="bg-white rounded-md text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    @click="
                                        deleteModals[reservation.id] = false
                                    "
                                >
                                    <span class="sr-only">Close</span>
                                    <XMarkIcon
                                        class="h-6 w-6"
                                        aria-hidden="true"
                                    />
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
                                            سيتم مسح
                                            <strong class="text-gray-600"
                                                >({{
                                                    reservation.name
                                                }})</strong
                                            >
                                            من البرنامج
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <template #footer>
                                <div
                                    class="flex items-center justify-between w-full sm:w-auto rtl:space-x-reverse space-x-4"
                                >
                                    <Link
                                        color="red"
                                        as="button"
                                        method="DELETE"
                                        class="w-full"
                                        :preserve-state="false"
                                        :href="
                                            reservation.isApproved
                                                ? reservation.links.stop
                                                : reservation.links.delete
                                        "
                                    >
                                        مسح
                                    </Link>

                                    <Button
                                        type="button"
                                        @click="
                                            deleteModals[reservation.id] = false
                                        "
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
        </div>
    </div>
</template>

<script setup>
import Link from "@/Shared/Link.vue";
import ReservationCard from "@/Shared/ReservationCard.vue";
import Button from "@/Shared/Form/Button.vue";
import Modal from "@/Shared/Modal.vue";
import {
    PencilSquareIcon,
    TrashIcon,
    XMarkIcon,
    PauseIcon,
} from "@heroicons/vue/24/outline";
import { DialogTitle } from "@headlessui/vue";
import { ref } from "vue";
import useUser from "@/Composables/useUser.js";
import DatePicker from "@/Shared/Form/DatePicker.vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
    pendingReservations: Array,
    approvedReservations: Array,
});

let form = useForm({
    date: null,
});

function pause(url, id) {
    form.post(url, {
        preserveScroll: true,
        onSuccess: () => {
            pauseModals.value[id] = false;
        },
    });
}

const user = useUser();

let finalReservations = [
    [0, props.pendingReservations],
    [1, props.approvedReservations],
];

const pauseModals = ref({});
const deleteModals = ref({});
for (let reservation of props.pendingReservations) {
    deleteModals.value[reservation.id] = false;
    pauseModals.value[reservation.id] = false;
}
for (let reservation of props.approvedReservations) {
    deleteModals.value[reservation.id] = false;
    pauseModals.value[reservation.id] = false;
}
</script>
