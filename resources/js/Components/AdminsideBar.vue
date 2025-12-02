<!-- [file name]: AdminsideBar.vue
[file content begin] -->
<script setup>
import { ref, computed, defineProps, onMounted, onUnmounted } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
defineProps({
    Notification: {
        type: Number,
        default: 0,
        required: false,
    },
    votes: Array,
    reflections: Array,
});
// import VoteHistory from "../Pages/VoteHistory.vue";
// import VoteListAdmin from "../Pages/VoteListAdmin.vue";

const page = usePage();
const user = page.props.auth.user;

const isAdmin = () => page.props.auth.user.role === "admin";
const isBureau = () => page.props.auth.user.role === "bureau";

const isOpen = ref(false);
const menu = computed(() => {
    // Menu de base (unique, sans doublons)
    const items = [
        {
            label: "Dashboard",
            icon: "fas fa-tachometer-alt",
            link: route("dashboard"),
            active: route().current("dashboard"),
        },
        // {
        //     label: "Stats des membres",
        //     icon: "fas fa-table",
        //     link: route("bureau.stats.index", [], false), // false pour forcer le chemin absolu
        //     active: route().current("bureau.stats.index.*"),
        // },
        {
            label: "Finances",
            icon: "fas fa-wallet",
            link: route("finances.index"),
            active: route().current("finances.*"),
        },
        { label: "Stats", icon: "fas fa-chart-bar", link: route('admin.stats.index'), active: route().current('admin.stats.*') },
        { label: "Classement", icon: "fas fa-trophy", link: route('stats.classements.index'), active: route().current('stats.classements.*') },
        {
            label: "Présences",
            icon: "fas fa-calendar-check",
            link: route('presence.index'),
            active: route().current('presence.*') || route().current('presence.index'),
        },

        {
            label: "Gallery",
            icon: "fa-solid fa-photo-film",
            link: route("galleries.index"),
            active: route().current("galleries.index"),
        },
        {
            label: "Regulations",
            icon: "fas fa-book",
            link: route('regulations.index'),
            active: route().current('regulations.index'),
        },
        {

            label: "Teams",
            icon: "fas fa-people-group",
            link: route("admin.teams.index"),
            active: route().current("admin.teams.*"),
        },
        {
            label: "SuggestionBox",
            icon: "fas fa-lightbulb",
            link: route("suggestions"),
            active: route().current("suggestions"),
        },
        {
            label: "Reflections",
            icon: "fas fa-comments",
            link: route("reflections.index"),
            active: route().current("reflections.*"),
        },
        {
            label: "Historique des Votes Personnels",
            icon: "fas fa-archive",
            link: route("vote.history"),
            active: route().current("vote.history"),
        },
        {
            label: "Historique général des Votes",
            icon: "fas fa-archive",
            link: route("vote.list.admin"),
            active: route().current("vote.list.admin"),
        },
    ];


    // Items conditionnels


    if (isAdmin()) {
        items.push(
            {
                label: "Membres",
                icon: "fas fa-user-friends",
                link: route("admin.members.index"),
                active: route().current("admin.members.index"),
            },

            {
                label: 'Stats des membres',
                icon: 'fas fa-chart-bar',
                link: route('admin.bureau.stats.index', [], false),
                active: route().current('admin.bureau.stats.index.*')
            },
            {
                label: "Statistiques équipes",
                icon: "fas fa-chart-line",
                link: route("admin.team-stats.index"),
                active: route().current("admin.team-stats.*"),
            },
            {
                label: "Identité visuelle",
                icon: "fas fa-chart-pie",
                link: route("admin.identity"),
                active: route().current("admin.identity*"),
            }
        );
    }
    if (isBureau()) {
        items.push({
            label: "Membres",
            icon: "fas fa-user-friends",
            link: route("admin.bureau.members.index"),
            active: route().current("admin.bureau.members.index"),
        });

        items.push({
            label: 'Stats des membres',
            icon: 'fas fa-chart-bar',
            link: route('bureau.stats.index', [], false),
            active: route().current('bureau.stats.index.*')
        });


    }

    items.push({
        label: 'Stats des joueurs',
        icon: 'fas fa-signal',
        link: route('Admin.CreateStats', [], false),
        active: route().current('Admin.CreateStats*')
    });
    // Correction principale : toujours retourner le menu




    return items;


}
);

// Bottom menu (profil, paramètres, déconnexion...) - keep empty by default to avoid undefined in template
const bottomMenu = [];




const handleLinkClick = () => {
    // Ne fermer le menu que si la sidebar mobile est ouverte
    if (isOpen.value) {
        closeMenu();
    }
};

