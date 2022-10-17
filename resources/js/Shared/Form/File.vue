<template>
    <div class="w-full">
        <file-pond
            v-bind="$attrs"
            :allow-multiple="multiple"
            :label-idle="label"
            :accepted-file-types="accepted"
            :name="name"
            :server="server"
        />
    </div>
</template>

<script setup>
import vueFilePond from "vue-filepond";

import "filepond/dist/filepond.min.css";
import "filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css";

// Import image preview and file type validation plugins
import FilePondPluginFileValidateType from "filepond-plugin-file-validate-type";
import FilePondPluginImagePreview from "filepond-plugin-image-preview";
import FilePondPluginPdfPreview from "filepond-plugin-pdf-preview";

const FilePond = vueFilePond(FilePondPluginFileValidateType);

const props = defineProps({
    label: String,
    multiple: Boolean,
    accepted: String,
    errors: Object,
    server: Object,
    name: String,
});

const label = props.label ?? "Drop files here...";
</script>
