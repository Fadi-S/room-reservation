<template>
    <Head title="المستخدمين" />

    <div class="bg-white p-6 rounded-lg shadow max-w-7xl mx-auto">
        <Table create-label="أضف مستخدم" :items="users">
            <template #header>
                <TH empty>ID</TH>
                <TH>الإسم</TH>
                <TH>اسم المستخدم</TH>
                <TH>الأيميل</TH>
                <TH>أدمن</TH>
                <TH>الخدمة</TH>
                <TH>Login Link</TH>
                <TH empty>تعديل</TH>
            </template>

            <tr class="odd:bg-white bg-gray-50" v-for="user in users.data">
                <TD>#{{ user.id }}</TD>
                <TD>{{ user.name }}</TD>
                <TD>{{ user.username }}</TD>
                <TD>{{ user.email }}</TD>
                <TD>
                    <CheckCircleIcon
                        v-if="user.isAdmin"
                        class="w-6 h-6 text-green-600"
                    />
                </TD>
                <TD>
                    <ul>
                        <li v-for="service in user.services">
                            {{ service.name }}
                        </li>
                    </ul>
                </TD>
                <TD>
                    <div
                        v-if="!user.isAdmin"
                        class="flex items-center justify-center"
                    >
                        <Button
                            color="red"
                            outline
                            padding="p-2"
                            type="button"
                            width="w-auto"
                            rounded="rounded-full"
                            @click="addLinkToClipboard(user)"
                        >
                            <LockClosedIcon class="w-6 h-6" />
                        </Button>
                    </div>
                </TD>
                <TD>
                    <Link
                        color="blue"
                        padding="p-2"
                        rounded="rounded-full"
                        outline
                        :href="user.links.edit"
                    >
                        <PencilIcon class="w-6 h-6" />
                    </Link>
                </TD>
            </tr>
        </Table>
    </div>
</template>

<script setup>
import { ref } from "vue";
import Table from "@/Shared/Table/Table.vue";
import TH from "@/Shared/Table/TH.vue";
import TD from "@/Shared/Table/TD.vue";
import Link from "@/Shared/Link.vue";
import {
    CheckCircleIcon,
    PencilIcon,
    LockClosedIcon,
} from "@heroicons/vue/24/solid";
import Button from "@/Shared/Form/Button.vue";
import flash from "@/Composables/useFlash.js";

defineProps({
    users: Object,
});

const search = ref("");

async function getLink(user) {
    let data = await fetch("/users/" + user.key + "/link");
    return (await data.json())["link"];
}

async function addLinkToClipboard(user) {
    const link = await getLink(user);

    navigator.permissions
        .query({ name: "clipboard-write" })
        .then(async (result) => {
            if (result.state === "granted" || result.state === "prompt") {
                navigator.clipboard.writeText(link).then(
                    function () {
                        flash.clipboard(
                            "Clipboard",
                            "تم نسخ رابط " + user.name + " بنجاح"
                        );
                    },
                    function (err) {
                        console.error("Async: Could not copy text: ", err);
                        flash.error("Error", "Copy permission denied");
                    }
                );
            } else {
                flash.error("Error", "Copy permission denied");
            }
        });
}
</script>
