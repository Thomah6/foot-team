<script setup>
import AdminsideBar from "@/Components/AdminsideBar.vue";
import FileUpload from "@/Components/FileUpload.vue";
import { ref, onMounted, onUnmounted } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  galleries: {
    type: Array,
    default: () => [],
  },
})

const showUploadModal = ref(false)
const zoomedImage = ref(null)
const isMobile = ref(false)

// Fonction pour le like
function toggleLike(gallery, event) {
  event.stopPropagation();
  if (gallery.liked_by_user) {
    router.delete(`/galleries/${gallery.id}/unlike`, {
      only: ['galleries'],
      preserveScroll: true,
    })
  } else {
    router.post(`/galleries/${gallery.id}/like`, {}, {
      only: ['galleries'],
      preserveScroll: true,
    })
  }
}

// Gestion du zoom
function toggleZoom(gallery) {
  if (isMobile.value) return

  if (zoomedImage.value?.id === gallery.id) {
    // Dézoomer si c'est la même image
    zoomedImage.value = null
  } else {
    // Zoomer sur la nouvelle image
    zoomedImage.value = gallery
  }
}

// Fermer le zoom en cliquant à l'extérieur
function closeZoom(event) {
  if (zoomedImage.value &&
      !event.target.closest('.zoomed-image-container') &&
      !event.target.closest('.glass-image-container')) {
    zoomedImage.value = null
  }
}

// Fermer avec la touche Escape
function handleEscapeKey(event) {
  if (event.key === 'Escape') {
    zoomedImage.value = null
  }
}

function handleResize() {
  isMobile.value = window.innerWidth <= 520
  if (isMobile.value) {
    zoomedImage.value = null
  }
}

// Initialisation
onMounted(() => {
  isMobile.value = window.innerWidth <= 520
  window.addEventListener('resize', handleResize)
  document.addEventListener('keydown', handleEscapeKey)
  document.addEventListener('click', closeZoom)
})

onUnmounted(() => {
  window.removeEventListener('resize', handleResize)
  document.removeEventListener('keydown', handleEscapeKey)
  document.removeEventListener('click', closeZoom)
})
</script>

