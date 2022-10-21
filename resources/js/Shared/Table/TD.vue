<template>
    <td
        :class="classInner"
        class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"
    >
        <slot />
    </td>
</template>

<script>
export default {
    name: "TD",
    props: {
        classInner: {
            type: String,
        },
    },
};
</script>

<style scoped></style>
