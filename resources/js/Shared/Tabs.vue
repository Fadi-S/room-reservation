<template>
    <div class="max-w-xs mx-auto md:mx-0">
        <DatePicker
            v-bind="$attrs"
            :config="{
                altInput: true,
                altFormat: 'l j M, Y',
                disableMobile: false,
                minDate: 'today',
            }"
        />
    </div>
</template>

<script setup>
import DatePicker from "@/Shared/Form/DatePicker.vue";
</script>
