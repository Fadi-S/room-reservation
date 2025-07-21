<template>
    <Head title="حسابي" />

    <Card>
        <form :action="url" method="POST" @submit.prevent="submit">
            <div class="grid gap-8 grid-cols-2 md:gap-6">
                <Input
                    label="كلمة المرور الحالية"
                    type="password"
                    width="w-full col-span-2"
                    placeholder="********"
                    v-model="form.current_password"
                    :errors="form.errors.current_password"
                    id="current_password"
                    name="current_password"
                    required
                />

                <Input
                    label="كلمة المرور الجديدة"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="********"
                    v-model="form.password"
                    :errors="form.errors.password"
                    id="password"
                    name="password"
                    required
                />

                <Input
                    label="تأكيد كلمة المرور"
                    type="text"
                    width="w-full col-span-2"
                    placeholder="********"
                    v-model="form.password_confirmation"
                    :errors="form.errors.password_confirmation"
                    id="password_confirmation"
                    name="password_confirmation"
                    required
                />
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
import Form from "@/Pages/Users/Form.vue";
import Card from "@/Shared/Card.vue";
import { useForm } from "@inertiajs/vue3";
import Input from "@/Shared/Form/Input.vue";
import Button from "@/Shared/Form/Button.vue";

const props = defineProps({
    user: Object,
    url: String,
});

const form = useForm({
    current_password: null,
    password: null,
    password_confirmation: null,
});

function submit() {
    form.post(props.url, {
        onSuccess: () => {
            form.reset();
        },
    });
}
</script>
