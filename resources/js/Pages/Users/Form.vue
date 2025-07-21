<template>
    <Head title="أضافة / تعديل مستخدم" />

    <Card>
        <form :action="url" method="POST" @submit.prevent="submit">
            <div class="grid gap-8 grid-cols-2 md:gap-6">
                <Input
                    label="الاسم"
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
                    label="اسم المستخدم"
                    dir="ltr"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="testuser"
                    v-model="form.username"
                    :errors="form.errors.username"
                    id="username"
                    name="username"
                    autocomplete="username"
                    required
                />

                <Input
                    dir="ltr"
                    label="البريد الالكتروني"
                    type="email"
                    width="w-full col-span-2"
                    placeholder="test@example.com"
                    v-model="form.email"
                    :errors="form.errors.email"
                    id="email"
                    name="email"
                    autocomplete="email"
                    required
                />

                <div
                    class="flex items-end rtl:flex-row-reverse space-x-2 col-span-2"
                >
                    <Button
                        type="button"
                        @click="generatePassword"
                        width="w-auto"
                        color="blue"
                    >
                        <ArrowPathIcon class="w-5 h-5" />
                    </Button>

                    <Input
                        label="كلمة المرور"
                        dir="ltr"
                        type="text"
                        width="w-full col-span-2"
                        placeholder="********"
                        v-model="form.password"
                        :errors="form.errors.password"
                        id="password"
                        name="password"
                        :required="create"
                    />
                </div>

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
                    width="flex items-center w-full"
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
import { ArrowPathIcon } from "@heroicons/vue/24/outline";

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

function generatePassword() {
    form.password = (Math.random() * 15).toString().substring(3, 11);

    navigator.clipboard.writeText(form.password);
}

function submit() {
    if (props.create) {
        return form.post(url.value);
    }

    return form.patch(url.value);
}
</script>
