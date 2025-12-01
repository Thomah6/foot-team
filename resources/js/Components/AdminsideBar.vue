<script setup>
import { ref, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";

const page = usePage();
const user = page.props.auth.user;

const isAdmin = () => page.props.auth.user.role === "admin";
const isBureau = () => page.props.auth.user.role === "bureau";

const isOpen = ref(false);

const menu = computed(() => {
    const items = [
        { label: "Dashboard", icon: "fas fa-tachometer-alt", link: route("dashboard"), active: route().current("dashboard") },
        { label: "Stats des membres", icon: "fas fa-table", link: route("bureau.stats.index"), active: route().current("bureau.stats.index.*") },
        { label: "Finances", icon: "fas fa-wallet", link: route("finances.index"), active: route().current("finances.*") },
        { label: "Profile", icon: "fas fa-user", link: route("profile.edit"), active: route().current("profile.edit") },
        { label: "Gallery", icon: "fa-solid fa-photo-film", link: route("galleries.index"), active: route().current("galleries.index") },
        { label: "Présences", icon: "fas fa-calendar-check", link: route("presence.index"), active: route().current("presence.*") },
        { label: "Regulations", icon: "fas fa-user", link: route('regulations.index'), active: route().current('regulations.index') },
        { label: "Teams", icon: "fas fa-people-group", link: route("admin.teams"), active: route().current("admin.teams") },
        { label: "SuggestionBox", icon: "fas fa-lightbulb", link: route("suggestions"), active: route().current("suggestions") },
    ];

    if (isAdmin() || isBureau()) {
        items.push(
            { label: "Membres", icon: "fas fa-user-friends", link: route("members.index"), active: route().current("members.index") },
            { label: "Stats Équipes", icon: "fas fa-chart-line", link: route("admin.team-stats.index"), active: route().current("admin.team-stats.index") }
        );
    }

    items.push(
        { label: "Stats Joueurs", icon: "fas fa-chart-bar", link: route('Admin.CreateStats'), active: route().current('Admin.CreateStats') }
    );

    return items;
});

const bottomMenu = [
    { label: "Settings", icon: "fas fa-cog", link: route("profile.edit") },
];

const toggleMenu = () => isOpen.value = !isOpen.value;
const closeMenu = () => isOpen.value = false;

const handleImageError = (event) => {
    event.target.src = `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`;
};
</script>

<template>
    <!-- Hamburger pour mobile -->
    <button v-if="!isOpen" @click="toggleMenu"
        class="lg:hidden fixed top-4 left-4 z-50 p-3 bg-white dark:bg-gray-800 rounded-lg shadow hover:bg-gray-100 dark:hover:bg-gray-700 transition">
        <i class="fas fa-bars text-gray-700 dark:text-gray-200"></i>
    </button>

    <!-- Overlay mobile -->
    <div v-if="isOpen" @click="closeMenu" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-40"></div>

    <!-- Sidebar -->
    <aside :class="[
        'fixed lg:static inset-y-0 left-0 z-50 w-64 flex flex-col p-4 font-inter border-r border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900 h-screen transform transition-transform duration-300 ease-in-out',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]">

        <!-- Logo + User -->
        <div class="flex items-center justify-between px-2 py-2">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img :src="user.avatar && user.avatar !== '' ? '/storage/' + user.avatar : `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`"
                         @error="handleImageError"
                         class="w-10 h-10 rounded-lg object-cover border-2 border-white dark:border-gray-700 shadow-sm"/>
                    <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-700"></div>
                </div>
                <div>
                    <h1 class="text-base font-bold text-gray-900 dark:text-white">{{ user.name }}</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ user.role || "Member" }}</p>
                </div>
            </div>

            <button @click="closeMenu" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <i class="fas fa-times text-gray-600 dark:text-gray-300"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-2 mt-4 flex-1 overflow-y-auto">
            <Link v-for="(item, index) in menu" :key="index" :href="item.link" @click="closeMenu"
                :class="[
                    'flex items-center gap-3 px-3 py-2 rounded-md transition-colors',
                    item.active
                        ? 'bg-blue-500/20 text-blue-600 dark:text-blue-400'
                        : 'hover:bg-blue-500/10 text-gray-900 dark:text-gray-200'
                ]">
                <i :class="item.icon" class="text-lg w-5 text-center"></i>
                <p class="text-sm font-medium">{{ item.label }}</p>
            </Link>
        </nav>

        <!-- Bottom Menu -->
        <div class="mt-auto flex flex-col gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
            <Link v-for="(item, index) in bottomMenu" :key="`bottom-${index}`" :href="item.link" @click="closeMenu"
                  class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-500/10 text-gray-900 dark:text-gray-200 transition-colors">
                <i :class="item.icon" class="text-lg w-5 text-center"></i>
                <p class="text-sm font-medium">{{ item.label }}</p>
            </Link>
        </div>

    </aside>
</template>
