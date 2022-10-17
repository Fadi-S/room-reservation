<template>
    <nav v-bind="$attrs">
        <template v-for="item in navs">
            <template v-if="item.children">
                <button
                    @click="item.open = !item.open"
                    type="button"
                    class="group flex items-center justify-between px-2 py-4 text-sm w-full font-medium transition-colors hover:bg-primary text-white"
                >
                    <span class="flex items-center">
                        <component
                            :is="item.icon"
                            class="mr-3 flex-shrink-0 h-6 w-6 text-white"
                            aria-hidden="true"
                        />
                        <span>{{ item.name }}</span>
                    </span>

                    <ChevronRightIcon
                        class="mx-3 flex-shrink-0 h-6 w-6 text-white transition-all transform"
                        :class="{ 'rotate-90': item.open }"
                        aria-hidden="true"
                    />
                </button>
                <transition
                    enter-active-class="transform ease-out duration-300 transition"
                    enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                    enter-to-class="translate-y-0 opacity-100 sm:translate-x-0"
                    leave-active-class="transition ease-in duration-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <div v-show="item.open" class="bg-light/90 rounded-lg mx-2 overflow-hidden">
                        <template v-for="child in item.children">
                            <InertiaLink
                                v-if="admin.can(child.permission)"
                                :key="child.name + child.current"
                                :href="child.href"
                                class="group flex items-center px-2 py-4 text-sm font-medium"
                                :class="{
                                    'bg-light text-gray-900': child.current,
                                    'text-gray-800 hover:bg-light hover:text-gray-900':
                                        !child.current,
                                }"
                            >
                                <component
                                    :is="child.icon"
                                    class="mr-3 flex-shrink-0 h-6 w-6"
                                    :class="{
                                        'text-gray-900': child.current,
                                        'text-gray-800 hover:text-gray-900': !child.current,
                                    }"
                                    aria-hidden="true"
                                />
                                {{ child.name }}
                            </InertiaLink>
                        </template>
                    </div>
                </transition>
            </template>

            <InertiaLink
                v-else
                v-if="admin.can(item.permission)"
                :key="item.name + item.current"
                :href="item.href"
                class="group flex items-center px-2 py-4 text-white text-sm font-semibold transition-colors"
                :class="{
                    'bg-primary': item.current,
                    'hover:bg-primary': !item.current,
                }"
            >
                <component
                    :is="item.icon"
                    class="mr-3 flex-shrink-0 text-white h-6 w-6 transition-colors"
                    aria-hidden="true"
                />
                {{ item.name }}
            </InertiaLink>
        </template>
    </nav>
</template>

<script setup>
import {
    AcademicCapIcon,
    CalendarIcon,
    CogIcon,
    HomeIcon,
    UsersIcon,
    ChevronRightIcon,
    AdjustmentsVerticalIcon,
    ClipboardIcon,
    BellIcon,
    StarIcon,
    PencilSquareIcon,
    BookOpenIcon,
    PencilIcon,
    MegaphoneIcon,
} from "@heroicons/vue/24/outline";
import { shallowRef, defineEmits, reactive } from "vue";
import KidIcon from "@/Shared/Icons/KidIcon.vue";
import useAdmin from "@/Models/Admin.js";
import { Inertia } from "@inertiajs/inertia";

const admin = useAdmin();
const navs = reactive([
    { name: "Dashboard", href: "/", icon: HomeIcon },
    {
        name: "Exams",
        icon: PencilSquareIcon,
        open: false,
        permission: "View Exams",
        children: [
            {
                name: "Exams",
                href: "/exams",
                permission: "View Exams",
                icon: PencilIcon,
            },
            {
                name: "Certificates",
                href: "/certificates",
                permission: "View Exams",
                icon: AcademicCapIcon,
            },
        ],
    },
    {
        name: "Send Notification",
        href: "/notifications",
        icon: MegaphoneIcon,
        permission: "Send Notifications",
    },
    {
        name: "Classes",
        href: "/classes",
        icon: BellIcon,
        permission: "View Classes",
    },
    {
        name: "School Years",
        href: "/school-years",
        icon: StarIcon,
        permission: "View School Years",
    },
    {
        name: "Students",
        href: "/users",
        icon: AcademicCapIcon,
        permission: "View Students",
    },
    {
        name: "Kindergarten",
        href: "/registrations",
        icon: shallowRef(KidIcon),
        permission: "View Students",
    },
    {
        name: "Admins",
        href: "/admins",
        icon: UsersIcon,
        permission: "View Admins",
    },
    {
        name: "Settings",
        icon: CogIcon,
        open: false,
        children: [
            {
                name: "Years",
                href: "/years",
                icon: CalendarIcon,
                permission: "View Years",
            },
            {
                name: "Subjects",
                href: "/subjects",
                icon: BookOpenIcon,
                permission: "View Subjects",
            },
            {
                name: "Roles",
                href: "/roles",
                icon: AdjustmentsVerticalIcon,
                permission: "View Roles",
            },
            {
                name: "Preferences",
                href: "/settings",
                icon: CogIcon,
                permission: "View Settings",
            },
            {
                name: "Sections",
                href: "/sections",
                icon: ClipboardIcon,
                permission: "View Sections",
            },
        ],
    },
]);

const emit = defineEmits(["navs"]);

Inertia.on("navigate", () => {
    navs.forEach((link) => {
        if (link.children) {
            link.children.forEach((child) => {
                child.current = window.location.pathname === child.href;
                if (child.current) {
                    link.open = true;
                }
            });

            return;
        }

        link.current = window.location.pathname === link.href;
    });

    emit("navs");
});
</script>
