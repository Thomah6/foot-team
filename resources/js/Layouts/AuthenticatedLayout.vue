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
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <div class="overflow-hidden">
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <!-- Mobile Header (Fixed) -->
            <div class="lg:hidden fixed top-0 left-0 right-0 z-40 bg-white/90 dark:bg-gray-900/90 backdrop-blur-lg border-b border-lime-200/50 dark:border-emerald-900/50 shadow-lg">
                <div class="flex items-center justify-between px-4 py-3">
                    <!-- Logo & Club Name -->
                    <div class="flex items-center gap-3">
                        <button @click="showMobileSidebar = !showMobileSidebar"
                                class="p-2 rounded-lg bg-gradient-to-br from-lime-400/10 to-emerald-600/10 hover:from-lime-400/20 hover:to-emerald-600/20 transition-all">
                            <i class="fas fa-bars text-lime-600 dark:text-emerald-400 text-lg"></i>
                        </button>
                        
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8  rounded-lg flex items-center justify-center shadow-md">
                                <!-- logo -->
                            </div>
                            <div>
                                <div class="text-sm font-black bg-gradient-to-r from-gray-900 to-emerald-800 dark:from-lime-300 dark:to-emerald-400 bg-clip-text text-transparent">
                                    FC Dynamo
                                </div>
                                <div class="text-[10px] font-bold text-lime-600 dark:text-emerald-400">
                                    ARÈNE ACTIVE
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- User Avatar & Notifications -->
                    <div class="flex items-center gap-3">
                        <!-- Notifications -->
                        <button v-if="notifications > 0" 
                                @click="showNotifications = !showNotifications"
                                class="relative p-2">
                            <div class="relative">
                                <i class="fas fa-bell text-lime-600 dark:text-emerald-400 text-lg"></i>
                                <span class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg">
                                    {{ notifications }}
                                </span>
                            </div>
                        </button>

                        <!-- User Avatar -->
                        <div class="relative">
                            <img v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                                 :src="'/storage/' + $page.props.auth.user.avatar"
                                 :alt="$page.props.auth.user.name"
                                 class="w-9 h-9 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-md"
                                 @error="handleImageError">
                            <div v-else 
                                 class="w-9 h-9 rounded-full bg-gradient-to-br from-lime-400 to-emerald-600 flex items-center justify-center border-2 border-white dark:border-gray-800 shadow-md">
                                <i class="fas fa-user text-white text-sm"></i>
                            </div>
                            <div class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-gradient-to-br from-lime-500 to-emerald-700 rounded-full border border-white dark:border-gray-800"></div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Navigation Quick Actions -->
                <div class="px-4 pb-3 overflow-x-auto flex gap-2 scrollbar-hide">
                    <Link :href="route('dashboard')"
                          :class="[
                              'flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all',
                              route().current('dashboard') 
                                ? 'bg-gradient-to-r from-lime-400 to-emerald-500 text-white shadow-md' 
                                : 'bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800'
                          ]">
                        <i class="fas fa-home text-xs"></i>
                        <span>Dashboard</span>
                    </Link>
                    
                    <Link v-if="isAdmin()" :href="route('admin.members.index')"
                          :class="[
                              'flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all',
                              route().current('admin.members.*') 
                                ? 'bg-gradient-to-r from-lime-400 to-emerald-500 text-white shadow-md' 
                                : 'bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800'
                          ]">
                        <i class="fas fa-users text-xs"></i>
                        <span>Membres</span>
                    </Link>
                    
                    <Link v-if="isAdmin()" :href="route('reflections.index')"
                          :class="[
                              'flex-shrink-0 flex items-center gap-2 px-3 py-1.5 rounded-lg text-sm font-medium transition-all',
                              route().current('reflections.*') 
                                ? 'bg-gradient-to-r from-lime-400 to-emerald-500 text-white shadow-md' 
                                : 'bg-white/50 dark:bg-gray-800/50 text-gray-700 dark:text-gray-300 hover:bg-white dark:hover:bg-gray-800'
                          ]">
                        <i class="fas fa-brain text-xs"></i>
                        <span>Réflexions</span>
                        <span v-if="notifications > 0" 
                              class="ml-1 w-4 h-4 bg-red-500 text-white text-[10px] rounded-full flex items-center justify-center">
                            {{ notifications }}
                        </span>
                    </Link>
                </div>
            </div>

            <!-- Mobile Sidebar Overlay -->
            <div v-if="showMobileSidebar" 
                 @click="showMobileSidebar = false"
                 class="lg:hidden fixed inset-0 z-40 bg-black/60 backdrop-blur-sm animate-fade-in"></div>

            <!-- Desktop Navigation -->
            <nav class="hidden lg:block border-b border-lime-200/50 dark:border-emerald-900/50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm">
                <div class="mx-auto px-6">
                    <div class="flex h-16 items-center justify-between">
                        <!-- Desktop Logo -->
                        <div class="flex items-center gap-4">
                            <Link :href="route('dashboard')"
                                  class="flex items-center gap-3 group">
                                <div class="w-10 h-10 bg-gradient-to-br from-lime-400 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg group-hover:rotate-12 transition-transform">
                                    <span class="text-white font-bold text-lg">⚽</span>
                                </div>
                                <div>
                                    <div class="text-lg font-black bg-gradient-to-r from-gray-900 to-emerald-800 dark:from-lime-300 dark:to-emerald-400 bg-clip-text text-transparent">
                                        FC Dynamo
                                    </div>
                                   
                                </div>
                            </Link>

                            <!-- Desktop Navigation Links -->
                            <div class="hidden lg:flex items-center gap-1 ml-8">
                                <NavLink :href="route('dashboard')"
                                         :active="route().current('dashboard')"
                                         class="!px-4 !py-2.5 rounded-lg font-medium">
                                    <i class="fas fa-home mr-2"></i>
                                    Dashboard
                                </NavLink>
                                
                                <NavLink v-if="isAdmin()" 
                                         :href="route('admin.members.index')"
                                         :active="route().current('admin.members.*')"
                                         class="!px-4 !py-2.5 rounded-lg font-medium">
                                    <i class="fas fa-users mr-2"></i>
                                    Membres
                                </NavLink>
                                
                                <NavLink v-if="isAdmin()" 
                                         :href="route('admin.team-stats.index')"
                                         :active="route().current('admin.team-stats.*')"
                                         class="!px-4 !py-2.5 rounded-lg font-medium">
                                    <i class="fas fa-chart-line mr-2"></i>
                                    Stats Équipes
                                </NavLink>
                                
                                <NavLink v-if="isAdmin()" 
                                         :href="route('reflections.index')"
                                         :active="route().current('reflections.*')"
                                         class="!px-4 !py-2.5 rounded-lg font-medium relative">
                                    <i class="fas fa-brain mr-2"></i>
                                    Réflexions
                                    <span v-if="notifications > 0"
                                          class="absolute -top-1 -right-1 w-5 h-5 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-lg animate-pulse">
                                        {{ notifications }}
                                    </span>
                                </NavLink>
                            </div>
                        </div>

                        <!-- Desktop User Menu -->
                        <div class="hidden lg:flex items-center gap-4">
                            <!-- Quick Stats -->
                           

                            <!-- User Dropdown -->
                            <div class="relative z-[9999]">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button class="group flex items-center gap-3 px-3 py-2 rounded-lg hover:bg-lime-50 dark:hover:bg-emerald-900/20 transition-colors">
                                            <div class="relative">
                                                <img v-if="$page.props.auth.user.avatar && $page.props.auth.user.avatar !== ''"
                                                     :src="'/storage/' + $page.props.auth.user.avatar"
                                                     :alt="$page.props.auth.user.name"
                                                     class="w-9 h-9 rounded-full object-cover border-2 border-white dark:border-gray-800 shadow-md"
                                                     @error="handleImageError">
                                                <div v-else 
                                                     class="w-9 h-9 rounded-full bg-gradient-to-br from-lime-400 to-emerald-600 flex items-center justify-center border-2 border-white dark:border-gray-800 shadow-md">
                                                    <i class="fas fa-user text-white text-sm"></i>
                                                </div>
                                                <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-gradient-to-br from-lime-500 to-emerald-700 rounded-full border border-white dark:border-gray-800"></div>
                                            </div>
                                            
                                            <div class="text-left">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white">
                                                    {{ $page.props.auth.user.name }}
                                                </div>
                                                <div class="text-xs text-lime-600 dark:text-emerald-400 font-medium">
                                                    {{ $page.props.auth.user.role || "STRIKER" }}
                                                </div>
                                            </div>
                                            
                                            <i class="fas fa-chevron-down text-gray-400 text-xs group-hover:text-lime-600 dark:group-hover:text-emerald-400"></i>
                                        </button>
                                    </template>

                                    <template #content>
                                        <div class="py-2 z-[9999] relative">
                                            <DropdownLink :href="route('profile.edit')"
                                                         class="!px-4 !py-3 hover:bg-lime-50 dark:hover:bg-emerald-900/20">
                                                <i class="fas fa-user-circle mr-3 text-lime-600 dark:text-emerald-400"></i>
                                                Mon Profil
                                            </DropdownLink>
                                            <DropdownLink :href="route('logout')"
                                                         method="post"
                                                         as="button"
                                                         class="!px-4 !py-3 hover:bg-red-50 dark:hover:bg-red-900/20 text-red-600 dark:text-red-400">
                                                <i class="fas fa-sign-out-alt mr-3"></i>
                                                Quitter l'Arène
                                            </DropdownLink>
                                        </div>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Main Layout -->
            <main class="flex pt-16 lg:pt-0 min-h-screen">
                <!-- Sidebar (Desktop) -->
                <div class="hidden lg:block sticky top-0 h-screen">
                    <AdminsideBar
                        :votes="votes"
                        :reflections="reflections"
                        :Notification="notifications"
                    />
                </div>

                <!-- Mobile Sidebar Drawer -->
                <div :class="[
                    'lg:hidden fixed inset-y-0 left-0 z-40 w-72 transform transition-transform duration-300 ease-in-out',
                    showMobileSidebar ? 'translate-x-0' : '-translate-x-full'
                ]">
                    <AdminsideBar
                        :votes="votes"
                        :reflections="reflections"
                        :Notification="notifications"
                        @close="showMobileSidebar = false"
                    />
                </div>

                <!-- Main Content Area -->
                <div class="flex-1 overflow-y-auto">
                    <!-- Mobile Spacing -->
                    <div class="lg:hidden h-4"></div>
                    
                    <!-- Content -->
                    <div class="scroller min-h-[calc(100vh-4rem)] lg:min-h-screen">
                        <slot :updateNotifications="updateNotifCount()" />
                    </div>
                    
                   
                </div>
            </main>
        </div>
    </div>
