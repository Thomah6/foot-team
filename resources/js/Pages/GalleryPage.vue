<script setup>
import AdminsideBar from "@/Components/AdminsideBar.vue";
import FileUpload from "@/Components/FileUpload.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
  galleries: {
    type: Array,
    default: () => [],
  },
})

const showUploadModal = ref(false)

function toggleLike(gallery) {
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
</script>

<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Page Header -->
                <div class="page-header mb-8">
                    <div>
                        <h1 class="page-title">
                            <i class="fas fa-images text-primary-600 mr-3"></i>
                            Galerie Photo
                        </h1>
                        <p class="page-subtitle">
                            Photos des événements et matchs du club
                        </p>
                    </div>
                </div>

                <!-- Upload Button (Mobile) -->
                <div v-if="!showUploadModal" class="flex justify-end mb-6">
                    <button
                        @click="showUploadModal = true"
                        class="inline-flex items-center gap-2 px-6 py-3 rounded-button bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors shadow-button"
                    >
                        <i class="fas fa-plus"></i>
                        Ajouter des photos
                    </button>
                </div>

                <!-- Gallery Grid -->
                <div v-if="!showUploadModal" class="grid-responsive-4">
                    <!-- Empty State -->
                    <div v-if="galleries.length === 0" class="col-span-full py-12 text-center">
                        <i class="fas fa-image text-gray-300 text-6xl mb-4 block"></i>
                        <p class="text-gray-500 dark:text-gray-400 text-lg">
                            Aucune photo pour le moment
                        </p>
                        <button
                            @click="showUploadModal = true"
                            class="mt-4 inline-flex items-center gap-2 px-4 py-2 rounded-button bg-primary-600 text-white text-sm font-semibold hover:bg-primary-700 transition-colors"
                        >
                            <i class="fas fa-cloud-arrow-up"></i>
                            Ajouter la première photo
                        </button>
                    </div>

                    <!-- Image Cards -->
                    <div
                        v-for="gallery in galleries"
                        :key="gallery.id"
                        class="card p-0 overflow-hidden group hover:shadow-card-hover"
                    >
                        <!-- Image Container -->
                        <div class="relative overflow-hidden bg-gray-100 dark:bg-gray-700 h-48 sm:h-56">
                            <img
                                :src="`/storage/${gallery.image_path}`"
                                :alt="gallery.description || 'Photo du club'"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            />
                            <!-- Image Overlay -->
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors duration-300 flex items-center justify-center">
                                <button
                                    @click="toggleLike(gallery)"
                                    class="opacity-0 group-hover:opacity-100 transition-opacity p-3 rounded-full bg-white text-primary-600 hover:bg-gray-100"
                                >
                                    <i :class="['fas text-xl', gallery.liked_by_user ? 'fa-heart' : 'fa-heart']"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Card Content -->
                        <div class="p-4">
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 line-clamp-2">
                                {{ gallery.description || "Sans description" }}
                            </p>
                            <div class="flex items-center justify-between mt-3">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ new Date(gallery.created_at).toLocaleDateString('fr-FR') }}
                                </span>
                                <button
                                    @click="toggleLike(gallery)"
                                    :class="['inline-flex items-center gap-1 text-sm font-semibold transition-colors', gallery.liked_by_user ? 'text-red-500' : 'text-gray-400 hover:text-red-500']"
                                >
                                    <i class="fas fa-heart"></i>
                                    <span>{{ gallery.likes_count }}</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Upload Modal -->
                <div v-if="showUploadModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-elevation max-w-2xl w-full max-h-[90vh] overflow-y-auto">
                        <!-- Modal Header -->
                        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700 sticky top-0 bg-white dark:bg-gray-800">
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                <i class="fas fa-cloud-arrow-up mr-3 text-primary-600"></i>
                                Ajouter des photos
                            </h2>
                            <button
                                @click="showUploadModal = false"
                                class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors"
                            >
                                <i class="fas fa-xmark text-xl text-gray-500 dark:text-gray-400"></i>
                            </button>
                        </div>

                        <!-- Modal Content -->
                        <div class="p-6">
                            <FileUpload :embedded="true" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
