<template>
    <Head title="Login" />

    <div class="h-screen bg-gray-50">
        <div
            class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8"
        >
            <Logo class="mx-auto h-24 w-auto" />

            <div class="mt-4 sm:mx-auto sm:w-full sm:max-w-md">
                <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                    <form
                        class="space-y-4"
                        @submit.prevent="submit"
                        action="/login"
                        method="POST"
                    >
                        <Input
                            id="username"
                            name="email"
                            label="الأسم"
                            v-model="form.email"
                            :errors="form.errors.email"
                            type="text"
                            autocomplete="username"
                            required
                        />

                        <Input
                            id="password"
                            name="password"
                            label="كلمة المرور"
                            v-model="form.password"
                            type="password"
                            autocomplete="password"
                            required
                        />

                        <Button
                            :form="form"
                            :auto-disable="false"
                            type="submit"
                            color="green"
                        >
                            تسجيل دخول
                        </Button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import AuthLayout from "@/Pages/Auth/AuthLayout.vue";
import Logo from "@/Pages/Auth/Logo.vue";
import Input from "@/Shared/Form/Input.vue";
import Button from "@/Shared/Form/Button.vue";
import { useForm } from "@inertiajs/inertia-vue3";

export default {
    layout: AuthLayout,
    components: { Logo, Input, Button },
    setup() {
        let form = useForm("LoginForm", {
            email: "",
            password: "",
            remember: true,
        });

        const submit = () =>
            form.post("/login", {
                preserveScroll: false,
            });

        return {
            form,
            submit,
        };
    },
};
</script>
