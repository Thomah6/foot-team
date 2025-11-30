<script setup>
import { ref } from 'vue';
import { defineEmits } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import AdminsideBar from '@/Components/AdminsideBar.vue';
import { Link, usePage } from '@inertiajs/vue3';

const showingNavigationDropdown = ref(false);
const page = usePage();
const isAdmin = () => page.props.auth.user.role === 'admin';
const isBureau = () => page.props.auth.user.role === 'bureau'

const handleImageError = (event) => {
    event.target.src = `https://ui-avatars.com/api/?name=${page.props.auth.user.name}&color=7F9CF5&background=EBF4FF&size=24`
}
const notifications = ref(0);

function updateNotifCount(count) {
    notifications.value = (typeof count === 'number' && count >= 0) ? count : 0;
}

defineProps({
    votes: Array,
    reflections: Array,
});
</script>

<template>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Top Navigation Bar -->
        <nav class="sticky top-0 z-40 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between items-center">
                    <!-- Logo -->
                    <div class="flex shrink-0 items-center">
                        <Link :href="route('dashboard')" class="inline-block">
                            <ApplicationLogo class="h-10 w-10 fill-current text-primary-600" />
                        </Link>
                    </div>

                    <!-- Desktop Navigation Links -->
                    <div class="hidden sm:flex sm:space-x-8 sm:ms-10">
                        <NavLink v-if="isAdmin()" :href="route('reflections.index')" :active="route().current('reflections.*')" class="relative">
                            Réflexions
                            <span v-if="notifications > 0" class="absolute -top-2 -right-3 inline-flex items-center justify-center h-5 w-5 rounded-full text-xs font-bold text-white bg-red-500">
                                {{ notifications }}
                            </span>
                        </NavLink>
                    </div>

                    <!-- Right Section -->
                    <div class="hidden sm:ms-6 sm:flex sm:items-center">
                        <!-- Settings Dropdown -->
                        <div class="relative ms-3">
                            <Dropdown align="right" width="48">
                                <template #trigger>
                                    <button type="button" class="inline-flex items-center rounded-full border border-transparent bg-transparent px-3 py-2 text-sm font-medium transition duration-150 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none">
                                        <img
                                            v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                                            :src="'/storage/' + $page.props.auth.user.avatar"
                                            :alt="$page.props.auth.user.name"
                                            class="w-8 h-8 rounded-full object-cover mr-2"
                                            @error="handleImageError"
                                        >
                                        <div v-else class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center mr-2 flex-shrink-0">
                                            <i class="fas fa-user text-white text-xs"></i>
                                        </div>
                                        <span class="hidden sm:inline text-text-light dark:text-text-dark">{{ $page.props.auth.user.name }}</span>
                                        <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </template>

                                <template #content>
                                    <DropdownLink :href="route('profile.edit')">
                                        <i class="fas fa-user mr-2"></i> Profil
                                    </DropdownLink>
                                    <DropdownLink :href="route('logout')" method="post" as="button">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                                    </DropdownLink>
                                </template>
                            </Dropdown>
                        </div>
                    </div>

                    <!-- Mobile Menu Button -->
                    <div class="flex sm:hidden items-center gap-4">
                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-500 focus:outline-none transition-colors">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path :class="{ hidden: showingNavigationDropdown, 'inline-flex': !showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                <path :class="{ hidden: !showingNavigationDropdown, 'inline-flex': showingNavigationDropdown }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden border-t border-gray-200 dark:border-gray-700">
                <div class="space-y-1 pb-3 pt-2 px-4">
                    <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                        <i class="fas fa-home mr-2"></i> Tableau de bord
                    </ResponsiveNavLink>
                    <ResponsiveNavLink v-if="isAdmin()" :href="route('reflections.index')" :active="route().current('reflections.*')">
                        <i class="fas fa-lightbulb mr-2"></i> Réflexions
                        <span v-if="notifications > 0" class="badge badge-primary ms-2">{{ notifications }}</span>
                    </ResponsiveNavLink>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pb-3 pt-4 px-4">
                    <div class="flex items-center gap-3 mb-3">
                        <img
                            :src="$page.props.auth.user.avatar ? '/storage/' + $page.props.auth.user.avatar : `https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&color=7F9CF5&background=EBF4FF`"
                            :alt="$page.props.auth.user.name"
                            class="w-10 h-10 rounded-full object-cover"
                        >
                        <div>
                            <div class="text-base font-medium text-gray-800 dark:text-gray-100">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $page.props.auth.user.email }}
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')">
                            <i class="fas fa-user mr-2"></i> Profil
                        </ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                            <i class="fas fa-sign-out-alt mr-2"></i> Déconnexion
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex min-h-[calc(100vh-4rem)]">
            <!-- Sidebar -->
            <div class="hidden md:block sticky top-16 h-[calc(100vh-4rem)] z-30">
                <AdminsideBar 
                    :votes="votes"
                    :reflections="reflections"
                    :Notification="notifications"
                />
            </div>

            <!-- Page Content -->
            <div class="flex-1 overflow-y-auto">
                <slot :updateNotifications="updateNotifCount()" /> 
            </div>
        </main>
    </div>
</template>
