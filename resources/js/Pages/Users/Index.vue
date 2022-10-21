<template>
    <Head title="المستخدمين" />

    <div class="bg-white p-6 rounded-lg shadow">
        <Table create-label="أضف مستخدم" :items="users">
            <template #header>
                <TH>ID</TH>
                <TH>Name</TH>
                <TH>Username</TH>
                <TH>Email</TH>
                <TH>Services</TH>
                <TH empty>Edit</TH>
            </template>

            <tr v-for="user in users.data">
                <TD>#{{ user.id }}</TD>
                <TD>{{ user.name }}</TD>
                <TD>{{ user.username }}</TD>
                <TD>{{ user.email }}</TD>
                <TD>
                    <ul>
                        <li v-for="service in user.services">
                            {{ service.name }}
                        </li>
                    </ul>
                </TD>
                <TD>
                    <Link color="blue" outline :href="user.links.edit">
                        Edit
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

defineProps({
    users: Array,
});

const search = ref("");
</script>
