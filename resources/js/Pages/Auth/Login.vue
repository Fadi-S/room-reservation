<template>
    <Head title="Login" />

    <div class="h-screen bg-gray-50">
        <div class="flex min-h-full flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <Logo class="mx-auto h-24 w-auto" />
            <h2 class="mt-6 text-center text-3xl font-bold tracking-tight text-gray-900">Sign in</h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="#" method="POST">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Code</label>
                        <div class="mt-1">
                            <input
                                id="code"
                                   name="code" type="text"
                                   autocomplete="current-code"
                                   required=""
                                   class="block w-full appearance-none rounded-md border border-gray-300 px-3 py-2 placeholder-gray-400 shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-indigo-500 sm:text-sm"
                            />
                        </div>
                    </div>

                    <div>
                        <button type="submit"
                                class="flex w-full justify-center rounded-md border border-transparent bg-green-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">
                            Enter
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

export default {
    components: {Logo},
    layout: AuthLayout,
    setup() {

    },
}
</script>
