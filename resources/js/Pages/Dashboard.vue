<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage, Link } from '@inertiajs/vue3';
import BannerPlayerMonth from '@/Pages/Admin/News/BannerPlayerMonth.vue'
import ALaUne from '@/Pages/Admin/News/ALaUne.vue'
import AdminsideBar from '@/Components/AdminsideBar.vue';

const page = usePage();

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Dashboard - FC Dynamo" />
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <AuthenticatedLayout>
        <div class="relative flex min-h-screen w-full flex-col font-display bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
            <!-- Sidebar Admin -->
            <AdminsideBar />
            
            <!-- Contenu principal -->
            <div class="lg:ml-64">
                <!-- Padding pour mobile pour ne pas être sous le hamburger -->
                <div class="pt-16 lg:pt-0">
                    <!-- Main Content -->
                    <main class="flex-1 overflow-y-auto">
                        <div class="p-8">
                            <!-- Hero Section -->
                            <div class="relative w-full rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-lg dark:shadow-none mb-8">
                                <div class="absolute inset-0 bg-cover bg-center" 
                                     style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCq5SmIFwaqOxatQv-MMr6vmBY_JeCcxPb3rbasK4fR6vxUq8RhLoZj4U-HGbvTJ8EALKaDPfgZw0WJC9B5MU4YVTxgNrbb66g0F_9nkDl5NSh75LFTiWvXeZ8v-Xt3NftWiLKgmAH7Uho9PPKEPpZodFfuZ9hOt91WtwSJnO6B1c6dXf_Xy1-CIMJq8LmYvmI6qgipl_udXDpjf54bUIKBNoWOFQLJd4BkdarIHGqeyXCaEckHvoxKfNzq3fTFALJVJX4eRtUcA'); opacity: 0.1;">
                                </div>
                                <div class="relative flex flex-col md:flex-row items-center justify-between p-8 md:p-12 gap-8">
                                    <div class="flex flex-col gap-4 max-w-2xl text-center md:text-left">
                                        <h2 class="text-3xl md:text-4xl font-extrabold leading-tight tracking-tighter text-gray-900 dark:text-gray-100">
                                            Bienvenue au FC Dynamo !
                                        </h2>
                                        <p class="text-lg text-gray-600 dark:text-gray-300">
                                            Le centre névralgique de votre club de football. Suivez les dernières actualités, consultez les statistiques des joueurs et accompagnez-nous tout au long de cette saison exceptionnelle. Nous sommes ravis de vous compter parmi nous !
                                        </p>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <Link href="/joueur-du-mois"
                                              class="flex items-center gap-2 min-w-[84px] cursor-pointer justify-center overflow-hidden rounded-lg h-12 px-6 bg-green-500 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-green-600 transition-opacity">
                                            <span class="truncate">Joueur du mois</span>
                                            <i class="fas fa-arrow-right"></i>
                                        </Link>
                                    </div>
                                </div>
                            </div>

                            <!-- Grid Layout -->
                            <div class="grid grid-cols-12 gap-8">
                                <!-- Left Column -->
                                <div class="col-span-12 lg:col-span-8 flex flex-col gap-8">
                                    <!-- Joueur du mois Section -->
                                    <div>
                                        <h2 class="text-2xl font-bold leading-tight tracking-tight px-0 pb-4">Joueur du mois</h2>
                                        <div class="flex overflow-x-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden -mx-8 px-4">
                                            <div class="flex items-stretch p-4 gap-6">
                                                <!-- BannerPlayerMonth Component -->
                                                <BannerPlayerMonth 
                                                    :player="page.props.player" 
                                                    :stats="page.props.stats"
                                                    :previousPlayers="page.props.previousPlayers" 
                                                />
                                            </div>
                                        </div>
                                    </div>

                                    <!-- À la une Section -->
                                    <div>
                                        <h2 class="text-2xl font-bold leading-tight tracking-tight pb-4">À la une</h2>
                                        <div class="group/card @container overflow-hidden rounded-xl shadow-lg dark:shadow-none bg-white dark:bg-gray-800">
                                            <ALaUne :featured="page.props.featured" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Right Column -->
                                <div class="col-span-12 lg:col-span-4 flex flex-col gap-8">
                                </div>
                            </div>
                        </div>
                    </main>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-size: 24px;
}

.material-symbols-outlined.fill {
    font-variation-settings: 'FILL' 1;
}
</style>
