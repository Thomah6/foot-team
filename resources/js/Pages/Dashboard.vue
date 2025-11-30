<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BannerPlayerMonth from '@/Pages/Admin/News/BannerPlayerMonth.vue';
import ALaUne from '@/Pages/Admin/News/ALaUne.vue';

const page = usePage();
const user = page.props.auth.user;
</script>

<template>
    <Head title="Tableau de bord - FC Dynamo" />
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Hero Section -->
                <section class="section mb-12">
                    <div class="rounded-lg overflow-hidden bg-gradient-to-r from-primary-600 to-primary-800 shadow-elevation">
                        <div class="relative flex flex-col md:flex-row items-center justify-between p-6 md:p-12 gap-8">
                            <!-- Content -->
                            <div class="flex flex-col gap-4 max-w-2xl flex-1">
                                <h1 class="text-3xl md:text-4xl font-bold text-white leading-tight">
                                    Bienvenue, {{ user.name }}! ⚽
                                </h1>
                                <p class="text-lg text-primary-100 leading-relaxed">
                                    Bienvenue au FC Dynamo! C'est le centre névralgique de votre club. Suivez les actualités, les statistiques et accompagnez-nous tout au long de cette saison exceptionnelle.
                                </p>
                            </div>
                            
                            <!-- CTA Button -->
                            <div class="flex gap-3 flex-wrap md:flex-nowrap">
                                <Link href="/joueur-du-mois" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-button bg-white text-primary-600 font-bold hover:bg-gray-100 transition-all duration-200 shadow-lg hover:shadow-elevation">
                                    <i class="fas fa-star"></i>
                                    Joueur du mois
                                </Link>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- Left Column - Main Content (2/3) -->
                    <div class="lg:col-span-2 space-y-8">
                        <!-- Player of Month Section -->
                        <section class="section">
                            <h2 class="section-title mb-6">
                                <i class="fas fa-medal text-yellow-500 mr-3"></i>
                                Joueur du mois
                            </h2>
                            <div class="overflow-x-auto">
                                <div class="flex gap-6 pb-4">
                                    <BannerPlayerMonth 
                                        :player="page.props.player" 
                                        :stats="page.props.stats"
                                        :previousPlayers="page.props.previousPlayers" 
                                    />
                                </div>
                            </div>
                        </section>

                        <!-- Featured News Section -->
                        <section class="section">
                            <h2 class="section-title mb-6">
                                <i class="fas fa-newspaper text-blue-500 mr-3"></i>
                                À la une
                            </h2>
                            <div class="rounded-card shadow-elevation bg-white dark:bg-gray-800 overflow-hidden">
                                <ALaUne :featured="page.props.featured" />
                            </div>
                        </section>
                    </div>

                    <!-- Right Column - Sidebar (1/3) -->
                    <div class="space-y-6">
                        <!-- Quick Stats Card -->
                        <div class="card">
                            <div class="card-header mb-4">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-bar text-primary-600 mr-2"></i>
                                    Statistiques rapides
                                </h3>
                            </div>
                            <div class="space-y-4">
                                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Membres actifs</span>
                                    <span class="text-2xl font-bold text-primary-600">{{ page.props.stats?.active_members || 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Matchs joués</span>
                                    <span class="text-2xl font-bold text-accent-500">{{ page.props.stats?.matches_played || 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center p-3 rounded-lg bg-gray-50 dark:bg-gray-700">
                                    <span class="text-sm font-medium text-gray-600 dark:text-gray-300">Victoires</span>
                                    <span class="text-2xl font-bold text-green-500">{{ page.props.stats?.wins || 0 }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Useful Links Card -->
                        <div class="card">
                            <h3 class="card-title mb-4">
                                <i class="fas fa-link text-purple-600 mr-2"></i>
                                Accès rapide
                            </h3>
                            <div class="space-y-2">
                                <Link href="/presence" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-check-circle mr-2"></i> Présences
                                </Link>
                                <Link href="/stats/create" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-chart-line mr-2"></i> Ajouter stats
                                </Link>
                                <Link href="/galleries" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-images mr-2"></i> Galerie photo
                                </Link>
                                <Link href="/finances" class="block p-3 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <i class="fas fa-money-bill mr-2"></i> Finances
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
