<script setup>
import { Card } from "@/components/ui/card";
import {
    DELETE_UPLOADED_FILE,
    FETCH_UPLOADED_FILES,
    UPLOAD_FILE,
} from "@/services/store/actions.type";

import { CloudUpload, LoaderCircle, Plus, Trash2, X } from "lucide-vue-next";
import { computed, onMounted, ref } from "vue";
import { useStore } from "vuex";
import Button from "./ui/button/Button.vue";

const props = defineProps({
    initialFiles: {
        type: Array,
        default: () => [],
    },
});

const store = useStore();

const uploadedFiles = computed(() => store.getters["fileUploader/files"]);
const isLoading = computed(() => store.getters["fileUploader/isLoading"]);

const files = computed(() => {
    const normalizedInitialFiles = props.initialFiles.map((file) => ({
        name: file.name,
        url: file.url,
    }));

    const normalizedUploadedFiles = uploadedFiles.value.map((file) => ({
        name: file.name,
        url: file.url,
    }));

    const data = [...normalizedInitialFiles, ...normalizedUploadedFiles];
    return data;
});

function handleFileChange(event) {
    const formData = new FormData();
    formData.append("file", event.target.files[0]);
    uploadFile(formData);
}

function fetchFiles() {
    store.dispatch("fileUploader/" + FETCH_UPLOADED_FILES);
}

function uploadFile(formData) {
    store.dispatch("fileUploader/" + UPLOAD_FILE, formData);
}

function deleteFile(name) {
    store.dispatch("fileUploader/" + DELETE_UPLOADED_FILE, {
        file_name: name,
    });
}

onMounted(() => {
    fetchFiles();
});
</script>

<template>
    <div class="flex flex-wrap items-center gap-2">
        <div v-if="files.length > 0" class="flex flex-wrap items-center gap-2">
            <Card
                v-for="file in files"
                :key="file.id"
                class="relative w-24 h-24 overflow-hidden group"
            >
                <img
                    class="w-full h-full object- cover"
                    :src="file.url"
                    alt=""
                />
                <div
                    class="absolute top-0 left-0 bottom-0 right-0 bg-black bg-opacity-50 hidden group-hover:block cursor-pointer"
                >
                    <div class="flex items-center justify-center w-full h-full">
                        <button
                            type="button"
                            @click="deleteFile(file.name)"
                            class="text-white"
                        >
                            <Trash2 class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </Card>
        </div>

        <label for="file">
            <Card
                class="w-24 h-24 flex flex-col items-center justify-center p-4 cursor-pointer"
            >
                <input
                    @change="handleFileChange"
                    type="file"
                    id="file"
                    hidden
                />
                <LoaderCircle v-if="isLoading" class="w-5 h-5 animate-spin" />
                <CloudUpload v-else class="w-8 h-8 text-primary" />
            </Card>
        </label>
    </div>
</template>
