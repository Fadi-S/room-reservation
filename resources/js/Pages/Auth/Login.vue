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
                            width="w-full"
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
                            width="w-full"
                            v-model="form.password"
                            type="password"
                            autocomplete="password"
                            required
                        />

                        <Button type="submit" color="green">
                            <div class="flex items-center">
                                <Spinner
                                    v-if="form.processing"
                                    class="w-5 h-5 ml-2"
                                />
                                <LockClosedIcon v-else class="w-5 h-5 ml-2" />
                                <span>تسجيل دخول</span>
                            </div>
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
import { LockClosedIcon } from "@heroicons/vue/24/solid";
import Spinner from "@/Shared/Spinner.vue";

export default {
    layout: AuthLayout,
    components: { Spinner, Logo, Input, Button, LockClosedIcon },
    setup() {
        let form = useForm("LoginForm", {
            email: "",
            password: "",
            remember: true,
        });

        const submit = () => form.post("/login");

        return {
            form,
            submit,
        };
    },
};
</script>
