<template>
    <div :class="['container', embedded ? 'embedded' : '']">
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
                :class="{ show: files.length > 0 }"
            >
                <div class="preview-header">
                    <h4 class="preview-title">
                        {{ showUploadComplete ? 'Uploaded Files' : 'Selected Files' }}
                    </h4>
                    <button
                        class="add-more-btn"
                        v-show="!showUploadComplete && !isUploading"
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
                            <div v-if="file.uploadStatus" class="file-upload-status">
                                {{ file.uploadStatus }}
                            </div>
                        </div>
                        <div class="file-status">
                            <div
                                class="status-icon"
                                :class="getStatusClass(file)"
                            >
                                {{ getStatusIcon(file) }}
                            </div>
                        </div>
                        <div class="file-actions">
                            <button
                                class="file-action delete"
                                @click="removeFile(file.id)"
                                :disabled="isUploading"
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

            <!-- Champ description amélioré -->
            <div class="description-container" v-if="files.length > 0">
                <label class="description-label">Image Description (Optional)</label>
                <textarea
                    v-model="form.description"
                    placeholder="Add a description for your images..."
                    class="description-textarea"
                    :disabled="isUploading"
                    rows="4"
                ></textarea>
                <div class="character-count" :class="{ 'near-limit': form.description.length > 180 }">
                    {{ form.description.length }}/200
                </div>
            </div>

            <!-- Bouton submit amélioré -->
            <div class="submit-container" v-if="files.length > 0">
                <button
                    @click="submit"
                    :disabled="form.processing || !files.length || isUploading"
                    class="submit-button"
                    :class="{
                        'processing': form.processing,
                        'disabled': !files.length || isUploading
                    }"
                >
                    <span class="button-content">
                        <span class="button-icon" v-if="!form.processing"></span>
                        <span class="button-icon loading" v-else>⏳</span>
                        <span class="button-text">
                            {{ form.processing ? 'Uploading to Server...' : 'Send to Gallery' }}
                        </span>
                    </span>
                    <div class="button-shine"></div>
                </button>
                <p class="submit-hint" v-if="!form.processing">
                    Ready to upload {{ files.length }} file{{ files.length !== 1 ? 's' : '' }}
                </p>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm, router } from "@inertiajs/vue3";

export default {
    name: "FileUpload",
    props: {
        embedded: {
            type: Boolean,
            default: false,
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
            primaryColor: "#84cc16", // Couleur citron du thème
            form: useForm({
                images: [],
                description: "",
            }),
        };
    },
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
            if (newFiles.length > 0 && !this.isUploading) {
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
                status: "pending", // Pour la simulation UI
                uploadStatus: "Ready to upload", // Pour l'upload réel
                preview: await this.generatePreview(file),
            };
            this.files.push(fileObj);
            this.form.images.push(file);
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
                        const overallProgress = ((completedFiles + progress / 100) / totalFiles) * 100;
                        this.uploadProgress = Math.round(overallProgress);

                        if (progress === 100) {
                            completedFiles++;
                            // Mettre à jour le statut du fichier dans l'UI
                            const fileObj = this.files.find(f => f.name === file.name && f.size === file.size);
                            if (fileObj) {
                                fileObj.status = 'success';
                                fileObj.uploadStatus = 'Simulation complete';
                            }

                            if (completedFiles === totalFiles) {
                                setTimeout(() => {
                                    this.isUploading = false;
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
                        // Redirect to galleries after 2 seconds
                        setTimeout(() => {
                            router.visit(route('galleries.index'));
                        }, 2000);
                },
                onError: (errors) => {
                    console.error("❌ Erreurs reçues :", errors);
                    this.isUploading = false;
                },
            });
        },
        startNewUpload() {
            // Cette méthode permet de recommencer complètement
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
        removeFile(fileId) {
            // On cherche le fichier dans la liste locale
            const fileObj = this.files.find((f) => f.id === fileId);
            if (!fileObj) return;

            // Si le fichier n'a pas encore d'ID serveur → suppression locale seulement
            if (!fileObj.serverId) {
                console.warn(
                    "⚠️ Pas d'ID serveur pour ce fichier, suppression locale uniquement"
                );
                this.files = this.files.filter((f) => f.id !== fileId);
                return;
            }

            // 🔎 Suppression côté backend avec l'ID serveur
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
        getStatusClass(file) {
            switch (file.status) {
                case 'success': return 'status-success';
                case 'error': return 'status-error';
                case 'uploading': return 'status-uploading';
                default: return 'status-pending';
            }
        },
        getStatusIcon(file) {
            switch (file.status) {
                case 'success': return '✓';
                case 'error': return '❌';
                case 'uploading': return '⏳';
                default: return '⏳';
            }
        }
    },
};
</script>

<style scoped>
/* Variables pour le thème light/dark */
:root {
    --bg-primary: #f9fafb;
    --bg-secondary: #ffffff;
    --bg-tertiary: #f3f4f6;
    --border-color: #e5e7eb;
    --text-primary: #111827;
    --text-secondary: #6b7280;
    --text-tertiary: #9ca3af;
    --citron-500: #84cc16;
    --citron-600: #65a30d;
    --citron-50: #f7fee7;
    --citron-100: #ecfccb;
    --success-bg: #dcfce7;
    --error-bg: #fee2e2;
}

.dark {
    --bg-primary: #111827;
    --bg-secondary: #1f2937;
    --bg-tertiary: #374151;
    --border-color: #4b5563;
    --text-primary: #f9fafb;
    --text-secondary: #d1d5db;
    --text-tertiary: #9ca3af;
    --citron-500: #a3e635;
    --citron-600: #84cc16;
    --citron-50: #365314;
    --citron-100: #3f6212;
    --success-bg: #064e3b;
    --error-bg: #7f1d1d;
}

/* Styles pour le conteneur de description */
.description-container {
    margin-top: 2rem;
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 16px;
    padding: 1.5rem;
    transition: all 0.3s ease;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Embedded mode (when displayed side-by-side in a layout) */
.container.embedded {
    max-width: none;
    margin: 0;
    padding: 0;
}

.container.embedded .upload-container {
    padding: 0;
    margin: 0;
}

.description-container:focus-within {
    border-color: var(--citron-500);
    box-shadow: 0 0 0 3px rgba(132, 204, 22, 0.1), 0 8px 32px rgba(132, 204, 22, 0.15);
    transform: translateY(-2px);
}

.description-label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.75rem;
    font-size: 1rem;
}

.description-textarea {
    width: 100%;
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    padding: 1rem 1.25rem;
    color: var(--text-primary);
    font-family: inherit;
    font-size: 0.95rem;
    resize: vertical;
    transition: all 0.3s ease;
    outline: none;
    min-height: 120px;
    line-height: 1.5;
}

.description-textarea::placeholder {
    color: var(--text-tertiary);
    opacity: 0.7;
}

.description-textarea:focus {
    border-color: var(--citron-500);
    box-shadow: 0 0 0 4px rgba(132, 204, 22, 0.15), 0 0 30px rgba(132, 204, 22, 0.1);
    background: var(--bg-secondary);
}

.description-textarea:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    border-color: var(--border-color);
    box-shadow: none;
}

