<template>
    <Head title="Make Reservation" />

    <div class="bg-white md:rounded-lg shadow p-2 max-w-4xl mx-auto">
        <form @submit.prevent="submit" action="/reserve" method="POST">
            <div class="grid gap-4 md:grid-cols-2">
                <Select
                    id="service"
                    label="الخدمة"
                    :options="services"
                    v-model="form.service"
                    :errors="form.errors.service"
                    required
                />

                <Input
                    dir="rtl"
                    type="text"
                    label="اسم الاجتماع"
                    v-model="form.description"
                    :errors="form.errors.description"
                    id="description"
                    required
                />

                <Select
                    label="المكان"
                    :options="locationsForSelect"
                    v-model="locationID"
                />

                <Select
                    v-show="location?.rooms.length > 1"
                    label="الغرفة"
                    :options="rooms"
                    v-model="form.room"
                />

                <div class="w-full flex items-start justify-between">
                    <Input
                        type="time"
                        label="وقت البداية"
                        v-model="form.start"
                        :errors="form.errors.start"
                        id="start"
                        required
                    />
                    <ArrowLeftIcon class="w-5 h-5" />
                    <Input
                        type="time"
                        label="وقت النهاية"
                        v-model="form.end"
                        :errors="form.errors.end"
                        id="end"
                        required
                    />
                </div>

                <div class="flex items-center">
                    <Checkbox
                        class="w-full"
                        :label="form.isRepeating ? 'كل أسبوع' : 'مرة واحدة'"
                        id="repeating"
                        v-model="form.isRepeating"
                    />
                </div>

                <template v-if="form.isRepeating">
                    <Select
                        id="dayOfWeek"
                        :options="days"
                        v-model="form.dayOfWeek"
                        :errors="form.errors.dayOfWeek"
                        label="يوم الحجز"
                    />
                </template>

                <template v-else>
                    <DatePicker
                        v-model="form.date"
                        label="تاريخ الحجز"
                        id="date-picker"
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
import { CheckCircleIcon, ArrowLeftIcon } from "@heroicons/vue/24/outline";
import Input from "@/Shared/Form/Input.vue";
import Select from "@/Shared/Form/Select.vue";
import Checkbox from "@/Shared/Form/Checkbox.vue";
import DatePicker from "@/Shared/Form/DatePicker.vue";
import { computed, ref, watch } from "vue";

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
    start: null,
    end: null,
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
