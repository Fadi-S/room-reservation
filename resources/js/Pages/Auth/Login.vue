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
                            label="الاسم أو البريد الإلكتروني"
                            v-model="form.email"
                            :errors="form.errors.email"
                            type="text"
                            autocomplete="username"
                            required
                        />

                        <Input
                            v-if="loginType === 'email-password'"
                            id="password"
                            name="password"
                            width="w-full"
                            label="كلمة المرور"
                            v-model="form.password"
                            :errors="form.errors.password"
                            type="password"
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

                        <div class="pt-6">
                            <button
                                class="text-blue-600 underline"
                                @click="toggleLoginType"
                                type="button"
                            >
                                <template v-if="loginType === 'email-password'">
                                    تسجيل الدخول باستخدام البريد الإلكتروني فقط
                                </template>

                                <template v-if="loginType === 'email-only'">
                                    تسجيل الدخول باستخدام البريد الإلكتروني
                                    وكلمة المرور
                                </template>
                            </button>
                        </div>
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
import { useForm } from "@inertiajs/vue3";
import { LockClosedIcon } from "@heroicons/vue/24/solid";
import Spinner from "@/Shared/Spinner.vue";
import { computed, ref } from "vue";

export default {
    layout: AuthLayout,
    components: { Spinner, Logo, Input, Button, LockClosedIcon },
    setup() {
        let loginType = ref("email-password");
        const otherType = computed(
            () =>
                ({
                    "email-password": "email-only",
                    "email-only": "email-password",
                }[loginType.value])
        );

        const url = computed(
            () =>
                ({
                    "email-password": "/login",
                    "email-only": "/login-by-email",
                }[loginType.value])
        );

        let form = useForm({
            email: "",
            password: "",
            remember: true,
        });

        const submit = () =>
            form.post(url.value, {
                onSuccess: () => {
                    form.reset();
                },
            });

        return {
            form,
            submit,
            loginType,
            otherType,
        };
    },
    methods: {
        toggleLoginType() {
            this.loginType = this.otherType;
        },
    },
};
</script>
