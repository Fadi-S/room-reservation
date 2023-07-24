<template>
    <Head title="Make Reservation" />

    <Card>
        <div class="flex items-center space-x-2 space-x-reverse">
            <Link
                v-if="deleteUrl"
                as="button"
                method="DELETE"
                color="red"
                outline
                :href="deleteUrl"
            >
                <div class="flex items-center space-x-reverse space-x-1">
                    <XCircleIcon class="w-6 h-6" />
                    <span>إيقاف</span>
                </div>
            </Link>

            <div v-if="reservation?.pause" class="flex items-center">
                <Button
                    color="blue"
                    outline
                    @click="pauseModal = true"
                    type="button"
                >
                    <div class="flex items-center space-x-reverse space-x-1">
                        <PauseIcon class="w-6 h-6" />
                        <span>إيقاف مؤقت</span>
                    </div>
                </Button>
            </div>
        </div>

        <Modal
            v-if="reservation?.pause"
            key="pause-modal"
            :fixed="false"
            v-model="pauseModal"
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
                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
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
                @submit.prevent="pause"
                id="pause-form"
            >
                <DatePicker
                    v-model="pauseForm.date"
                    required
                    label="التاريخ"
                    :time="false"
                    :errors="pauseForm.errors.date"
                />
            </form>

            <template #footer>
                <div
                    class="flex items-center justify-between w-full rtl:space-x-reverse space-x-4"
                >
                    <Button
                        :form="pauseForm"
                        for-form="pause-form"
                        color="green"
                        class="w-full"
                    >
                        ايقاف مؤقت
                    </Button>

                    <Button
                        type="button"
                        @click="pauseModal = false"
                        color="light-gray"
                        width="w-full"
                    >
                        اغلاق
                    </Button>
                </div>
            </template>
        </Modal>

        <form @submit.prevent="submit" :action="url" method="POST">
            <div class="grid gap-8 grid-cols-2 md:gap-6 mt-4">
                <Select
                    id="service"
                    class="col-span-2"
                    placeholder="-- الخدمة --"
                    :options="services"
                    v-model="form.service"
                    :errors="form.errors.service"
                    required
                />

                <Input
                    dir="rtl"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="اسم الاجتماع"
                    v-model="form.description"
                    :errors="form.errors.description"
                    id="description"
                    name="reservation-description"
                    autocomplete="reservation-description"
                />

                <Select
                    class="col-span-2"
                    placeholder="-- اختار المكان --"
                    :options="locationsForSelect"
                    :errors="form.errors.room"
                    v-model="locationID"
                />

                <Select
                    class="col-span-2"
                    v-show="location?.rooms.length > 1"
                    placeholder="-- الغرفة --"
                    :options="rooms"
                    v-model="form.room"
                />

                <div class="contents">
                    <div class="col-span-1">
                        <TimePicker
                            required
                            label="وقت البداية"
                            minutesGridIncrement="5"
                            minutes-increment="30"
                            v-model="form.start"
                            :errors="form.errors.start"
                        />
                    </div>

                    <div class="col-span-1">
                        <TimePicker
                            required
                            label="وقت النهاية"
                            minutesGridIncrement="5"
                            minutes-increment="30"
                            v-model="form.end"
                            :errors="form.errors.end"
                        />
                    </div>
                </div>

                <div class="flex col-span-2">
                    <RadioGroup v-model="form.isRepeating">
                        <RadioGroupLabel
                            class="text-lg font-medium text-gray-900"
                        >
                            نوع الحجز
                        </RadioGroupLabel>

                        <div
                            class="mt-1 w-full space-x-6 rtl:space-x-reverse flex items-center justify-between"
                        >
                            <MyRadioOption
                                label="مرة واحدة"
                                id="one-time"
                                :value="false"
                            />

                            <MyRadioOption
                                label="كل أسبوع"
                                id="repeating"
                                :value="true"
                            />

                            <MyRadioOption
                                v-if="isInSummer"
                                label="خلال الصيف"
                                id="summer-only"
                                value="summer"
                            />
                        </div>
                    </RadioGroup>
                </div>

                <template v-if="form.isRepeating">
                    <Select
                        class="col-span-2"
                        id="dayOfWeek"
                        :options="days"
                        v-model="form.dayOfWeek"
                        :errors="form.errors.dayOfWeek"
                        placeholder="-- يوم الحجز --"
                    />
                </template>

                <template v-else>
                    <DatePicker
                        class="col-span-2"
                        v-model="form.date"
                        placeholder="تاريخ الحجز"
                        id="date-picker"
                        :config="{
                            altInput: true,
                            altFormat: 'l, j F Y',
                            disableMobile: true,
                        }"
                        :errors="form.errors.date"
                        required
                    />
                </template>
            </div>
            <Button
                class="mt-4"
                width="flex items-center md:w-auto w-full"
                color="green"
                type="submit"
                :form="form"
            >
                <template #icon>
                    <CheckCircleIcon class="w-6 h-6 ml-2" />
                </template>
                تسجيل
            </Button>
        </form>
    </Card>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import Button from "@/Shared/Form/Button.vue";
