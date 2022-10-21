<template>
    <Head title="Make Reservation" />

    <div
        class="bg-white md:rounded-lg shadow px-3 md:px-6 py-4 max-w-xl mx-auto"
    >
        <form @submit.prevent="submit" action="/reserve" method="POST">
            <div class="grid gap-8 grid-cols-2 md:gap-6">
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
                    required
                />

                <Select
                    class="col-span-2"
                    placeholder="-- اختار المكان --"
                    :options="locationsForSelect"
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
                    <TimePicker
                        required
                        class="col-span-1"
                        label="وقت البداية"
                        minutesGridIncrement="30"
                        minutes-increment="30"
                        v-model="form.start"
                        :errors="form.errors.start"
                    />

                    <TimePicker
                        required
                        class="col-span-1"
                        label="وقت النهاية"
                        minutesGridIncrement="30"
                        minutes-increment="30"
                        v-model="form.end"
                        :errors="form.errors.end"
                    />
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
                احجز
            </Button>
        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/inertia-vue3";
import Button from "@/Shared/Form/Button.vue";
import { CheckCircleIcon } from "@heroicons/vue/24/outline";
import Input from "@/Shared/Form/Input.vue";
import Select from "@/Shared/Form/Select.vue";
import DatePicker from "@/Shared/Form/DatePicker.vue";
import { computed, ref, watch } from "vue";
import { RadioGroup, RadioGroupLabel } from "@headlessui/vue";
import MyRadioOption from "@/Pages/MyRadioOption.vue";
import TimePicker from "@/Shared/Form/TimePicker.vue";

const props = defineProps({
    locations: Array,
    services: Object,
});

const locationID = ref(null);

let form = useForm("ReservationForm", {
    isRepeating: false,
    room: null,
    dayOfWeek: null,
    description: null,
    date: null,
    start: {
        hours: new Date().getHours(),
        minutes: 0,
    },
    end: {
        hours: new Date().getHours(),
        minutes: 0,
    },
    service: Object.keys(props.services)[0] ?? null,
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
    form.room = location.value?.rooms[0]?.id?.toString();
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
    form.post("/reserve");
}
</script>