</template>



<style scoped>
@keyframes fade-in {
    from {
        opacity: 0;
        backdrop-filter: blur(0);
    }
    to {
        opacity: 1;
        backdrop-filter: blur(8px);
    }
}

.animate-fade-in {
    animation: fade-in 0.2s ease-out forwards;
}

/* Custom NavLink styling for theme */
:deep(.nav-link) {
    @apply relative inline-flex items-center px-3 py-2 text-sm font-medium leading-5 transition-colors duration-150 ease-in-out;
}

:deep(.nav-link-active) {
    @apply text-white bg-gradient-to-r from-lime-400 to-emerald-500 shadow-md;
}

:deep(.nav-link-inactive) {
    @apply text-gray-700 dark:text-gray-300 hover:text-lime-600 dark:hover:text-emerald-400 hover:bg-lime-50 dark:hover:bg-emerald-900/20;
}

/* Scrollbar styling */
.scroller {
    scrollbar-width: thin;
    scrollbar-color: theme('colors.emerald.500') transparent;
}

.scroller::-webkit-scrollbar {
    width: 6px;
}

.scroller::-webkit-scrollbar-track {
    background: transparent;
}

.scroller::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, theme('colors.lime.500'), theme('colors.emerald.500'));
    border-radius: 3px;
}

.scroller::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, theme('colors.lime.600'), theme('colors.emerald.600'));
}

/* Hide scrollbar on mobile */
@media (max-width: 1024px) {
    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
    
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
}

/* Touch optimization */
@media (max-width: 640px) {
    button, a {
        min-height: 44px;
        min-width: 44px;
    }
    
    /* Prevent text selection on interactive elements */
    .select-none {
        user-select: none;
        -webkit-user-select: none;
    }
}

/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, transform, opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}
</style>
