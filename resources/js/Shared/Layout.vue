<template>
    <div class="min-h-full h-screen">
        <Disclosure as="nav" class="bg-gray-800" v-slot="{ open, close }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <logo class="h-8 w-8" />
                        </div>
                        <div class="hidden md:block">
                            <div
                                class="mr-10 flex items-baseline space-x-reverse space-x-4"
                            >
                                <InertiaLink
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :class="[
                                        item.current
                                            ? 'bg-gray-900 text-white'
                                            : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                        'px-3 py-2 rounded-md text-sm font-medium',
                                    ]"
                                    :aria-current="
                                        item.current ? 'page' : undefined
                                    "
                                >
                                    {{ item.name }}
                                </InertiaLink>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="mr-4 flex items-center md:mr-6">
                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative mr-3">
                                <div>
                                    <MenuButton
                                        class="flex max-w-xs items-center rounded-full bg-gray-800 text-sm text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                                    >
                                        <span class="sr-only"
                                            >Open user menu</span
                                        >
                                        <img
                                            class="h-8 w-8 rounded-full"
                                            :src="user.imageUrl"
                                            alt=""
                                        />
                                    </MenuButton>
                                </div>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute left-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                    >
                                        <MenuItem
                                            v-for="item in userNavigation"
                                            :key="item.name"
                                            v-slot="{ active }"
                                        >
                                            <InertiaLink
                                                as="button"
                                                method="POST"
                                                :href="item.href"
                                                :class="[
                                                    active ? 'bg-gray-100' : '',
                                                    'block w-full text-start px-4 py-2 text-sm text-gray-700',
                                                ]"
                                            >
                                                {{ item.name }}
                                            </InertiaLink>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-gray-800"
                        >
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon
                                v-if="!open"
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                            <XMarkIcon
                                v-else
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <transition
                enter-active-class="transition duration-100 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-75 ease-out"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
            >
                <DisclosurePanel class="md:hidden">
                    <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                        <InertiaLink
                            v-for="item in navigation"
                            :key="item.name"
                            :href="item.href"
                            :class="[
                                item.current
                                    ? 'bg-gray-900 text-white'
                                    : 'text-gray-300 hover:bg-gray-700 hover:text-white',
                                'block px-3 py-2 rounded-md text-start w-full font-medium',
                            ]"
                            :aria-current="item.current ? 'page' : undefined"
                        >
                            {{ item.name }}
                        </InertiaLink>
                    </div>
                    <div class="border-t border-gray-700 pt-4 pb-3">
                        <div class="flex items-center px-5">
                            <div class="flex-shrink-0">
                                <img
                                    class="h-10 w-10 rounded-full"
                                    :src="user.imageUrl"
                                    alt=""
                                />
                            </div>
                            <div class="mr-3">
                                <div class="text-base font-medium text-white">
                                    {{ user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-400">
                                    {{ user.email }}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 space-y-1 px-2">
                            <InertiaLink
                                v-for="item in userNavigation"
                                :key="item.name"
                                as="Button"
                                method="POST"
                                :href="item.href"
                                class="block w-full rounded-md px-3 py-2 text-start font-medium text-gray-400 hover:bg-gray-700 hover:text-white"
                            >
                                {{ item.name }}
                            </InertiaLink>
                        </div>
                    </div>
                </DisclosurePanel>
            </transition>
        </Disclosure>

        <main>
            <FlashMessages />
            <div class="mx-auto max-w-7xl py-6 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
    </div>
</template>

<script setup>
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { Bars3Icon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import useUser from "@/Composables/useUser.js";
import Logo from "@/Pages/Auth/Logo.vue";
import { Inertia } from "@inertiajs/inertia";
import { ref } from "vue";
import FlashMessages from "@/Shared/FlashMessages.vue";

const user = useUser();

const navigation = ref([
    { name: "الرئيسية", href: "/", current: true },
    { name: "الجدول", href: "/table", current: false },
    { name: "حجز غرفة", href: "/reserve", current: false },
]);

if (user.isAdmin) {
    navigation.value.push(
        {
            name: "الموافقة علي الحجز",
            href: "/reservations/not-approved",
            current: false,
        },
        {
            name: "المستخدمين",
            href: "/users",
            current: false,
        }
    );
}

Inertia.on("navigate", () => {
    navigation.value.forEach((link) => {
        link.current = window.location.pathname === link.href;
    });
});

const userNavigation = [{ name: "تسجيل خروج", href: "/logout" }];
</script>
