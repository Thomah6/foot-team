<!-- [file name]: AdminsideBar.vue -->
<script setup>
import { computed, defineProps, useAttrs } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";

defineProps({
    Notification: {
        type: Number,
        default: 0,
    },
    votes: Array,
    reflections: Array,
});

const page = usePage();
const user = page.props.auth.user;

// Roles
const isAdmin = () => user.role === "admin";
const isBureau = () => user.role === "bureau";

// Menu dynamique
const menu = computed(() => {
    const items = [
        { label: "Dashboard", icon: "fas fa-tachometer-alt", link: route("dashboard"), active: route().current("dashboard") },
        { label: "Finances", icon: "fas fa-wallet", link: route("finances.index"), active: route().current("finances.*") },
        { label: "Stats", icon: "fas fa-chart-bar", link: route("admin.stats.index"), active: route().current("admin.stats.*") },
        { label: "Classement", icon: "fas fa-trophy", link: route("stats.classements.index"), active: route().current("stats.classements.*") },
        { label: "Présences", icon: "fas fa-calendar-check", link: route("presence.index"), active: route().current("presence.*") },
        { label: "Gallery", icon: "fa-solid fa-photo-film", link: route("galleries.index"), active: route().current("galleries.index") },
        { label: "Règlements", icon: "fas fa-book", link: route("regulations.index"), active: route().current("regulations.index") },
        { label: "Teams", icon: "fas fa-people-group", link: route("admin.teams.index"), active: route().current("admin.teams.*") },
        { label: "SuggestionBox", icon: "fas fa-lightbulb", link: route("suggestions"), active: route().current("suggestions") },
        { label: "Reflections", icon: "fas fa-comments", link: route("reflections.index"), active: route().current("reflections.*") },
        { label: "Historique Votes Perso", icon: "fas fa-archive", link: route("vote.history"), active: route().current("vote.history") },
        { label: "Historique Votes Général", icon: "fas fa-archive", link: route("vote.list.admin"), active: route().current("vote.list.admin") },
    ];

    if (isAdmin()) {
        items.push(
            { label: "Membres", icon: "fas fa-user-friends", link: route("admin.members.index"), active: route().current("admin.members.index") },
            { label: "Statistiques équipes", icon: "fas fa-chart-line", link: route("admin.team-stats.index"), active: route().current("admin.team-stats.*") },
            { label: "Identité visuelle", icon: "fas fa-chart-pie", link: route("admin.identity"), active: route().current("admin.identity*") }
        );
    }

    if (isBureau()) {
        items.push(
            { label: "Membres", icon: "fas fa-user-friends", link: route("bureau.members.index"), active: route().current("bureau.members.index") },
            { label: "Stats des membres", icon: "fas fa-chart-bar", link: route("bureau.stats.index"), active: route().current("bureau.stats.index.*") }
        );
    }

    items.push(
        { label: "Stats des joueurs", icon: "fas fa-signal", link: route("Admin.CreateStats"), active: route().current("Admin.CreateStats*") }
    );

    return items;
});

// Gère le clic sur les liens pour parent layout
const emit = defineEmits(['link-clicked']);
const handleLinkClick = () => emit('link-clicked');

// Logout
const handleLogout = () => router.post('/logout');

const handleImageError = (event) => {
    event.target.src = `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`;
};
</script>

<template>
    <aside class="flex flex-col p-4 lg:p-6 font-inter bg-white dark:bg-gray-900 h-full overflow-y-auto border-r border-gray-200 dark:border-gray-800">
        <!-- User Card -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center gap-3">
                <img
                    :src="user.avatar && user.avatar !== '' ? '/storage/' + user.avatar : `https://ui-avatars.com/api/?name=${user.name}&color=FFFFFF&background=65A30D&size=48`"
                    @error="handleImageError"
                    class="w-12 h-12 rounded-xl object-cover border-2 border-white dark:border-gray-800 shadow-lg"
                />
                <div class="flex-1 min-w-0">
                    <h2 class="text-base font-bold text-gray-900 dark:text-white truncate">{{ user.name }}</h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gradient-to-r from-lime-500 to-emerald-600 text-white">{{ user.role || "STRIKER" }}</span>
                        <span class="text-xs text-emerald-600 dark:text-lime-400 font-medium">{{ user.stats?.goals || 0 }}⚽</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Menu -->
        <nav class="flex flex-col gap-1.5 flex-1">
            <Link
                v-for="(item, index) in menu"
                :key="index"
                :href="item.link"
                class="group flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:translate-x-1"
                :class="item.active ? 'bg-gradient-to-r from-lime-400/20 to-emerald-500/20 border-l-4 border-emerald-500 dark:border-lime-400 shadow-sm' : 'hover:bg-gray-100 dark:hover:bg-gray-800 border-l-4 border-transparent'"
                @click="handleLinkClick"
            >
                <div :class="['w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300', item.active ? 'bg-gradient-to-br from-lime-400 to-emerald-600 shadow-lg' : 'bg-gray-100 dark:bg-gray-800 group-hover:bg-gradient-to-br group-hover:from-lime-300 group-hover:to-emerald-500']">
                    <i :class="[item.icon, item.active ? 'text-white' : 'text-gray-600 dark:text-gray-400 group-hover:text-white']"></i>
                </div>
                <p class="text-sm font-semibold truncate" :class="item.active ? 'text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white'">{{ item.label }}</p>

                <!-- Badge Reflections -->
                <div v-if="item.label === 'Reflections' && Notification > 0" class="animate-pulse ml-auto">
                    <div class="relative">
                        <div class="w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-xs font-bold text-white">{{ Notification }}</span>
                        </div>
                        <div class="absolute inset-0 bg-red-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                </div>
            </Link>
        </nav>

        <!-- Bottom Logout -->
        <div class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-800">
            <button @click="handleLogout" class="group w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gradient-to-r hover:from-red-500/10 hover:to-red-600/10 border border-red-200 dark:border-red-900/30 transition-all duration-300">
                <div class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-red-500 group-hover:to-red-600 transition-all">
                    <i class="fas fa-sign-out-alt text-sm text-red-500 dark:text-red-400 group-hover:text-white"></i>
                </div>
                <p class="text-sm font-medium text-red-600 dark:text-red-400 group-hover:text-white">Déconnexion</p>
            </button>
        </div>
    </aside>
</template>

<style scoped>
nav::-webkit-scrollbar {
    width: 4px;
}
nav::-webkit-scrollbar-track { background: rgba(203, 213, 225, 0.1); border-radius: 10px; }
nav::-webkit-scrollbar-thumb { background: linear-gradient(to bottom, rgb(132, 204, 22), rgb(16, 185, 129)); border-radius: 10px; }
nav::-webkit-scrollbar-thumb:hover { background: linear-gradient(to bottom, rgb(101, 163, 13), rgb(5, 150, 105)); }
.dark nav::-webkit-scrollbar-track { background: rgba(31, 41, 55, 0.1); }
</style>
