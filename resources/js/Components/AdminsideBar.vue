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
        {
            label: "Dashboard",
            icon: "fas fa-tachometer-alt",
            link: route("dashboard"),
            active: route().current("dashboard"),
        },
        {
            label: "Stats des membres",
            icon: "fas fa-table",
            link: route("bureau.stats.index"),
            active: route().current("bureau.stats.index.*"),
        },
        {
            label: "Finances",
            icon: "fas fa-wallet",
            link: route("finances.index"),
            active: route().current("finances.*"),
        },
        // { label: "Stats", icon: "fas fa-chart-bar", link: route('admin.stats.index'), active: route().current('admin.stats.*') },
        // { label: "Voir classements", icon: "fas fa-trophy", link: route('stats.classements.index'), active: route().current('stats.classements.*') },
        {
            label: "Profile",
            icon: "fas fa-user",
            link: route("profile.edit"),
            active: route().current("profile.edit"),
        },
        {
            label: "Gallery",
            icon: "fa-solid fa-photo-film",
            link: route("galleries.index"),
            active: route().current("galleries.index"),
        },

        {
            label: "Stats des membres",
            icon: "fas fa-users",
            link: route("bureau.stats.index"),
            active: route().current("bureau.stats.index.*"),
        },
        {
            label: "Finances",
            icon: "fas fa-wallet",
            link: route("finances.index"),
            active: route().current("finances.*"),
        },
        {
            label: "Profile",
            icon: "fas fa-user",
            link: route("profile.edit"),
            active: route().current("profile.edit"),
        },
        {
            label: "Présences",
            icon: "fas fa-calendar-check",
            link: route("presence.index"),
            active: route().current("presence.*"),
        },
        {
            label: "Teams",
            icon: "fas fa-people-group",
            link: route("admin.teams"),
            active: route().current("admin.teams"),
        },
        {
            label: "SuggestionBox",
            icon: "fas fa-lightbulb",
            link: route("suggestions"),
            active: route().current("suggestions"),
        },
    ];

    // 👉 Ajouter l’item "membres" *seulement si admin ou bureau*
    if (isAdmin()) {
        items.push(
            {
                label: "Membres",
                icon: "fas fa-user-friends",
                link: route("members.index"),
                active: route().current("members.index"),
            },
            {
                label: "Statisques des Équipes",
                icon: "fas fa-chart-line",
                link: route("admin.team-stats.index"),
                active: route().current("admin.team-stats.index"),
            }
        );
    }
    if (isBureau()) {
        items.push({
            label: "Membres",
            icon: "fas fa-user-friends",
            link: route("bureau.members.index"),
            active: route().current("bureau.members.index"),
        });
    }

    items.push({
        label: "Membres",
        icon: "fas fa-user-friends",
        link: route('bureau.members.index'),
        active: route().current('bureau.members.index')
    });

    items.push({
        label: 'Stats des membres',
        icon: 'fas fa-chart-bar',
        link: route('bureau.stats.index'),
        active: route().current('bureau.stats.index.*')
    });




  return items;
});

const bottomMenu = [
    { label: "Settings", icon: "fas fa-cog", link: route("profile.edit") },
];

const toggleMenu = () => {
    isOpen.value = !isOpen.value;
};

const closeMenu = () => {
    isOpen.value = false;
};

const handleImageError = (event) => {
    // Si l'image ne charge pas, utiliser l'avatar par défaut
    event.target.src = `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`;
};
</script>