const navigate = (href) => {
    // debug log
    // eslint-disable-next-line no-console
    console.log('[AdminsideBar] navigate ->', href);
    // Use Inertia router to perform a proper visit
    router.get(href);
    // Close mobile menu after navigation
    if (isOpen.value) closeMenu();
};

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
    // Empêche le défilement du body quand le menu est ouvert
    document.body.style.overflow = isOpen.value ? 'hidden' : '';
};

const closeMenu = () => {
    isOpen.value = false;
    document.body.style.overflow = '';
};

// Fermer le menu quand on clique en dehors
const handleClickOutside = (event) => {
    const sidebar = document.querySelector('aside');
    const button = document.querySelector('button[aria-expanded]');
    
    if (isOpen.value && !sidebar.contains(event.target) && !button.contains(event.target)) {
        closeMenu();
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && isOpen.value) {
            closeMenu();
        }
    });
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
    document.body.style.overflow = ''; // S'assurer de réactiver le défilement
});

const handleImageError = (event) => {
    event.target.src = `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`;
};

// Gestionnaire d'événement pour le toggle global
const handleToggleSidebar = () => {
    isOpen.value = !isOpen.value;
};

// Ajouter l'écouteur d'événement au montage du composant
onMounted(() => {
    window.addEventListener('toggle-sidebar', handleToggleSidebar);
});

// Nettoyer l'écouteur d'événement au démontage
onUnmounted(() => {
    window.removeEventListener('toggle-sidebar', handleToggleSidebar);
});

// Fonction pour gérer la déconnexion
const handleLogout = () => {
    router.post('/logout');
};
</script>

