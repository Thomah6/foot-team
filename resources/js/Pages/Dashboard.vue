<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BannerPlayerMonth from '@/Pages/Admin/News/BannerPlayerMonth.vue';
import ALaUne from '@/Pages/Admin/News/ALaUne.vue';



function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
const page = usePage();
const identity = page.props.identity;

</script>

<template>

    <Head title="Arène Dynamo - Dashboard" />

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

    <AuthenticatedLayout>
        <div
            class="min-h-screen w-full bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-gray-100">
            <!-- Hero Section Mobile-First -->
            <div class="relative w-full overflow-hidden">
                <!-- Background Pattern -->
                <div
                    class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iIzExMTExMSIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjMTExMTExIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')] opacity-20 dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlxsD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')]">
                </div>

                <!-- Content -->
                <div class="relative px-4 py-6 md:px-8 md:py-12">
                    <!-- Mobile Header -->
                    <div class="lg:hidden mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="flex items-center gap-3">
                                    <!-- Logo -->
                                    <div
                                        class="w-12 h-12 rounded-xl overflow-hidden bg-gray-200 dark:bg-gray-700 shadow-lg flex items-center justify-center">
                                        <img v-if="identity?.logo"
                                            :src="identity.logo.startsWith('/storage') ? identity.logo : '/storage/' + identity.logo"
                                            class="w-full h-full object-cover" alt="Logo club">
                                        <span v-else
                                            class="text-2xl font-black text-white bg-gradient-to-br from-lime-400 to-emerald-600 w-full h-full flex items-center justify-center">
                                            ⚽
                                        </span>
                                    </div>
 
                                    <!-- Nom -->
                                    <div>
                                        <h1
                                            class="text-xl font-black bg-gradient-to-r from-gray-900 to-emerald-800 dark:from-lime-300 dark:to-emerald-400 bg-clip-text text-transparent">
                                            {{ identity?.name ?? 'Nom du Club' }}
                                        </h1>
                                        <p class="text-xs font-medium text-lime-600 dark:text-emerald-400">
                                            Arène Égoïste
                                        </p>
                                    </div>
                                </div>
 
                            </div>
                            <Link href="/joueur-du-mois"
                                class="flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-lime-600 rounded-lg text-white font-bold text-sm shadow-lg hover:shadow-emerald-500/30 transform hover:scale-105 transition-all">
                            <span>Voir stats</span>
                            <i class="fas fa-chevron-right text-xs"></i>
                            </Link>
                        </div>
                    </div>
 
                    <!-- Hero Card -->
                    <div
                        class="relative overflow-hidden rounded-2xl bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border border-lime-200/50 dark:border-emerald-800/50 shadow-xl">
                        <!-- Decorative Elements -->
                        <div
                            class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-lime-400/10 to-emerald-600/10 rounded-full -translate-y-16 translate-x-16">
                        </div>
                        <div
                            class="absolute bottom-0 left-0 w-24 h-24 bg-gradient-to-br from-emerald-400/10 to-lime-500/10 rounded-full translate-y-12 -translate-x-12">
                        </div>
 
                        <div class="relative p-6 md:p-8">
                            <div class="flex flex-col md:flex-row items-center md:items-start justify-between gap-6">
                                <div class="flex-1 max-w-2xl text-center md:text-left">
                                    <div
                                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-gradient-to-r from-lime-100 to-emerald-100 dark:from-emerald-900/30 dark:to-lime-900/30 border border-lime-300/30 dark:border-emerald-700/30 mb-4">
                                        <div
                                            class="w-2 h-2 bg-gradient-to-r from-lime-500 to-emerald-500 rounded-full animate-pulse">
                                        </div>
                                        <span class="text-xs font-bold text-lime-700 dark:text-emerald-400">NOUVELLE
                                            SAISON</span>
                                    </div>
 
                                    <h2 class="text-2xl md:text-3xl font-extrabold leading-tight mb-4">
                                        <span
                                            class="bg-gradient-to-r from-gray-900 to-emerald-800 dark:from-lime-300 dark:to-emerald-400 bg-clip-text text-transparent">
                                            Bienvenue au FC <span>{{ identity?.name ?? 'Nom du Club' }}</span> !
                                        </span>
                                    </h2>
 
                                    <p
                                        class="text-gray-700 dark:text-gray-300 text-base md:text-lg mb-6 leading-relaxed">
                                        Zone de haute intensité du FC <span>{{ identity?.name ?? 'Nom du Club' }}</span>. Suivez les stats en temps réel, analysez
                                        chaque mouvement, et forgez votre <span
                                            class="font-bold text-emerald-700 dark:text-lime-400">EGO de
                                            champion</span>. Votre destin commence ici.
                                    </p>
 
 
                                </div>
 
                                <!-- CTA Button -->
                                <div class="flex-shrink-0">
                                    <Link href="/joueur-du-mois"
                                        class="group relative flex items-center gap-3 px-6 py-4 bg-gradient-to-r from-emerald-600 to-lime-600 text-white font-bold rounded-xl shadow-lg hover:shadow-emerald-500/30 transform hover:scale-105 transition-all duration-300 overflow-hidden">
                                    <span class="relative z-10">
                                        <span class="block text-sm">Joueur</span>
                                        <span class="block text-lg">du mois</span>
                                    </span>
                                    <i
                                        class="fas fa-arrow-right text-lg group-hover:translate-x-1 transition-transform"></i>
                                    <div
                                        class="absolute inset-0 bg-gradient-to-r from-emerald-700 to-lime-700 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                                    </div>
                                    </Link>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center mt-3">
                                        Découvrez le joueur du mois
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
 
            </div>

            <!-- Main Content Grid -->
            <div class="px-4 md:px-8 pb-8">
                <!-- Mobile Quick Actions -->
                <div class="lg:hidden mb-6">
                    <div class="flex items-center justify-between gap-3 overflow-x-auto pb-2 -mx-4 px-4">
                        <button
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 rounded-lg border border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 transition-colors">
                            <i class="fas fa-chart-line text-lime-600 dark:text-emerald-400"></i>
                            <span class="text-sm font-medium">Stats</span>
                        </button>
                        <button
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 rounded-lg border border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 transition-colors">
                            <i class="fas fa-users text-lime-600 dark:text-emerald-400"></i>
                            <span class="text-sm font-medium">Équipe</span>
                        </button>
                        <button
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 rounded-lg border border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 transition-colors">
                            <i class="fas fa-calendar text-lime-600 dark:text-emerald-400"></i>
                            <span class="text-sm font-medium">Matchs</span>
                        </button>
                        <button
                            class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 bg-white dark:bg-gray-800 rounded-lg border border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 transition-colors">
                            <i class="fas fa-trophy text-lime-600 dark:text-emerald-400"></i>
                            <span class="text-sm font-medium">Classement</span>
                        </button>
                    </div>
                </div>

                <!-- Main Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <!-- Left Column (Mobile Full, Desktop 8/12) -->
                    <div class="lg:col-span-8 space-y-6">
                        <!-- Joueur du Mois Section -->
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h2
                                        class="text-xl font-black text-gray-900 dark:text-white flex items-center gap-2">
                                        <span
                                            class="w-1 h-6 bg-gradient-to-b from-lime-400 to-emerald-500 rounded-full"></span>
                                        joueur DU MOIS
                                    </h2>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Celui qui domine l'arène ce mois-ci
                                    </p>
                                </div>

                            </div>

                            <div
                                class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-white to-lime-50/50 dark:from-gray-800 dark:to-emerald-900/20 border border-lime-200 dark:border-emerald-800/50 shadow-lg">
                                <!-- Player Banner -->
                                <BannerPlayerMonth :player="page.props.player" :stats="page.props.stats"
                                    :previousPlayers="page.props.previousPlayers" />


                            </div>
                        </div>

                        <!-- À la une Section -->
                        <div class="relative">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        Les dernières nouvelles de l'arène
                                    </p>
                                </div>
                                <Link :href="route('news.index')"
                                    class="hidden lg:flex items-center gap-2 text-sm font-medium text-emerald-600 dark:text-lime-400 hover:text-emerald-700 dark:hover:text-lime-300 transition-colors">
                                Toutes les news
                                <i class="fas fa-arrow-right text-xs"></i>
                                </Link>
                            </div>

                            <div class="group">
                                <ALaUne :featured="page.props.featured" />

                                <!-- Mobile View All Button -->
                                <div class="lg:hidden mt-4">
                                    <Link :href="route('news.index')"
                                        class="w-full flex items-center justify-center gap-2 py-3 px-4 bg-white dark:bg-gray-800 rounded-xl border border-lime-200 dark:border-emerald-800/50 hover:border-lime-400 dark:hover:border-emerald-500 transition-colors">
                                    <span class="font-medium">Accéder à toutes les actualités</span>
                                    <i class="fas fa-newspaper text-lime-600 dark:text-emerald-400"></i>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column (Desktop 4/12) -->
                    <div class="lg:col-span-4 space-y-6">
                        <!-- Quick Stats Card -->
                        <div
                            class="bg-gradient-to-br from-white to-lime-50/50 dark:from-gray-800 dark:to-emerald-900/20 rounded-2xl p-6 border border-lime-200/50 dark:border-emerald-800/50 shadow-lg">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                                <i class="fas fa-bolt text-lime-500 dark:text-emerald-400"></i>
                                STATS ÉCLAIR
                            </h3>

                       

                            <div class="mt-6 pt-4 border-t border-lime-100 dark:border-emerald-900/30">
                                <Link href="/admin/stats"
                                    class="w-full flex items-center justify-center gap-2 py-2.5 text-sm font-medium text-emerald-600 dark:text-lime-400 hover:text-emerald-700 dark:hover:text-lime-300 transition-colors">
                                <i class="fas fa-chart-bar"></i>
                                Voir stats détaillées
                                </Link>
                            </div>
                        </div>

                    </div>
                </div>


            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Mobile-first animations */
