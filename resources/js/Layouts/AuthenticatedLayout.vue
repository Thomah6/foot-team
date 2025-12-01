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
    // Si l'image ne charge pas, utiliser l'avatar par défaut
    event.target.src = `https://ui-avatars.com/api/?name=${page.props.auth.user.name}&color=7F9CF5&background=EBF4FF&size=24`
}
const notifications = ref(0); // L'état des notifications

// La fonction que nous exposons au slot pour que l'enfant puisse DEFINIR la valeur
function updateNotifCount(count) {
    console.log("Mise à jour des notifications à :", count);
    // S'assure que 'count' est un nombre
    notifications.value = (typeof count === 'number' && count >= 0) ? count : 0;
}

defineProps({
    votes: Array,
    reflections: Array,
});
</script>

<template>
    <div class=" overflow-hidden">
        <div class="min-h-screen  bg-gray-100">
            <nav class="border-b border-gray-100 bg-white">
                <!-- Primary Navigation Menu -->
                <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    <div class="flex h-16 justify-between">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex shrink-0 items-center">
                                <Link :href="route('dashboard')">

                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                                <!-- <NavLink :href="route('dashboard')" :active="route().current('dashboard')">
                                    Dashboard
                                </NavLink>
                                <NavLink v-if="isAdmin()" :href="route('members.index')"
                                    :active="route().current('members.*')">
                                    Membres
                                </NavLink>
                                <NavLink v-if="isAdmin()" :href="route('admin.team-stats.index')"
                                    :active="route().current('admin.team-stats.*')">
                                    Statistiques des équipes
                                </NavLink> -->
                                <NavLink v-if="isAdmin()" :href="route('reflections.index')"
                                    :active="route().current('reflections.*')">
                                    Reflexions 
                                    <span v-if="notifications > 0" 
                                        class="ms-2 inline-flex items-center justify-center h-5 min-w-[20px] 
                                                px-1.5 rounded-full text-xs font-bold text-white bg-red-500">
                                        {{ notifications }}
                                    </span>
                                </NavLink>
                                <!-- <NavLink v-if="isBureau()" :href="route('bureau.members.index')"
                                    :active="route().current('bureau.members.*')">
                                    Members
                                </NavLink> -->
                            </div>
                        </div>

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
                                                    v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                                                    :src="'/storage/' + $page.props.auth.user.avatar"
                                                    :alt="$page.props.auth.user.name"
                                                    class="w-6 h-6 rounded-full object-cover mr-2"
                                                    @error="handleImageError"
                                                >
                                                <div v-else class="w-6 h-6 rounded-full bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center mr-2">
                                                    <i class="fas fa-user text-white text-xs"></i>
                                                </div>
                                                {{ $page.props.auth.user.name }}

                                                <svg class="-me-0.5 ms-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')">
                                            Profile
                                        </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-me-2 flex items-center sm:hidden">
                            <button @click="
                                showingNavigationDropdown =
                                !showingNavigationDropdown
                                "
                                class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 transition duration-150 ease-in-out hover:bg-gray-100 hover:text-gray-500 focus:bg-gray-100 focus:text-gray-500 focus:outline-none">
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path :class="{
                                        hidden: showingNavigationDropdown,
                                        'inline-flex':
                                            !showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                    <path :class="{
                                        hidden: !showingNavigationDropdown,
                                        'inline-flex':
                                            showingNavigationDropdown,
                                    }" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div :class="{
                    block: showingNavigationDropdown,
                    hidden: !showingNavigationDropdown,
                }" class="sm:hidden">
                    <div class="space-y-1 pb-3 pt-2">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>

                        <ResponsiveNavLink v-if="isAdmin()" :href="route('members.index')"
                            :active="route().current('members.*')">
                            Membres
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin()" :href="route('admin.team-stats.index')"
                            :active="route().current('admin.team-stats.*')">
                            Statisques des équipes
                        </ResponsiveNavLink>
                        <ResponsiveNavLink v-if="isAdmin()" :href="route('reflections.index')"
                            :active="route().current('reflections.*')">
                            Reflexions <span class="badge">{{ notifications }}</span>
                        </ResponsiveNavLink>
                        
                    </div>

                    <!-- Responsive Settings Options -->
                    <div
                        class="border-t border-gray-200 pb-1 pt-4"
                    >
                        <div class="px-4 flex items-center gap-3">
                            <img
                                :src="$page.props.auth.user.avatar ? '/storage/' + $page.props.auth.user.avatar : `https://ui-avatars.com/api/?name=${$page.props.auth.user.name}&color=7F9CF5&background=EBF4FF`"
                                :alt="$page.props.auth.user.name"
                                class="w-10 h-10 rounded-full object-cover"
                            >
                            <div>
                                <div
                                    class="text-base font-medium text-gray-800"
                                >
                                    {{ $page.props.auth.user.name }}
                                </div>
                                <div class="text-sm font-medium text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')">
                                Profile
                            </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <!-- <header
                class="bg-white shadow"
                v-if="$slots.header"
            >
                <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header> -->

            <!-- Page Content -->
            <main class="flex h-full border">
                <div class="min-h-full max-h-[calc(100vh-4rem)] sticky top-0 z-50">
                    <AdminsideBar 
                    :votes="votes"
                    :reflections="reflections"
                    :Notification="notifications"
                    />
                </div>
                <div class=" scroller h-[calc(100vh-4rem)] overflow-y-scroll w-full">
                    <slot :updateNotifications="updateNotifCount()" /> 
                </div>

            </main>
        </div>
    </div>
</template>

<style scoped>
.scroller{
    scrollbar-width: none;
    scrollbar-color: #a0aec0 transparent;
}
</style>
