<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Hero Section -->
                <div class="bg-gradient-to-r from-primary-600 to-accent-600 rounded-xl p-8 md:p-12 mb-8 text-white">
                    <div class="flex items-center gap-4 mb-4">
                        <i class="fas fa-trophy text-4xl text-primary-200"></i>
                        <div>
                            <h1 class="heading-1 text-white">Tous les Classements</h1>
                            <p class="text-primary-100">Statistiques et performances de la saison</p>
                        </div>
                    </div>
                </div>

                <!-- Classements Grid -->
                <section class="section">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        <!-- Classement Général -->
                        <Link href="/classements/general">
                            <Card variant="elevated" padding="lg" class="cursor-pointer h-full hover:shadow-lg group transition">
                                <div class="text-center">
                                    <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-yellow-400 to-yellow-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                        <i class="fas fa-medal text-3xl text-white"></i>
                                    </div>
                                    <h3 class="heading-3 mb-2">Classement Général</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Points = Buts + Passes/2</p>
                                    <div class="text-primary-600 font-semibold text-sm group-hover:text-primary-700">Voir →</div>
                                </div>
                            </Card>
                        </Link>

                        <!-- Classement Buteurs -->
                        <Link href="/classements/buteurs">
                            <Card variant="elevated" padding="lg" class="cursor-pointer h-full hover:shadow-lg group transition">
                                <div class="text-center">
                                    <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-error-400 to-error-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                        <i class="fas fa-futbol text-3xl text-white"></i>
                                    </div>
                                    <h3 class="heading-3 mb-2">Buteurs</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Les meilleurs buteurs</p>
                                    <div class="text-primary-600 font-semibold text-sm group-hover:text-primary-700">Voir →</div>
                                </div>
                            </Card>
                        </Link>

                        <!-- Classement Passeurs -->
                        <Link href="/classements/passeurs">
                            <Card variant="elevated" padding="lg" class="cursor-pointer h-full hover:shadow-lg group transition">
                                <div class="text-center">
                                    <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                        <i class="fas fa-hand-fist text-3xl text-white"></i>
                                    </div>
                                    <h3 class="heading-3 mb-2">Passeurs</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Meilleure vision de jeu</p>
                                    <div class="text-primary-600 font-semibold text-sm group-hover:text-primary-700">Voir →</div>
                                </div>
                            </Card>
                        </Link>

                        <!-- Classement Gardiens -->
                        <Link href="/classements/gardiens">
                            <Card variant="elevated" padding="lg" class="cursor-pointer h-full hover:shadow-lg group transition">
                                <div class="text-center">
                                    <div class="h-16 w-16 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition">
                                        <i class="fas fa-shield text-3xl text-white"></i>
                                    </div>
                                    <h3 class="heading-3 mb-2">Gardiens</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Solidité défensive</p>
                                    <div class="text-primary-600 font-semibold text-sm group-hover:text-primary-700">Voir →</div>
                                </div>
                            </Card>
                        </Link>
                    </div>
                </section>

                <!-- Joueur du Mois -->
                <section class="section">
                    <Card variant="elevated" padding="lg">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                            <div>
                                <div class="flex items-center gap-3 mb-4">
                                    <i class="fas fa-star text-4xl text-warning-500"></i>
                                    <div>
                                        <h2 class="heading-2">Joueur du Mois</h2>
                                        <p class="text-gray-500 text-sm">Novembre 2024</p>
                                    </div>
                                </div>
                                <p class="text-gray-600 dark:text-gray-400 mb-6">
                                    Découvre qui a été le meilleur joueur du mois avec ses statistiques impressionnantes !
                                </p>
                                <Link href="/player-of-month" class="inline-block px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg transition font-medium">
                                    Voir le détail →
                                </Link>
                            </div>
                            <div class="text-center">
                                <div class="h-40 w-40 rounded-full bg-gradient-to-br from-primary-100 to-accent-100 flex items-center justify-center mx-auto">
                                    <i class="fas fa-user text-6xl text-primary-600"></i>
                                </div>
                            </div>
                        </div>
                    </Card>
                </section>

                <!-- Stats Générales -->
                <section class="section">
                    <h2 class="heading-2 mb-6">Statistiques Globales</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Total Buts</p>
                                <p class="heading-1 text-primary-600">2,847</p>
                                <p class="text-xs text-gray-500 mt-2">Tous les joueurs</p>
                            </div>
                        </Card>
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Total Passes</p>
                                <p class="heading-1 text-accent-600">5,293</p>
                                <p class="text-xs text-gray-500 mt-2">Toutes les passes</p>
                            </div>
                        </Card>
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Matchs Joués</p>
                                <p class="heading-1 text-warning-600">156</p>
                                <p class="text-xs text-gray-500 mt-2">Cette saison</p>
                            </div>
                        </Card>
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Joueurs Actifs</p>
                                <p class="heading-1 text-success-600">42</p>
                                <p class="text-xs text-gray-500 mt-2">Roster actuel</p>
                            </div>
                        </Card>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Card from '@/Components/Card.vue'
</script>