@keyframes slide-up {
    from {
        opacity: 0;
        transform: translateY(20px);
    }

    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse-glow {

    0%,
    100% {
        opacity: 1;
        box-shadow: 0 0 20px rgba(101, 163, 13, 0.1);
    }

    50% {
        opacity: 0.9;
        box-shadow: 0 0 30px rgba(101, 163, 13, 0.2);
    }
}

/* Apply animations to sections */
.animate-slide-up {
    animation: slide-up 0.6s ease-out forwards;
}

.animate-glow {
    animation: pulse-glow 3s ease-in-out infinite;
}

/* Touch-friendly hover states for mobile */
@media (hover: none) and (pointer: coarse) {
    .hover\:scale-105:hover {
        transform: scale(1);
    }

    .hover\:shadow-emerald-500\/30:hover {
        box-shadow: none;
    }
}

/* Custom scrollbar for mobile */
@media (max-width: 1024px) {
    .overflow-x-auto {
        scrollbar-width: thin;
        scrollbar-color: theme('colors.emerald.500') transparent;
    }

    .overflow-x-auto::-webkit-scrollbar {
        height: 4px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: transparent;
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: linear-gradient(to right, theme('colors.lime.500'), theme('colors.emerald.500'));
        border-radius: 2px;
    }
}

/* Better tap targets for mobile */
@media (max-width: 640px) {

    button,
    a {
        min-height: 44px;
        min-width: 44px;
    }
}
</style>
