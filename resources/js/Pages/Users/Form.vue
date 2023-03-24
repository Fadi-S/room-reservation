<template>
    <Head title="أضافة / تعديل مستخدم" />

    <Card>
        <form :action="url" method="POST" @submit.prevent="submit">
            <div class="grid gap-8 grid-cols-2 md:gap-6">
                <Input
                    dir="rtl"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="الاسم"
                    v-model="form.name"
                    :errors="form.errors.name"
                    id="name"
                    name="name"
                    autocomplete="name"
                    required
                />

                <Input
                    dir="rtl"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="اسم المستخدم"
                    v-model="form.username"
                    :errors="form.errors.username"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                />

                <Input
                    dir="ltr"
                    type="email"
                    width="w-full col-span-2"
                    placeholder="البريد الالكتروني"
                    v-model="form.email"
                    :errors="form.errors.email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    required
                />

                <MultiSelect
                    class="col-span-2"
                    :options="services"
                    v-model="form.services"
                />

                <Checkbox id="is-admin" v-model="form.is_admin" label="أدمن" />
            </div>

            <div>
                <Button
                    class="mt-4"
                    width="flex items-center md:w-auto w-full"
                    type="submit"
                    color="green"
                    :form="form"
                >
                    تسجيل
                </Button>
            </div>
        </form>
    </Card>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";
import { computed } from "vue";
import Button from "@/Shared/Form/Button.vue";
import Card from "@/Shared/Card.vue";
import Input from "@/Shared/Form/Input.vue";
import Checkbox from "@/Shared/Form/Checkbox.vue";
import MultiSelect from "@/Shared/Form/MultiSelect.vue";

const props = defineProps({
    user: Object,
    create: Boolean,
    services: Object,
});

const form = useForm({
    name: props.user.name,
    username: props.user.username,
    email: props.user.email,
    is_admin: props.user.is_admin ?? false,
    password: null,
    services: Object.values(props.user.services ?? {}),
});

const url = computed(() => {
    return props.create ? "/users" : `/users/${props.user.username}`;
});

function submit() {
    if (props.create) {
        return form.post(url.value);
    }

    return form.patch(url.value);
}
</script>
