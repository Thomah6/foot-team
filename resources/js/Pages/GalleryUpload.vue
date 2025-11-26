<script setup>
import { ref, computed } from "vue";
import { useForm } from "@inertiajs/vue3";

// Inertia form
const form = useForm({
    images: [],
    description: "",
});

const files = ref([]);
const dragover = ref(false);
const uploading = ref(false);
const uploaded = ref(false);
const progress = ref(0);

const progressOffset = computed(() => {
    const circumference = 2 * Math.PI * 25;
    return circumference - (progress.value / 100) * circumference;
});

function handleFiles(fileList) {
    Array.from(fileList).forEach((file) => {
        if (validateFile(file)) {
            //   const reader = new FileReader()
            //   reader.onload = e => {
            //     const fileObj = {
            //       id: Date.now() + Math.random(),
            //       name: file.name,
            //       size: formatFileSize(file.size),
            //       preview: e.target.result,
            //       status: 'pending'
            //     }
            //     files.value.push(fileObj)
            //     form.images.push(file) // ajout au form Inertia
            //   }
            //   reader.readAsDataURL(file)
            const reader = new FileReader();
            reader.onload = (e) => {
                const fileObj = {
                    id: Date.now() + Math.random(),
                    name: file.name,
                    size: formatFileSize(file.size),
                    preview: e.target.result, // ✅ utilisé par <img>
                    status: "pending",
                };
                files.value.push(fileObj);
                form.images.push(file);
            };
            reader.readAsDataURL(file);
        }
    });
}

function validateFile(file) {
    const allowedTypes = ["image/jpeg", "image/png", "image/gif"];
    const maxFileSize = 10 * 1024 * 1024;
    if (!allowedTypes.includes(file.type)) return false;
    if (file.size > maxFileSize) return false;
    return true;
}

function formatFileSize(bytes) {
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(1024));
    return (bytes / Math.pow(1024, i)).toFixed(2) + " " + sizes[i];
}

function removeFile(id) {
    files.value = files.value.filter((f) => f.id !== id);
    form.images = form.images.filter((f, idx) => files.value[idx]); // synchroniser avec Inertia
}

function submit() {
  uploading.value = true
  form.post(route('galleries.store'), {
    forceFormData: true,
    onProgress: e => progress.value = e.percentage,
    onSuccess: () => {
      uploading.value = false
      uploaded.value = true   // ✅ active l’affichage du cadre
      // ⚠️ ne pas vider files.value tout de suite
      form.reset()
    },
    onError: () => uploading.value = false
  })
}

function startNewUpload() {
    files.value = [];
    form.reset();
    progress.value = 0;
    uploading.value = false;
    uploaded.value = false;
}

function viewUploadedFiles() {
    uploaded.value = false;
}
</script>

<template>
    <div class="container">
        <div class="upload-container">
            <!-- Upload Box -->
            <div
                class="upload-box"
                @click="$refs.fileInput.click()"
                @dragover.prevent="dragover = true"
                @dragleave.prevent="dragover = false"
                @drop.prevent="handleFiles($event.dataTransfer.files)"
                :class="{ dragover, uploading }"
            >
                <div class="upload-content" v-show="!uploading">
                    <div class="upload-icon">
                        <svg
                            width="64"
                            height="64"
                            viewBox="0 0 24 24"
                            fill="none"
                        >
                            <path
                                d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4M17 8l-5-5-5 5M12 3v12"
                                stroke="currentColor"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                    </div>
                    <h3 class="upload-title">
                        Drop files here or click to upload
                    </h3>
                    <p class="upload-subtitle">
                        Support for single or bulk uploads. Strictly PNG, JPG
                        and GIF files only.
                    </p>
                    <button
                        class="upload-button"
                        type="button"
                        @click="$refs.fileInput.click()"
                    >
                        Choose Files
                    </button>
                    <input
                        type="file"
                        ref="fileInput"
                        multiple
                        accept="image/*"
                        hidden
                        @change="handleFiles($event.target.files)"
                    />
                </div>

                <!-- Progress -->
                <div class="upload-progress" v-show="uploading">
                    <div class="progress-circle">
                        <svg class="progress-ring" width="60" height="60">
                            <circle
                                cx="30"
                                cy="30"
                                r="25"
                                stroke="#4a5568"
                                stroke-width="4"
                                fill="none"
                            />
                            <circle
                                class="progress-bar"
                                cx="30"
                                cy="30"
                                r="25"
                                stroke="#f093fb"
                                stroke-width="4"
                                fill="none"
                                stroke-dasharray="157"
                                :stroke-dashoffset="progressOffset"
                            />
                        </svg>
                        <span class="progress-text"
                            >{{ Math.round(progress) }}%</span
                        >
                    </div>
                    <p class="progress-label">Uploading files...</p>
                </div>
            </div>

            <!-- Files Preview -->
            <div
                class="files-preview"
                :class="{ show: files.length }"
            >
                <div class="preview-header">
                    <h4 class="preview-title">
                        {{ uploaded ? "Uploaded Files" : "Selected Files" }}
                    </h4>
                    <button
                        class="add-more-btn"
                        v-show="files.length"
                        @click="$refs.fileInput.click()"
                    >
                        Add More Files
                    </button>
                </div>
                <div class="files-list">
                    <div v-for="file in files" :key="file.id" class="file-item">
                        <img
                            :src="file.preview"
                            :alt="file.name"
                            class="file-preview"
                        />
                        <div class="file-info">
                            <div class="file-name">{{ file.name }}</div>
                            <div class="file-size">{{ file.size }}</div>
                        </div>
                        <div class="file-status">
                            <div
                                class="status-icon"
                                :class="
                                    file.status === 'success'
                                        ? 'status-success'
                                        : 'status-uploading'
                                "
                            >
                                {{ file.status === "success" ? "✓" : "⏳" }}
                            </div>
                        </div>
                        <div class="file-actions">
                            <button
                                class="file-action delete"
                                @click="removeFile(file.id)"
                            >
                                🗑
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Upload Complete -->
            <div class="upload-complete" v-show="uploaded">
                <div class="complete-header">
                    <div class="success-icon">✓</div>
                    <h3 class="complete-title">Upload Successful!</h3>
                    <p class="complete-subtitle">
                        {{ files.length }} file(s) uploaded successfully
                    </p>
                </div>
                <div class="complete-actions">
                    <button class="new-upload-btn" @click="startNewUpload">
                        Start New Upload
                    </button>
                    <button class="view-files-btn" @click="viewUploadedFiles">
                        View Uploaded Files
                    </button>
                </div>
            </div>

            <!-- Champ description -->
            <textarea
                v-model="form.description"
                placeholder="Description (optionnelle)"
                class="mt-6 w-full border rounded p-2 text-black"
            ></textarea>

            <!-- Bouton submit -->
            <div class="mt-6 text-center">
                <button
                    @click="submit"
                    :disabled="form.processing || !files.length"
                    class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 disabled:opacity-50"
                >
                    Envoyer vers Laravel
                </button>
            </div>
        </div>
    </div>
</template>

<style src="resources/css/upload.css">
/* Ton CSS complet collé ici */
</style>