.character-count {
    text-align: right;
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 0.5rem;
    transition: color 0.3s ease;
}

.character-count.near-limit {
    color: #f59e0b;
    font-weight: 600;
}

/* Styles pour le conteneur du bouton submit */
.submit-container {
    margin-top: 2rem;
    text-align: center;
}

.submit-button {
    position: relative;
    background: linear-gradient(135deg, var(--citron-500) 0%, var(--citron-600) 100%);
    color: white;
    border: none;
    border-radius: 16px;
    padding: 1.25rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    overflow: hidden;
    box-shadow: 0 8px 30px rgba(132, 204, 22, 0.3),
                0 4px 15px rgba(132, 204, 22, 0.2),
                inset 0 1px 0 rgba(255, 255, 255, 0.2);
    min-width: 220px;
}

.submit-button::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.4),
                transparent);
    transition: left 0.6s ease;
}

.submit-button:hover:not(.disabled):not(.processing)::before {
    left: 100%;
}

.submit-button:hover:not(.disabled):not(.processing) {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 12px 40px rgba(132, 204, 22, 0.4),
                0 6px 20px rgba(132, 204, 22, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.3);
}

.submit-button:active:not(.disabled):not(.processing) {
    transform: translateY(-1px) scale(1.01);
    box-shadow: 0 6px 25px rgba(132, 204, 22, 0.3),
                0 3px 12px rgba(132, 204, 22, 0.2);
}

.submit-button:focus:not(.disabled):not(.processing) {
    outline: none;
    box-shadow: 0 0 0 4px rgba(132, 204, 22, 0.3),
                0 12px 40px rgba(132, 204, 22, 0.4);
}

