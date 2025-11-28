<template>
    <div class="container">
        <div class="upload-container">
            <!-- Upload Box -->
            <div
                class="upload-box"
                :class="{ dragover: isDragging, uploading: isUploading }"
                @click="triggerFileInput"
                @dragover="handleDragOver"
                @dragleave="handleDragLeave"
                @drop="handleDrop"
            >
                <!-- Upload Content -->
                <div class="upload-content" v-show="!isUploading">
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
                        @click.stop="triggerFileInput"
                    >
                        Choose Files
                    </button>
                    <input
                        type="file"
                        ref="fileInput"
                        multiple
                        accept="image/*"
                        hidden
                        @change="handleFileInputChange"
                    />
                </div>

                <!-- Upload Progress -->
                <div class="upload-progress" v-show="isUploading">
                    <div class="progress-circle">
                        <svg class="progress-ring" width="60" height="60">
                            <circle
                                cx="30"
                                cy="30"
                                r="25"
                                stroke="currentColor"
                                stroke-width="4"
                                fill="none"
                            />
                            <circle
                                class="progress-bar"
                                cx="30"
                                cy="30"
                                r="25"
                                :stroke="primaryColor"
                                stroke-width="4"
                                fill="none"
                                stroke-dasharray="157"
                                :stroke-dashoffset="progressOffset"
                            />
                        </svg>
                        <span class="progress-text">{{ uploadProgress }}%</span>
                    </div>
                    <p class="progress-label">Uploading files...</p>
                </div>
            </div>

            <!-- Files Preview -->
            <div
                class="files-preview"
                :class="{ show: files.length > 0 && !showUploadComplete }"
            >
                <div class="preview-header">
                    <h4 class="preview-title">Selected Files</h4>
                    <button
                        class="add-more-btn"
                        v-show="files.length > 0 && !isUploading"
                        @click="triggerFileInput"
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
                            <div class="file-size">
                                {{ file.sizeFormatted }}
                            </div>
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
            <div class="upload-complete" v-show="showUploadComplete">
                <div class="complete-header">
                    <div class="success-icon">✓</div>
                    <h3 class="complete-title">Upload Successful!</h3>
                    <p class="complete-subtitle">{{ uploadCompleteMessage }}</p>
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

            <!-- Liste des fichiers -->
            <!-- <GalleryList
                v-if="files.length > 0"
                :files="files"
                @remove="removeFile"
            /> -->
        </div>
    </div>
</template>

<script>
import { useForm } from "@inertiajs/vue3";
// import GalleryList from "./GalleryList.vue";

