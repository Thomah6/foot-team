<script setup>
// import GalleryList from "../Components/GalleryList.vue";
import AdminsideBar from "@/Components/AdminsideBar.vue";
import FileUpload from "@/Components/FileUpload.vue";
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
      only: ['galleries'], // ⚡ recharge uniquement la prop galleries
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
    <div
        class="font-display bg-background-light dark:bg-background-dark text-text-light dark:text-text-dark"
    >
        <div class="relative flex min-h-screen w-full">
            <AdminsideBar />

            <!-- Main Content Area -->
            <div class="flex flex-1 overflow-hidden">
                <!-- Gallery View -->
                <main v-if="!showUploadModal" class="flex-1 overflow-y-auto">
                    <div class="p-6 md:p-10">
                        <!-- Page Heading -->
                        <header
                            class="flex flex-wrap justify-between items-center gap-4 mb-8"
                        >
                            <div class="flex flex-col gap-1">
                                <h1
                                    class="text-text-light dark:text-text-dark text-4xl font-black leading-tight tracking-tight"
                                >
                                    Club Photo Gallery
                                </h1>
                                <p
                                    class="text-subtext-light dark:text-subtext-dark text-base font-normal leading-normal"
                                >
                                    Photos from our recent events and gatherings.
                                </p>
                            </div>
                        </header>

                        <!-- Image Grid -->
                        <div
                            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                        >
                            <div
                                v-for="gallery in galleries"
                                :key="gallery.id"
                                class="bg-surface-light dark:bg-surface-dark rounded-lg shadow-md overflow-hidden"
                            >
                                <img
                                    :src="`/storage/${gallery.image_path}`"
                                    :alt="gallery.description || 'Image'"
                                    class="w-full h-48 object-cover"
                                />
                                <div class="p-4 flex items-center justify-between">
                                    <p
                                        class="text-sm text-text-light dark:text-text-dark"
                                    >
                                        {{
                                            gallery.description ||
                                            "Sans description"
                                        }}
                                    </p>

                                    <button
                                        @click="toggleLike(gallery)"
                                        class="flex items-center gap-1 text-primary"
                                    >
                                        <span class="material-symbols-outlined" :class="gallery.liked_by_user ? 'text-red-500' : 'text-gray-400'"
                                            ><i class="fa-regular fa-thumbs-up"></i></span
                                        >
                                        <span>{{ gallery.likes_count }}</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Floating Action Button (FAB) -->
                    <div class="fixed bottom-8 right-8">
                        <button
                            @click="showUploadModal = true"
                            class="flex cursor-pointer items-center justify-center rounded-full h-16 w-16 bg-primary text-text-light shadow-lg hover:scale-105 transition-transform"
                        >
                            <span
                                class="cursor-pointer material-symbols-outlined text-4xl"
                                style="font-variation-settings: 'wght' 500"
                                ><i class="fa-solid fa-plus"></i
                            ></span>
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
    </div>
</template>
