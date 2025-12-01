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
        {
            label: "Stats des membres",
            icon: "fas fa-table",
            link: route("bureau.stats.index", [], false), // false pour forcer le chemin absolu
            active: route().current("bureau.stats.index.*"),
        },
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

const toggleMenu = () => isOpen.value = !isOpen.value;
const closeMenu = () => isOpen.value = false;

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
            <Link v-for="(item, index) in menu" :key="index" :href="item.link"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors" :class="item.active
                    ? 'bg-blue-500/20 text-blue-600'
                    : 'hover:bg-blue-500/10 text-text-primary-light dark:text-text-primary-dark'
                    ">
            <i :class="item.icon" class="text-lg w-5 text-center"></i>
            <p class="text-sm font-medium">{{ item.label }}</p>
            <span v-if="item.label === 'Reflections' && Notification > 0" class="ml-2 inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full text-xs font-bold text-white bg-red-500">
                {{ Notification }}
            </span>
            <span v-if="item.label === 'Membres' && $page.props.inactiveUsersCount > 0" class="ml-2 inline-flex items-center justify-center h-5 min-w-[20px] px-1.5 rounded-full text-xs font-bold text-white bg-amber-500">
                {{ $page.props.inactiveUsersCount }}
            </span>
            <p class="text-sm font-medium dark:text-gray-100">{{ item.label }}</p><span v-if="item.label === 'Reflections'">
                {{ Notification}}</span>
            </Link>
        </nav>





        <div class="mt-auto flex flex-col gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
            <Link v-for="(item, index) in bottomMenu" :key="`bottom-${index}`" :href="item.link"
                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-500/10 transition-colors text-text-primary-light dark:text-text-primary-dark">
            <i :class="item.icon" class="text-lg w-5 text-center"></i>
            <p class="text-sm font-medium">{{ item.label }}</p>
            </Link>

            <!-- Bouton logout -->
            <button @click="handleLogout"
                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-red-500/10 transition-colors text-red-600 dark:text-red-400 w-full">
                <i class="fas fa-sign-out-alt text-lg w-5 text-center"></i>
                <p class="text-sm font-medium">Déconnexion</p>
            </button>
        </div>

    </aside>
</template>
