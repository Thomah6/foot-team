<!-- [file name]: AuthenticatedLayout.vue
[file content begin] -->
<!-- AuthenticatedLayout.vue -->
<script setup>
import { ref } from "vue";

import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";

import AdminsideBar from "@/Components/AdminsideBar.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const sidebarOpen = ref(false); // ← AJOUT : état sidebar mobile

const page = usePage();
const isAdmin = () => page.props.auth.user.role === "admin";
const isBureau = () => page.props.auth.user.role === "bureau";

const handleImageError = (event) => {
    event.target.src = `https://ui-avatars.com/api/?name=${page.props.auth.user.name}&color=7F9CF5&background=EBF4FF&size=24`;
};

const notifications = ref(0);

function updateNotifCount(count) {
    notifications.value = typeof count === "number" && count >= 0 ? count : 0;
}

defineProps({
    votes: Array,
    reflections: Array,
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">

        <!-- HEADER -->
        <nav
            class="fixed top-0 left-0 right-0 z-50 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">

                    <!-- LEFT -->
                    <div class="flex items-center">

                        <!-- MOBILE HAMBURGER (AJOUTÉ) -->
                        <button class="lg:hidden mr-3" @click="sidebarOpen = true">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 text-gray-700 dark:text-gray-300"
                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>

                        <!-- LOGO -->
                        <Link :href="route('dashboard')">
                        <!-- ton logo ici -->
                        </Link>
                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                        >
                        </div>
                    </div>

                    <!-- DESKTOP HEADER RIGHT -->
                    <div class="hidden sm:flex sm:items-center">

                        <Dropdown align="right" width="48">
                            <template #trigger>
                                <button
                                    class="inline-flex items-center bg-white px-3 py-2 rounded-md text-gray-500 hover:text-gray-700">
                                    <img v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                                        :src="'/storage/' + $page.props.auth.user.avatar"
                                        class="w-6 h-6 rounded-full object-cover mr-2" @error="handleImageError" />
                                    <div v-else
                                        class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-2">
                                        <i class="fas fa-user text-white text-xs"></i>
                                    </div>

                                    {{ $page.props.auth.user.name }}

                                    <svg class="ms-2 h-4 w-4" fill="currentColor">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </template>

                            <template #content>
                                <DropdownLink :href="route('profile.edit')">Profil</DropdownLink>
                                <DropdownLink :href="route('logout')" method="post" as="button">Déconnexion
                                </DropdownLink>
                            </template>
                        </Dropdown>
                    </div>

                    <!-- MOBILE AVATAR -->
                    <div class="sm:hidden flex items-center">
                        <img v-if="$page.props.auth.user.avatar" :src="'/storage/' + $page.props.auth.user.avatar"
                            class="w-8 h-8 rounded-full object-cover" @error="handleImageError" />
                        <div v-else
                            class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- MOBILE SIDEBAR OVERLAY -->
        <div v-if="sidebarOpen" class="fixed inset-0 bg-black/50 z-40 lg:hidden" @click="sidebarOpen = false"></div>

        <!-- MOBILE SIDEBAR -->
        <div class="fixed top-16 bottom-0 left-0 w-72 z-50 bg-white dark:bg-gray-900 shadow-lg transform transition-transform lg:hidden"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <AdminsideBar :votes="votes" :reflections="reflections" :Notification="notifications" class="h-full" />
        </div>

        <!-- DESKTOP SIDEBAR -->
        <div class="hidden lg:block fixed left-0 top-16 bottom-0 z-40 w-72">
            <AdminsideBar :votes="votes" :reflections="reflections" :Notification="notifications"
                class="h-full border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900" />
        </div>

        <!-- MAIN CONTENT -->
        <div class="flex pt-16 h-screen">
            <div class="flex-1 lg:ml-72 overflow-y-auto">
                <main class="min-h-full">
                    <div class="p-4 sm:p-6">
                        <slot :updateNotifications="updateNotifCount" />
                    </div>
                </main>
            </div>
        </div>

    </div>
</template>


<style scoped>
/* Custom scrollbar for main content */
.overflow-y-auto::-webkit-scrollbar {
    width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: #f1f1f1;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
}

/* Dark mode scrollbar */
.dark .overflow-y-auto::-webkit-scrollbar-track {
    background: #374151;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: #4b5563;
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: #6b7280;
}

/* Ensure proper scrolling */
.h-screen {
    height: 100vh;
    height: 100dvh;
    /* For mobile browsers */
}

.min-h-full {
    min-height: calc(100vh - 4rem);
    min-height: calc(100dvh - 4rem);
}
</style>
<!-- [file content end] -->