<template>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Mobile Overlay -->
    <div v-if="isOpen" @click="closeMenu"
        class="lg:hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40 transition-opacity duration-300"
        :class="{ 'opacity-100': isOpen, 'opacity-0 pointer-events-none': !isOpen }"
        @touchmove.prevent>
    </div>

    <!-- Mobile Hamburger Button -->
    <button v-if="!isOpen" @click="toggleMenu"
        class="md:hidden fixed top-4 left-4 z-[9999] p-3 bg-gradient-to-br from-lime-500 to-emerald-600 rounded-xl shadow-lg hover:shadow-emerald-500/30 transform hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500"
        aria-label="Ouvrir le menu">
        <i class="fas fa-bars text-white text-xl"></i>
    </button>

    <!-- Sidebar -->
    <aside :class="[
        'w-full lg:w-72 h-full flex flex-col p-4 lg:p-6 font-inter',
        'bg-white dark:bg-gray-900 backdrop-blur-xl',
        'overflow-y-auto',
        'fixed lg:static left-0 top-0 transform transition-transform duration-300 ease-in-out z-40',
        'border-r border-gray-200 dark:border-gray-800',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
    ]"
    :aria-expanded="isOpen"
    :aria-hidden="!isOpen"
    @click.stop>
        
        <!-- Mobile Header -->
        <div class="mb-6 lg:mb-8">
            <div class="flex items-center justify-between mb-4 lg:hidden">
                <h1 class="text-lg font-bold text-gray-900 dark:text-white">Menu</h1>
                <button @click="closeMenu"
                    class="p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                    <i class="fas fa-times text-gray-600 dark:text-gray-400 text-lg"></i>
                </button>
            </div>

            <!-- User Card -->
            <div
                class="relative overflow-hidden rounded-xl bg-gradient-to-r from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 p-4 border border-gray-200 dark:border-gray-700 shadow-sm">
                <div
                    class="absolute top-0 right-0 w-16 h-16 bg-gradient-to-br from-lime-400/20 to-emerald-600/20 rounded-full -translate-y-8 translate-x-4">
                </div>

                <div class="flex items-center gap-3 relative z-10">
                    <div class="relative">
                        <img :src="user.avatar && user.avatar !== '' ? '/storage/' + user.avatar : `https://ui-avatars.com/api/?name=${user.name}&color=FFFFFF&background=65A30D&size=48`"
                            @error="handleImageError"
                            class="w-12 h-12 rounded-xl object-cover border-2 border-white dark:border-gray-800 shadow-lg" />
                        <div
                            class="absolute -bottom-1 -right-1 w-4 h-4 bg-gradient-to-br from-lime-500 to-emerald-700 rounded-full border-2 border-white dark:border-gray-800 shadow-lg">
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h2 class="text-base font-bold text-gray-900 dark:text-white truncate">{{ user.name }}</h2>
                        <div class="flex items-center gap-2 mt-1">
                            <span
                                class="text-xs font-semibold px-2 py-0.5 rounded-full bg-gradient-to-r from-lime-500 to-emerald-600 text-white">
                                {{ user.role || "STRIKER" }}
                            </span>
                            <span class="text-xs text-emerald-600 dark:text-lime-400 font-medium">
                                {{ user.stats?.goals || 0 }}⚽
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation -->
        <nav class="flex flex-col gap-1.5 flex-1 overflow-y-auto pb-4">
            <div class="px-2 mb-3">
                <span class="text-xs font-bold uppercase tracking-wider text-emerald-600 dark:text-lime-400">
                    Accès rapide
                </span>
                <div class="h-0.5 w-12 bg-gradient-to-r from-lime-400 to-emerald-500 rounded-full mt-1"></div>
            </div>

            <Link v-for="(item, index) in menu" :key="index" :href="item.link"
                class="group relative flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-300 hover:translate-x-1"
                :class="item.active
                    ? 'bg-gradient-to-r from-lime-400/20 to-emerald-500/20 border-l-4 border-emerald-500 dark:border-lime-400 shadow-sm'
                    : 'hover:bg-gray-100 dark:hover:bg-gray-800 border-l-4 border-transparent'
                    "
                @click="handleLinkClick">
                <!-- Icon -->
                <div :class="[
                    'w-9 h-9 rounded-lg flex items-center justify-center transition-all duration-300',
                    item.active
                        ? 'bg-gradient-to-br from-lime-400 to-emerald-600 shadow-lg'
                        : 'bg-gray-100 dark:bg-gray-800 group-hover:bg-gradient-to-br group-hover:from-lime-300 group-hover:to-emerald-500'
                ]">
                    <i :class="[item.icon, item.active
                        ? 'text-white'
                        : 'text-gray-600 dark:text-gray-400 group-hover:text-white']"></i>
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold truncate" :class="item.active
                        ? 'text-gray-900 dark:text-white'
                        : 'text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-white'">
                        {{ item.label }}
                    </p>
                    <p v-if="item.description" class="text-xs text-gray-500 dark:text-gray-400 truncate mt-0.5">
                        {{ item.description }}
                    </p>
                </div>

                <!-- Badges -->
                <div v-if="item.label === 'Reflections' && Notification > 0" class="animate-pulse">
                    <div class="relative">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-red-500 to-red-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-xs font-bold text-white">{{ Notification }}</span>
                        </div>
                        <div class="absolute inset-0 bg-red-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                </div>

                <div v-if="item.label === 'Membres' && $page.props.inactiveUsersCount > 0" class="animate-pulse">
                    <div class="relative">
                        <div
                            class="w-6 h-6 bg-gradient-to-br from-amber-500 to-amber-600 rounded-full flex items-center justify-center shadow-lg">
                            <span class="text-xs font-bold text-white">{{ $page.props.inactiveUsersCount }}</span>
                        </div>
                        <div class="absolute inset-0 bg-amber-400 rounded-full animate-ping opacity-75"></div>
                    </div>
                </div>

                <!-- Active arrow -->
                <div v-if="item.active" class="ml-2">
                    <i class="fas fa-chevron-right text-xs text-emerald-500 dark:text-lime-400"></i>
                </div>
            </Link>
        </nav>

        <!-- Bottom Section -->
        <div class="mt-auto pt-6 border-t border-gray-200 dark:border-gray-800">
            <div class="space-y-1.5">
                <!-- Logout -->
                <button @click="handleLogout"
                    class="group w-full flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gradient-to-r hover:from-red-500/10 hover:to-red-600/10 border border-red-200 dark:border-red-900/30 transition-all duration-300">
                    <div
                        class="w-8 h-8 rounded-lg bg-red-100 dark:bg-red-900/20 flex items-center justify-center group-hover:bg-gradient-to-br group-hover:from-red-500 group-hover:to-red-600 transition-all">
                        <i class="fas fa-sign-out-alt text-sm text-red-500 dark:text-red-400 group-hover:text-white"></i>
                    </div>
                    <p class="text-sm font-medium text-red-600 dark:text-red-400 group-hover:text-white">
                        Déconnexion
                    </p>
                </button>
            </div>
        </div>
    </aside>
</template>

<style scoped>
/* Smooth transitions */
* {
    transition-property: background-color, border-color, color, fill, stroke, transform, opacity;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 200ms;
}

/* Scrollbar styling */
nav::-webkit-scrollbar {
    width: 4px;
}

nav::-webkit-scrollbar-track {
    background: rgba(203, 213, 225, 0.1);
    border-radius: 10px;
}

nav::-webkit-scrollbar-thumb {
    background: linear-gradient(to bottom, rgb(132, 204, 22), rgb(16, 185, 129));
    border-radius: 10px;
}

nav::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(to bottom, rgb(101, 163, 13), rgb(5, 150, 105));
}

/* Dark mode scrollbar */
.dark nav::-webkit-scrollbar-track {
    background: rgba(31, 41, 55, 0.1);
}

@keyframes pulse-glow {
    0%, 100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.8;
        transform: scale(1.05);
    }
}

.animate-pulse {
    animation: pulse-glow 2s infinite;
}
</style>
<!-- [file content end] -->