export default {
    name: "FileUpload",
    // components: { GalleryList },
    props: {
        galleries: {
            type: Array,
            default: () => [],
        },
    },
    data() {
        return {
            files: [],
            isDragging: false,
            isUploading: false,
            uploadProgress: 0,
            showUploadComplete: false,
            maxFileSize: 10 * 1024 * 1024, // 10MB
            allowedTypes: ["image/jpeg", "image/png", "image/gif"],
            primaryColor: "#13ec6d",
            form: useForm({
                images: [],
                description: "",
            }),
        };
    },
   /* mounted() {
        // Charger les fichiers existants dans this.files
        this.files = this.galleries.map((g) => ({
            id: g.id,
            name: g.image_path.split("/").pop(),
            size: 0, // tu peux ignorer ou calculer si besoin
            sizeFormatted: "",
            preview: "/storage/" + g.image_path, // chemin public
            serverId: g.id,
            status: "success",
        }));
    },
    */
    computed: {
        progressOffset() {
            const circumference = 157;
            return circumference - (this.uploadProgress / 100) * circumference;
        },
        uploadCompleteMessage() {
            const count = this.files.length;
            return `${count} file${
                count !== 1 ? "s" : ""
            } uploaded successfully`;
        },
    },
    methods: {
        triggerFileInput() {
            this.$refs.fileInput.click();
        },
        handleFileInputChange(event) {
            this.handleFiles(event.target.files);
        },
        handleDragOver(event) {
            event.preventDefault();
            this.isDragging = true;
        },
        handleDragLeave(event) {
            event.preventDefault();
            this.isDragging = false;
        },
        handleDrop(event) {
            event.preventDefault();
            this.isDragging = false;
            this.handleFiles(event.dataTransfer.files);
        },
        async handleFiles(fileList) {
            const newFiles = Array.from(fileList);

            // Simulation d'upload pour l'UI seulement
            if (newFiles.length > 0) {
                this.simulateUploadForUI(newFiles);
            }

            // Ajout réel des fichiers au form Inertia
            for (const file of newFiles) {
                if (this.validateFile(file)) {
                    await this.addFile(file);
                }
            }
        },
        validateFile(file) {
            if (!this.allowedTypes.includes(file.type)) {
                this.showError(
                    `${file.name}: Only JPG, PNG, and GIF files are allowed.`
                );
                return false;
            }
            if (file.size > this.maxFileSize) {
                this.showError(
                    `${file.name}: File size must be less than 10MB.`
                );
                return false;
            }
            return true;
        },
        async addFile(file) {
            const fileObj = {
                id: Date.now() + Math.random(),
                name: file.name,
                size: file.size,
                sizeFormatted: this.formatFileSize(file.size),
                file: file,
                status: "pending",
                preview: await this.generatePreview(file),
            };
            this.files.push(fileObj);
            this.form.images.push(file); // ✅ ajout au form Inertia
        },
        generatePreview(file) {
            return new Promise((resolve) => {
                const reader = new FileReader();
                reader.onload = (e) => resolve(e.target.result);
                reader.readAsDataURL(file);
            });
        },
        // Simulation d'upload pour l'interface utilisateur
        simulateUploadForUI(newFiles) {
            this.isUploading = true;
            this.uploadProgress = 0;
            this.showUploadComplete = false;

            let completedFiles = 0;
            const totalFiles = newFiles.length;

            newFiles.forEach((file, index) => {
                setTimeout(() => {
                    this.simulateFileUpload(file, (progress) => {
                        const overallProgress =
                            ((completedFiles + progress / 100) / totalFiles) *
                            100;
                        this.uploadProgress = Math.round(overallProgress);

                        if (progress === 100) {
                            completedFiles++;
                            // Mettre à jour le statut du fichier dans l'UI
                            const fileObj = this.files.find(
                                (f) =>
                                    f.name === file.name && f.size === file.size
                            );
                            if (fileObj) {
                                fileObj.status = "success";
                            }

                            if (completedFiles === totalFiles) {
                                setTimeout(() => {
                                    this.isUploading = false;
                                    // Ne pas montrer le écran de complétion ici car on veut que l'utilisateur puisse soumettre
                                }, 500);
                            }
                        }
                    });
                }, index * 500);
            });
        },
        simulateFileUpload(file, progressCallback) {
            let progress = 0;

            const uploadInterval = setInterval(() => {
                progress += Math.random() * 20;
                if (progress >= 100) {
                    progress = 100;
                    clearInterval(uploadInterval);
                }

                progressCallback(progress);
            }, 100 + Math.random() * 200);
        },
        /*submit() {
            console.log("➡️ Bouton cliqué, submit() déclenché");

            // Vérifier les fichiers ajoutés
            console.log("📂 Fichiers présents dans this.files :", this.files);
            console.log(
                "📂 Fichiers présents dans form.images :",
                this.form.images
            );

            // Vérifier la route appelée
            console.log("📡 Route appelée :", route("galleries.store"));

            // Réinitialiser l'état d'upload pour l'UI
            this.isUploading = true;
            this.uploadProgress = 0;

            this.form.post(route("galleries.store"), {
                forceFormData: true,
                onProgress: (event) => {
                    console.log("⏳ Progression :", event.percentage + "%");
                    this.uploadProgress = event.percentage;
                },
                onSuccess: (page) => {
                    console.log("✅ Upload terminé avec succès");
                    console.log("📦 Contenu du flash :", page.props.flash);
                    const uploaded =
                        page.props.flash && page.props.flash.uploaded
                            ? page.props.flash.uploaded
                            : [];

                    console.log("📦 IDs renvoyés :", uploaded);
                    uploaded.forEach((item, index) => {
                        if (this.files[index]) {
                            this.files[index].serverId = item.id;
                        }
                    });
                    this.isUploading = false;
                    this.showUploadComplete = true;
                    this.form.reset();
                },
                onError: (errors) => {
                    console.error("❌ Erreurs reçues :", errors);
                    this.isUploading = false;
                },
            });
        },
        */

        submit() {
            this.form.post(route("galleries.store"), {
                forceFormData: true,
                onSuccess: (page) => {
                    console.log("✅ Upload terminé avec succès");
                    console.log("📦 IDs renvoyés :", page.props.uploaded);

                    const uploaded = page.props.uploaded || [];
                    uploaded.forEach((item, index) => {
                        if (this.files[index]) {
                            this.files[index].serverId = item.id;
                        }
                    });

                    this.isUploading = false;
                    this.showUploadComplete = true;
                    this.form.reset();
                },
                onError: (errors) => {
                    console.error("❌ Erreurs reçues :", errors);
                    this.isUploading = false;
                },
            });
        },
        startNewUpload() {
            this.files = [];
            this.uploadProgress = 0;
            this.showUploadComplete = false;
            this.isUploading = false;
            this.form.reset();
            this.$refs.fileInput.value = "";
        },
        viewUploadedFiles() {
            this.showUploadComplete = false;
        },
        formatFileSize(bytes) {
            if (bytes === 0) return "0 Bytes";
            const k = 1024;
            const sizes = ["Bytes", "KB", "MB", "GB"];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return (
                parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i]
            );
        },
        showError(message) {
            const errorDiv = document.createElement("div");
            errorDiv.className = "error-notification";
            errorDiv.style.animation = "slideInRight 0.3s ease";
            errorDiv.textContent = message;
            document.body.appendChild(errorDiv);
            setTimeout(() => {
                errorDiv.style.animation = "slideOutRight 0.3s ease";
                setTimeout(() => errorDiv.remove(), 300);
            }, 4000);
        },

        // removeFile(fileId) {
        //     const fileObj = this.files.find((f) => f.id === fileId);

        //     if (!fileObj) return;

        //     if (!fileObj.serverId) {
        //         console.warn(
        //             "⚠️ Pas d'ID serveur pour ce fichier, suppression locale uniquement"
        //         );
        //         this.files = this.files.filter((f) => f.id !== fileId);
        //         return;
        //     }

        //     // 🔎 Suppression côté backend
        //     this.form.delete(route("galleries.destroy", fileObj.serverId), {
        //         onSuccess: () => {
        //             console.log("✅ Fichier supprimé côté backend");
        //             this.files = this.files.filter((f) => f.id !== fileId);
        //         },
        //         onError: (errors) => {
        //             console.error("❌ Erreur lors de la suppression :", errors);
        //         },
        //     });
        // },

        removeFile(fileId) {
            // On cherche le fichier dans la liste locale
            const fileObj = this.files.find((f) => f.id === fileId);
            if (!fileObj) return;

            // Si le fichier n’a pas encore d’ID serveur → suppression locale seulement
            if (!fileObj.serverId) {
                console.warn(
                    "⚠️ Pas d'ID serveur pour ce fichier, suppression locale uniquement"
                );
                this.files = this.files.filter((f) => f.id !== fileId);
                return;
            }

            // 🔎 Suppression côté backend avec l’ID serveur
            this.form.delete(route("galleries.destroy", fileObj.serverId), {
                onSuccess: () => {
                    console.log("✅ Fichier supprimé côté backend");
                    // Supprimer aussi côté UI
                    this.files = this.files.filter((f) => f.id !== fileId);
                },
                onError: (errors) => {
                    console.error("❌ Erreur lors de la suppression :", errors);
                },
            });
        },
    },
};
</script>

