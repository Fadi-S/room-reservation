<template>
    {{ user.name }}
</template>

<script setup>
import useUser from "@/Composables/useUser.js";

let user = useUser();
</script>