<template>
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <!-- Menu hamburger pour mobile -->
    <button v-if="!isOpen" @click="toggleMenu"
        class="lg:hidden fixed top-4 left-4 z-40 p-3 bg-white rounded-lg shadow-lg border hover:bg-gray-50 transition">
        <i class="fas fa-bars text-gray-700"></i>
    </button>

    <!-- Overlay pour mobile -->
    <div v-if="isOpen" @click="closeMenu" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-30"></div>

    <!-- Sidebar -->
    <aside :class="[
        'fixed lg:static inset-y-0 left-0 z-40 transform transition-transform duration-300 ease-in-out',
        'flex w-64 flex-col border-r border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-4 font-inter',
        'backdrop-blur-sm bg-white/95 dark:bg-gray-900/95 h-[100vh]',
        isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
    ]">
        <!-- Logo -->
        <div class="flex items-center justify-between px-2 py-2">
            <div class="flex items-center gap-3">
                <div class="relative">
                    <img :src="user.avatar && user.avatar !== ''
                            ? '/storage/' + user.avatar
                            : `https://ui-avatars.com/api/?name=${user.name}&color=7F9CF5&background=EBF4FF&size=40`
                        " :alt="user.name"
                        class="w-10 h-10 rounded-lg object-cover border-2 border-white dark:border-gray-700 shadow-sm"
                        @error="handleImageError" />
                    <div
                        class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-700">
                    </div>
                </div>
                <div>
                    <h1 class="text-base font-bold text-text-primary-light dark:text-text-primary-dark">
                        {{ user.name }}
                    </h1>
                    <p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">
                        {{ user.role || "Member" }}
                    </p>
                </div>
            </div>

            <!-- Bouton close pour mobile -->
            <button @click="closeMenu"
                class="lg:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                <i class="fas fa-times text-gray-600 dark:text-gray-300"></i>
            </button>
        </div>

        <!-- Navigation Items -->
        <nav class="flex flex-col gap-2 mt-4 flex-1 overflow-y-auto">
            <Link v-for="(item, index) in menu" :key="index" :href="item.link" @click="closeMenu"
                class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors" :class="item.active
                        ? 'bg-blue-500/20 text-blue-600'
                        : 'hover:bg-blue-500/10 text-text-primary-light dark:text-text-primary-dark'
                    ">
            <i :class="item.icon" class="text-lg w-5 text-center"></i>
            <p class="text-sm font-medium">{{ item.label }}</p>
            </Link>

            <Link :href="menu[2].link" class="flex items-center gap-3 px-3 py-2 rounded-md transition-colors" :class="menu[2].active
                    ? 'bg-blue-500/20 text-blue-600'
                    : 'hover:bg-blue-500/10 text-text-primary-light dark:text-text-primary-dark'
                ">
            <i :class="menu[2].icon" class="text-lg"></i>
            <p class="text-sm font-medium">{{ menu[2].label }}</p>
            </Link>
        </nav>

        <!-- Bottom -->
        <div class="mt-auto flex flex-col gap-2 border-t border-gray-200 dark:border-gray-700 pt-4">
            <Link v-for="(item, index) in bottomMenu" :key="`bottom-${index}`" :href="item.link" @click="closeMenu"
                class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-blue-500/10 transition-colors text-text-primary-light dark:text-text-primary-dark">
            <i :class="item.icon" class="text-lg w-5 text-center"></i>
            <p class="text-sm font-medium">{{ item.label }}</p>
            </Link>
        </div>
    </aside>
</template>

<style scoped>
.font-inter {
    font-family: "Inter", sans-serif;
}

/* Animation pour les transitions fluides */
.transition-colors {
    transition: background-color 0.2s ease, color 0.2s ease;
}

/* Style pour les icônes Font Awesome */
i.fas {
    width: 1.25rem;
    text-align: center;
}

/* Responsive styles */
@media (max-width: 1023px) {
    aside {
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    }
}

/* Animation de transition pour la sidebar */
.transform {
    transition: transform 0.3s ease-in-out;
}

/* Z-index management */
.z-40 {
    z-index: 40;
}

.z-30 {
    z-index: 30;
}

/* Background fix pour sidebar */
aside {
    background-color: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(8px);
    -webkit-backdrop-filter: blur(8px);
}

.dark aside {
    background-color: rgba(17, 24, 39, 0.98);
}

/* Assurer que la sidebar est au-dessus sur mobile */
@media (max-width: 1023px) {
    aside {
        box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
        background-color: rgba(255, 255, 255, 1);
    }

    .dark aside {
        background-color: rgba(17, 24, 39, 1);
    }
}

/* Scrollbar styling pour la navigation */
.overflow-y-auto::-webkit-scrollbar {
    width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(0, 0, 0, 0.1);
    border-radius: 2px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(0, 0, 0, 0.2);
}

/* Dark mode scrollbar */
.dark .overflow-y-auto::-webkit-scrollbar-thumb {
    background: rgba(255, 255, 255, 0.1);
}

.dark .overflow-y-auto::-webkit-scrollbar-thumb:hover {
    background: rgba(255, 255, 255, 0.2);
}
</style>