<style scoped>
/* Les styles restent exactement les mêmes que dans ta version précédente */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: #102218; /* background-dark */
    min-height: 100vh;
    padding: 2rem;
    color: #f6f8f7; /* text-dark */
}

.container {
    max-width: 800px;
    margin: 50px auto;
}

.upload-container {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

/* Upload Box */
.upload-box {
    position: relative;
    padding: 6rem;
    text-align: center;
    border: 3px dashed #2a3d33; /* border-dark */
    border-radius: 20px;
    transition: all 0.3s ease;
    background: #182c21; /* surface-dark */
    cursor: pointer;
}

.upload-box.dragover {
    border-color: #13ec6d; /* primary */
    background: #182c21;
    transform: scale(1.02);
}

.upload-box.uploading {
    border-color: #13ec6d;
    background: #182c21;
}

.upload-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
    color: #13ec6d;
}

.upload-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: #f6f8f7;
}

.upload-subtitle {
    color: #a0b5a9;
    margin-bottom: 2rem;
    font-size: 0.95rem;
}

.upload-button {
    background: #13ec6d;
    color: #102218;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(19, 236, 109, 0.3);
}

.upload-button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(19, 236, 109, 0.4);
}

/* Progress */
.upload-progress {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    text-align: center;
}

.progress-circle {
    position: relative;
    display: inline-block;
    margin-bottom: 1rem;
}