import { CheckCircleIcon, XCircleIcon } from "@heroicons/vue/24/outline";
import Input from "@/Shared/Form/Input.vue";
import Select from "@/Shared/Form/Select.vue";
import DatePicker from "@/Shared/Form/DatePicker.vue";
import { computed, ref, watch } from "vue";
import { DialogTitle, RadioGroup, RadioGroupLabel } from "@headlessui/vue";
import MyRadioOption from "@/Pages/MyRadioOption.vue";
import TimePicker from "@/Shared/Form/TimePicker.vue";
import Card from "@/Shared/Card.vue";
import Link from "@/Shared/Link.vue";
import Modal from "@/Shared/Modal.vue";
import { PauseIcon } from "@heroicons/vue/24/outline/index.js";

const props = defineProps({
    locations: Array,
    services: Object,
    reservation: Object,
    url: String,
    deleteUrl: String,
    isInSummer: Boolean,
});

let pauseModal = ref(false);
let pauseForm = useForm({
    date: null,
});

function pause() {
    if (props.reservation?.pause)
        pauseForm.post(props.reservation.pause, {
            onSuccess: () => {
                pauseModal.value = false;
            },
        });
}

let isCreate = props.reservation == null;

const locationID = ref(props.reservation?.location_id);

let form = useForm("ReservationForm", {
    isRepeating: isCreate ? false : props.reservation.is_repeating,
    room: props.reservation?.room_id,
    dayOfWeek: props.reservation?.day_of_week,
    description: props.reservation?.description,
    date: props.reservation?.date,
    start: {
        hours: isCreate
            ? new Date().getHours()
            : props.reservation.start.split(":")[0],
        minutes: isCreate ? 0 : props.reservation.start.split(":")[1],
    },
    end: {
        hours: isCreate
            ? new Date().getHours()
            : props.reservation.end.split(":")[0],
        minutes: isCreate ? 30 : props.reservation.end.split(":")[1],
    },
    service: isCreate
        ? Object.keys(props.services)[0] ?? null
        : props.reservation?.service_id,
});

const location = computed(
    () => props.locations.filter((loc) => loc.id == locationID.value)[0]
);

const locationsForSelect = computed(() => {
    let locations = {};

    for (let loc of props.locations) {
        locations[loc.id] = loc.name;
    }

    return locations;
});

const rooms = computed(() => {
    let rooms = {};

    if (location.value) {
        for (let room of location.value.rooms) {
            rooms[room.id] = room.name + " " + room.description ?? "";
        }

        return rooms;
    }

    for (let loc of props.locations) {
        for (let room of loc.rooms) {
            rooms[room.id] = room.name + " " + room.description ?? "";
        }
    }

    return rooms;
});

watch(location, () => {
    form.room =
        props.reservation?.room_id ?? location.value?.rooms[0]?.id?.toString();
});

const days = {
    0: "الأحد",
    1: "الاثنين",
    2: "الثلاثاء",
    3: "الأربعاء",
    4: "الخميس",
    5: "الجمعة",
    6: "السبت",
};

function submit() {
    if (isCreate) form.post(props.url);
    else form.patch(props.url);
}
</script>