.submit-button.processing {
    background: linear-gradient(135deg, #65a30d 0%, #4d7c0f 100%);
    cursor: progress;
    transform: none;
    box-shadow: 0 4px 20px rgba(101, 163, 13, 0.3);
}

.submit-button.disabled {
    background: linear-gradient(135deg, var(--bg-tertiary) 0%, var(--border-color) 100%);
    color: var(--text-tertiary);
    cursor: not-allowed;
    transform: none;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.button-content {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    position: relative;
    z-index: 2;
}

.button-icon {
    font-size: 1.3rem;
    transition: transform 0.3s ease;
}

.button-icon.loading {
    animation: spin 1.5s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

.submit-button:hover:not(.disabled):not(.processing) .button-icon {
    transform: scale(1.1) translateX(2px);
}

.button-text {
    letter-spacing: 0.5px;
}

.button-shine {
    position: absolute;
    top: 0;
    left: -100%;
    width: 50%;
    height: 100%;
    background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.6),
                transparent);
    transform: skewX(-25deg);
    transition: left 0.8s ease;
}

.submit-button:hover:not(.disabled):not(.processing) .button-shine {
    left: 150%;
}

.submit-hint {
    margin-top: 1rem;
    color: var(--text-secondary);
    font-size: 0.9rem;
    font-weight: 500;
}

/* Styles existants conservés */
.status-success {
    color: var(--citron-500);
}

.status-error {
    color: #dc2626;
}

.status-uploading {
    color: #f59e0b;
}

.status-pending {
    color: var(--text-tertiary);
}

.file-upload-status {
    font-size: 0.75rem;
    color: var(--text-tertiary);
    margin-top: 0.25rem;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    background: var(--bg-primary);
    min-height: 100vh;
    padding: 2rem;
    color: var(--text-primary);
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

.upload-box {
    position: relative;
    padding: 6rem;
    text-align: center;
    border: 3px dashed var(--border-color);
    border-radius: 20px;
    transition: all 0.3s ease;
    background: var(--bg-secondary);
    cursor: pointer;
}

.upload-box.dragover {
    border-color: var(--citron-500);
    background: var(--citron-50);
    transform: scale(1.02);
}

.upload-box.uploading {
    border-color: var(--citron-500);
    background: var(--bg-secondary);
}

.upload-icon {
    display: flex;
    justify-content: center;
    margin-bottom: 1.5rem;
    color: var(--citron-500);
}

.upload-title {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    color: var(--text-primary);
}

.upload-subtitle {
    color: var(--text-secondary);
    margin-bottom: 2rem;
    font-size: 0.95rem;
}

.upload-button {
    background: var(--citron-500);
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(132, 204, 22, 0.3);
}

.upload-button:hover {
    background: var(--citron-600);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(132, 204, 22, 0.4);
}

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
    color: var(--text-primary);
}

.progress-label {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.files-preview {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 2rem;
    opacity: 0;
    visibility: hidden;
    transform: translateY(20px);
    transition: all 0.4s ease;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
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
    color: var(--text-primary);
}

.add-more-btn {
    background: rgba(132, 204, 22, 0.1);
    color: var(--citron-500);
    border: 2px solid var(--citron-500);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    font-size: 0.85rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.add-more-btn:hover {
    background: rgba(132, 204, 22, 0.2);
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
    background: var(--bg-primary);
    border: 2px solid var(--border-color);
    border-radius: 12px;
    transition: all 0.3s ease;
}

.file-item:hover {
    background: var(--bg-tertiary);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
}

.file-preview {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    background: var(--bg-tertiary);
}

.file-info {
    flex: 1;
}

.file-name {
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.file-size {
    font-size: 0.85rem;
    color: var(--text-secondary);
}

.file-status {
    margin-right: 1rem;
}

.status-icon {
    font-size: 1.5rem;
}

.file-actions {
    display: flex;
    gap: 0.5rem;
}

.file-action {
    background: var(--bg-tertiary);
    border: 2px solid var(--border-color);
    color: var(--text-primary);
    width: 32px;
    height: 32px;
    border-radius: 6px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
}

.file-action:hover:not(:disabled) {
    background: var(--border-color);
}

.file-action:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.file-action.delete:hover:not(:disabled) {
    background: rgba(220, 38, 38, 0.2);
    border-color: #dc2626;
    color: #dc2626;
}

.upload-complete {
    background: var(--bg-secondary);
    border: 2px solid var(--border-color);
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
}

.complete-header {
    margin-bottom: 2rem;
}

.success-icon {
    width: 80px;
    height: 80px;
    background: var(--citron-500);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: white;
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
    color: var(--text-primary);
    margin-bottom: 0.5rem;
}

.complete-subtitle {
    color: var(--text-secondary);
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
    background: var(--citron-500);
    color: white;
    box-shadow: 0 4px 15px rgba(132, 204, 22, 0.3);
}

.new-upload-btn:hover {
    background: var(--citron-600);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(132, 204, 22, 0.4);
}

.view-files-btn {
    background: var(--bg-tertiary);
    color: var(--text-primary);
    border: 2px solid var(--border-color);
}

.view-files-btn:hover {
    background: var(--border-color);
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

    .description-container {
        padding: 1rem;
        margin: 1rem;
    }

    .submit-button {
        padding: 1rem 2rem;
        font-size: 1rem;
        min-width: auto;
        width: 100%;
        max-width: 280px;
    }
}

/* Error notifications */
.error-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: var(--error-bg);
    color: #dc2626;
    padding: 1rem;
    border-radius: 12px;
    border: 2px solid rgba(252, 165, 165, 0.5);
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    backdrop-filter: blur(10px);
    max-width: 300px;
}

.dark .error-notification {
    color: #fca5a5;
    border-color: rgba(127, 29, 29, 0.5);
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
</style>