<template>
  <div class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark">
    <div class="relative flex min-h-screen w-full">
      <AdminsideBar />

      <!-- Main Content Area -->
      <div class="flex flex-1 overflow-hidden">
        <!-- Gallery View -->
        <main v-if="!showUploadModal" class="flex-1 overflow-y-auto">
          <div class="p-6 md:p-10">
            <!-- Page Heading -->
            <header class="flex flex-wrap justify-between items-center gap-4 mb-8">
              <div class="flex flex-col gap-1">
                <h1 class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-tight">
                  Club Photo Gallery
                </h1>
                <p class="text-subtext-light dark:text-subtext-dark text-base font-normal leading-normal">
                  Photos from our recent events and gatherings.
                </p>
              </div>
            </header>

            <!-- Masonry Gallery -->
            <div class="masonry">
              <div
                v-for="gallery in galleries"
                :key="gallery.id"
                class="masonry-item"
              >
                <!-- Cadre glassmorphisme transparent qui englobe TOUT -->
                <div class="glass-card">
                  <!-- Image avec effet de verre -->
                  <div class="glass-image-container" @click="toggleZoom(gallery)">
                    <img
                      :src="`/storage/${gallery.image_path}`"
                      :alt="gallery.description || 'Image'"
                      class="glass-image"
                      :class="{ 'zoomed': zoomedImage?.id === gallery.id }"
                    />
                  </div>

                  <!-- Zone pour description et bouton like - À L'INTÉRIEUR du glassmorphisme -->
                  <div class="glass-content">
                    <!-- Description -->
                    <p class="glass-description">
                      {{ gallery.description || "Sans description" }}
                    </p>

                    <!-- Bouton like avec compteur -->
                    <button
                      @click.stop="toggleLike(gallery, $event)"
                      class="glass-like-button"
                      :aria-label="gallery.liked_by_user ? 'Unlike this photo' : 'Like this photo'"
                    >
                      <span class="material-symbols-outlined glass-like-icon"
                            :class="gallery.liked_by_user ? 'liked' : ''">
                        <i class="fa-regular fa-thumbs-up"></i>
                      </span>
                      <span class="glass-like-count">{{ gallery.likes_count }}</span>
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Floating Action Button (FAB) -->
          <div class="fixed bottom-8 right-8 z-20">
            <button
              @click="showUploadModal = true"
              class="flex cursor-pointer items-center justify-center rounded-full h-16 w-16 bg-primary text-text-light shadow-lg hover:scale-105 transition-transform"
            >
              <span
                class="cursor-pointer material-symbols-outlined text-4xl"
                style="font-variation-settings: 'wght' 500"
              >
                <i class="fa-solid fa-plus"></i>
              </span>
            </button>
          </div>
        </main>

        <!-- Upload View (Side by side) -->
        <div v-else class="w-full md:w-2/3 lg:w-3/5 overflow-y-auto bg-background-dark border-l border-slate-700">
          <div class="p-6">
            <!-- Close Button -->
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-2xl font-bold text-white">Upload Photos</h2>
              <button
                @click="showUploadModal = false"
                class="text-gray-400 hover:text-white transition"
              >
                <span class="material-symbols-outlined text-2xl">close</span>
              </button>
            </div>
            <!-- FileUpload Component -->
            <FileUpload :embedded="true" />
          </div>
        </div>
      </div>
    </div>

    <!-- Overlay pour l'image zoomée -->
    <div v-if="zoomedImage" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <!-- Overlay sombre -->
      <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" @click="zoomedImage = null"></div>

      <!-- Conteneur de l'image zoomée réduite à 50% et centrée -->
      <div class="relative z-10 w-full flex flex-col items-center justify-center">
        <div class="w-1/2 max-h-[70vh] flex flex-col items-center">
          <img
            :src="`/storage/${zoomedImage.image_path}`"
            :alt="zoomedImage.description || 'Image zoomée'"
            class="w-full h-auto max-h-[60vh] object-contain rounded-lg shadow-2xl cursor-zoom-out"
            @click="zoomedImage = null"
          />

          <!-- Bouton de fermeture -->
          <button
            @click="zoomedImage = null"
            class="absolute -top-6 right-8 z-2 text-white hover:text-gray-300 transition-colors p-2 rounded-full bg-black/50 hover:bg-black/70"
            aria-label="Fermer le zoom"
          >
            <span class="material-symbols-outlined text-4xl">close</span>
        </button>

          <!-- Informations sous l'image zoomée -->
          <div class="mt-4 w-full text-center text-white">
            <p class="text-lg font-medium mb-2">{{ zoomedImage.description || "Sans description" }}</p>
            <div class="flex items-center justify-center gap-2">
              <button
                @click.stop="toggleLike(zoomedImage, $event)"
                class="flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full hover:bg-white/20 transition-colors"
              >
                <span class="material-symbols-outlined" :class="zoomedImage.liked_by_user ? 'text-red-500' : 'text-gray-300'">
                  <i class="fa-regular fa-thumbs-up"></i>
                </span>
                <span class="font-medium">{{ zoomedImage.likes_count }}</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Masonry via CSS Columns */
.masonry {
  column-count: 7;
  column-gap: 1.5rem;
  padding: 0 1rem;
}

@media (max-width: 1100px) {
  .masonry {
    column-count: 4;
  }
}

@media (max-width: 780px) {
  .masonry {
    column-count: 2;
  }
}

@media (max-width: 520px) {
  .masonry {
    column-count: 1;
  }
}

.masonry-item {
  break-inside: avoid;
  -webkit-column-break-inside: avoid;
  margin-bottom: 2rem;
}

/* Cadre glassmorphisme transparent qui englobe TOUT */
.glass-card {
  background: rgba(255, 255, 255, 0.05);
  backdrop-filter: blur(15px);
  -webkit-backdrop-filter: blur(15px);
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow:
    0 8px 32px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.05);
  overflow: hidden;
  transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
  position: relative;
}

