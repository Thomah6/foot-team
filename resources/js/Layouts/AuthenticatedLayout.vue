<!-- [file name]: AuthenticatedLayout.vue
[file content begin] -->
<script setup>
import { ref } from "vue";
import { defineEmits } from "vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownLink from "@/Components/DropdownLink.vue";
import NavLink from "@/Components/NavLink.vue";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";
import AdminsideBar from "@/Components/AdminsideBar.vue";
import { Link, usePage } from "@inertiajs/vue3";

const showingNavigationDropdown = ref(false);
const page = usePage();
const isAdmin = () => page.props.auth.user.role === "admin";
const isBureau = () => page.props.auth.user.role === "bureau";

const handleImageError = (event) => {
    // Si l'image ne charge pas, utiliser l'avatar par défaut
    event.target.src = `https://ui-avatars.com/api/?name=${page.props.auth.user.name}&color=7F9CF5&background=EBF4FF&size=24`;
};
const notifications = ref(0); // L'état des notifications

// La fonction que nous exposons au slot pour que l'enfant puisse DEFINIR la valeur
function updateNotifCount(count) {
    console.log("Mise à jour des notifications à :", count);
    // S'assure que 'count' est un nombre
    notifications.value = typeof count === "number" && count >= 0 ? count : 0;
}

defineProps({
    votes: Array,
    reflections: Array,
});
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        <!-- Navigation Header - Fixed -->
        <nav class="fixed top-0 left-0 right-0 z-50 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900 shadow-sm">
            <!-- Primary Navigation Menu -->
            <div class="mx-auto max-w-full px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link :href="route('dashboard')"> </Link>
                        </div>

                        <!-- Navigation Links -->
                        <div
                            class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex"
                        >
                            <NavLink
                                v-if="isAdmin()"
                                :href="route('reflections.index')"
                                :active="route().current('reflections.*')"
                            >
                                Reflexions
                                <span
                                    v-if="notifications > 0"
                                    class="ms-2 inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full text-xs font-bold text-white bg-red-500"
                                >
                                    {{ notifications }}
                                </span>
                            </NavLink>
                        </div>
                    </div>

                    <!-- Desktop User Menu -->
                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <!-- Settings Dropdown -->
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <span class="inline-flex rounded-md">
                                        <button
                                            type="button"
                                            class="inline-flex items-center rounded-md border border-transparent bg-white px-3 py-2 text-sm font-medium leading-4 text-gray-500 transition duration-150 ease-in-out hover:text-gray-700 focus:outline-none"
                                        >
                                            <img
                                                v-if="
                                                    $page.props.auth.user
                                                        .avatar &&
                                                    $page.props.auth.user
                                                        .avatar !== ''
                                                "
                                                :src="
                                                    '/storage/' +
                                                    $page.props.auth.user
                                                        .avatar
                                                "
                                                :alt="
                                                    $page.props.auth.user
                                                        .name
                                                "
                                                class="w-6 h-6 rounded-full object-cover mr-2"
                                                @error="handleImageError"
                                            />
                                            <div
                                                v-else
                                                class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-2"
                                            >
                                                <i
                                                    class="fas fa-user text-white text-xs"
                                                ></i>
                                            </div>
                                            {{ $page.props.auth.user.name }}

                                            <svg
                                                class="-me-0.5 ms-2 h-4 w-4"
                                                xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20"
                                                fill="currentColor"
                                            >
                                                <path
                                                    fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd"
                                                />
                                            </svg>
                                        </button>
                                    </span>
                                </template>

                                <template #content>
                                    <DropdownLink
                                        :href="route('profile.edit')"
                                    >
                                        Profile
                                    </DropdownLink>
                                    <DropdownLink
                                        :href="route('logout')"
                                        method="post"
                                        as="button"
                                    >
                                        Log Out
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile User Avatar -->
                    <div class="sm:hidden flex items-center">
                        <img
                            v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                            :src="'/storage/' + $page.props.auth.user.avatar"
                            :alt="$page.props.auth.user.name"
                            class="w-8 h-8 rounded-full object-cover"
                            @error="handleImageError"
                        >
                        <div v-else class="w-8 h-8 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center">
                            <i class="fas fa-user text-white text-sm"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div
                :class="{
                    'block': showingNavigationDropdown,
                    'hidden': !showingNavigationDropdown,
                }"
                class="sm:hidden absolute top-16 left-0 right-0 bg-white dark:bg-gray-900 border-b border-gray-200 dark:border-gray-800 shadow-lg z-40"
            >
                <div class="space-y-1 pb-3 pt-2 px-4">
                    <ResponsiveNavLink
                        :href="route('dashboard')"
                        :active="route().current('dashboard')"
                    >
                        Dashboard
                    </ResponsiveNavLink>
                    
                    <ResponsiveNavLink
                        v-if="isAdmin()"
                        :href="route('reflections.index')"
                        :active="route().current('reflections.*')"
                    >
                        Reflexions
                        <span v-if="notifications > 0" class="ms-2 inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full text-xs font-bold text-white bg-red-500">
                            {{ notifications }}
                        </span>
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-800 pb-4 pt-3 px-4">
                    <div class="flex items-center gap-3 mb-3">
                        <img
                            :src="
                                $page.props.auth.user.avatar
                                    ? '/storage/' +
                                      $page.props.auth.user.avatar
                                    : `https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&color=7F9CF5&background=EBF4FF`
                            "
                            :alt="$page.props.auth.user.name"
                            class="w-10 h-10 rounded-full object-cover"
                        />
                        <div>
                            <div
                                class="text-base font-medium text-gray-800 dark:text-gray-200"
                            >
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            Profile
                        </ResponsiveNavLink>
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Layout Container -->
        <div class="flex pt-16 h-screen">
            <!-- Sidebar - Fixed on desktop, hidden on mobile -->
            <div class="hidden lg:block fixed left-0 top-16 bottom-0 z-40 w-72">
                <AdminsideBar
                    :votes="votes"
                    :reflections="reflections"
                    :Notification="notifications"
                    class="h-full border-r border-gray-200 dark:border-gray-800 bg-white dark:bg-gray-900"
                />
            </div>

            <!-- Main Content Area -->
            <div class="flex-1 lg:ml-72 overflow-y-auto">
                <main class="min-h-full">
                    <div class="p-4 sm:p-6">
                        <!-- Slot with padding adjustment for mobile -->
                        <div class="max-w-full">
                            <slot :updateNotifications="updateNotifCount()" />
                        </div>
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
    height: 100dvh; /* For mobile browsers */
}

.min-h-full {
    min-height: calc(100vh - 4rem);
    min-height: calc(100dvh - 4rem);
}
</style>
<!-- [file content end] -->