.progress-ring {
    transform: rotate(-90deg);
}

.progress-bar {
    transition: stroke-dashoffset 0.3s ease;
    stroke-linecap: round;
}

.progress-text {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    font-weight: 600;
    font-size: 1rem;
    color: #f6f8f7;
}

.progress-label {
    color: #a0b5a9;
    font-size: 0.9rem;
}

/* Files Preview */
.files-preview {
    background: #182c21;
    border: 1px solid #2a3d33;
    border-radius: 20px;
    padding: 2rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.4s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.files-preview.show {
    opacity: 1;
    visibility: visible;
    transform: translateY(0);
}

.preview-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.preview-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #f6f8f7;
}

.add-more-btn {
    background: rgba(19, 236, 109, 0.1);
    color: #13ec6d;
    border: 1px solid #13ec6d;
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-more-btn:hover {
    background: rgba(19, 236, 109, 0.2);
}

.files-list {
    display: grid;
    gap: 1rem;
}

.file-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid #2a3d33;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.file-item:hover {
    background: rgba(255, 255, 255, 0.1);
    transform: translateY(-2px);
}

.file-preview {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.1);
}

.file-info {
    flex: 1;
}

.file-name {
    font-weight: 500;
    color: #f6f8f7;
    margin-bottom: 0.25rem;
}

.file-size {
    font-size: 0.85rem;
    color: #a0b5a9;
}

.file-status {
    margin-right: 1rem;
}

.status-icon {
    font-size: 1.5rem;
}

.status-uploading {
    color: #13ec6d;
}

.status-success {
    color: #13ec6d;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

.file-action {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid #2a3d33;
    color: #f6f8f7;
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-action:hover {
    background: rgba(255, 255, 255, 0.2);
}

.file-action.delete:hover {
    background: rgba(220, 38, 38, 0.2);
    border-color: #dc2626;
}

/* Upload Complete */
.upload-complete {
    background: #182c21;
    border: 1px solid #2a3d33;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
}

.complete-header {
    margin-bottom: 2rem;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: #13ec6d;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #102218;
    margin: 0 auto 1.5rem;
    animation: successBounce 0.6s ease;
}

@keyframes successBounce {
    0% {
        transform: scale(0);
    }
    50% {
        transform: scale(1.1);
    }
    100% {
        transform: scale(1);
    }
}

.complete-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #f6f8f7;
    margin-bottom: 0.5rem;
}

.complete-subtitle {
    color: #a0b5a9;
    font-size: 1rem;
}

.complete-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.new-upload-btn,
.view-files-btn {
    padding: 1rem 1.5rem;
    border: none;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.3s ease;
}

.new-upload-btn {
    background: #13ec6d;
    color: #102218;
    box-shadow: 0 4px 15px rgba(19, 236, 109, 0.3);
}

.new-upload-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(19, 236, 109, 0.4);
}

.view-files-btn {
    background: rgba(255, 255, 255, 0.1);
    color: #f6f8f7;
    border: 1px solid #2a3d33;
}

.view-files-btn:hover {
    background: rgba(255, 255, 255, 0.2);
}

/* Error States */
.file-item.error {
    border: 1px solid #fecaca;
    background: #fef2f2;
}

.error-message {
    color: #dc2626;
    font-size: 0.75rem;
    margin-top: 0.25rem;
}

/* Responsive Design */
@media (max-width: 640px) {
    body {
        padding: 1rem;
    }

    .upload-box {
        padding: 2rem 1rem;
        margin: 1rem;
    }

    .upload-title {
        font-size: 1.25rem;
    }

    .files-preview {
        padding: 1rem;
    }

    .file-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }

    .file-preview {
        margin-right: 0;
    }

    .file-actions {
        justify-content: center;
    }

    .complete-actions {
        flex-direction: column;
    }
}

/* Error notifications */
.error-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: rgba(254, 226, 226, 0.95);
    color: #dc2626;
    padding: 1rem;
    border-radius: 12px;
    border: 1px solid rgba(252, 165, 165, 0.5);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    backdrop-filter: blur(10px);
    max-width: 300px;
}

@keyframes slideInRight {
    from {
        transform: translateX(100%);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOutRight {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(100%);
        opacity: 0;
    }
}

@keyframes slideOut {
    from {
        opacity: 1;
        transform: translateY(0);
    }
    to {
        opacity: 0;
        transform: translateY(-20px);
    }
}
</style>