.dark .glass-card {
  background: rgba(20, 20, 20, 0.15);
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.glass-card:hover {
  transform: translateY(-4px);
  box-shadow:
    0 12px 40px rgba(0, 0, 0, 0.15),
    0 6px 20px rgba(0, 0, 0, 0.1),
    inset 0 1px 0 rgba(255, 255, 255, 0.08);
  border-color: rgba(255, 255, 255, 0.15);
}

/* Conteneur de l'image */
.glass-image-container {
  position: relative;
  overflow: hidden;
  margin: 12px 12px 0 12px;
  border-radius: 12px;
  cursor: zoom-in;
  transition: transform 0.3s ease;
}

.glass-image-container:hover {
  transform: scale(1.02);
}

/* Image */
.glass-image {
  transition: transform 0.3s ease;
  transform-origin: center center;
  width: 100%;
  height: auto;
  display: block;
  border-radius: 12px;
}

.glass-image.zoomed {
  cursor: zoom-out;
}

/* Zone pour description et bouton like - À L'INTÉRIEUR du glassmorphisme */
.glass-content {
  padding: 16px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-top: 12px;
  border-top: 1px solid rgba(255, 255, 255, 0.05);
}

/* Description */
.glass-description {
  color: rgba(0, 0, 0, 0.9);
  font-size: 0.95rem;
  line-height: 1.4;
  margin: 0;
  flex: 1;
  /* overflow: hidden; */
  text-overflow: ellipsis;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
}

.dark .glass-description {
  color: rgba(255, 255, 255, 0.95);
}

/* Bouton like */
.glass-like-button {
  display: flex;
  align-items: center;
  gap: 6px;
  background: transparent;
  border: 1px solid rgba(255, 255, 255, 0.1);
  border-radius: 20px;
  padding: 6px 14px;
  color: rgba(0, 0, 0, 0.8);
  font-weight: 500;
  transition: all 0.3s ease;
  cursor: pointer;
  backdrop-filter: blur(5px);
  -webkit-backdrop-filter: blur(5px);
}

.dark .glass-like-button {
  color: rgba(255, 255, 255, 0.9);
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.glass-like-button:hover {
  background: rgba(255, 255, 255, 0.1);
  border-color: rgba(255, 255, 255, 0.2);
  transform: scale(1.05);
}

.dark .glass-like-button:hover {
  background: rgba(255, 255, 255, 0.08);
}

/* Icône like */
.glass-like-icon {
  font-size: 1.1rem;
  color: rgba(0, 0, 0, 0.7);
  transition: all 0.3s ease;
}

.dark .glass-like-icon {
  color: rgba(255, 255, 255, 0.8);
}

.glass-like-icon.liked {
  color: #ef4444 !important;
}

/* Compteur de likes */
.glass-like-count {
  font-size: 0.95rem;
  font-weight: 600;
  color: rgba(0, 0, 0, 0.9);
}

.dark .glass-like-count {
  color: rgba(255, 255, 255, 0.95);
}

/* Mobile no zoom */
@media (max-width: 520px) {
  .glass-card:hover {
    transform: none;
  }

  .glass-image-container {
    cursor: default;
  }

  .glass-image-container:hover {
    transform: none;
  }
}

/* Pour l'image zoomée sur mobile */
@media (max-width: 768px) {
  .w-1\/2 {
    width: 90% !important;
  }

  .max-h-\[70vh\] {
    max-height: 60vh !important;
  }

  .max-h-\[60vh\] {
    max-height: 50vh !important;
  }
}

@media (max-width: 520px) {
  .w-1\/2 {
    width: 95% !important;
  }

  .max-h-\[70vh\] {
    max-height: 50vh !important;
  }

  .max-h-\[60vh\] {
    max-height: 40vh !important;
  }
}

/* Animation pour l'image zoomée */
.fixed.inset-0 {
  animation: fadeIn 0.3s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

.relative.z-10 {
  animation: zoomIn 0.3s ease;
}

@keyframes zoomIn {
  from {
    transform: scale(0.8);
    opacity: 0;
  }
  to {
    transform: scale(1);
    opacity: 1;
  }
}
</style>
