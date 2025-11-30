<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    gardiens: Array
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-slate-900 via-slate-800 to-black text-white font-sans relative overflow-hidden">
            
            <!-- Animations de foot en arrière-plan -->
            <div class="absolute inset-0 pointer-events-none">
                <!-- Ballon flottant 1 -->
                <div class="absolute top-20 left-10 animate-float-slow">
                    <div class="w-8 h-8 bg-white/10 rounded-full border border-white/20 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 2 -->
                <div class="absolute top-40 right-20 animate-float-delay">
                    <div class="w-6 h-6 bg-white/8 rounded-full border border-white/15 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 3 -->
                <div class="absolute bottom-32 left-1/4 animate-float-reverse">
                    <div class="w-10 h-10 bg-white/12 rounded-full border border-white/25 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 4 -->
                <div class="absolute bottom-20 right-1/3 animate-float-slow">
                    <div class="w-7 h-7 bg-white/9 rounded-full border border-white/18 backdrop-blur-sm"></div>
                </div>
                <!-- Trajectoire de gardien -->
                <div class="absolute top-1/3 left-20 animate-spin-slow">
                    <svg class="w-16 h-16 text-white/5" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <!-- Étoile scintillante -->
                <div class="absolute top-1/2 right-10 animate-pulse-slow">
                    <div class="w-4 h-4 bg-yellow-500/20 rounded-full"></div>
                </div>
            </div>

            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-slate-800 to-slate-900 py-12 px-4 border-b border-slate-700">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-700 to-slate-800 rounded-xl flex items-center justify-center border border-slate-600 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-hand-paper text-white text-2xl animate-bounce-slow"></i>
                        </div>
                        <div class="text-slate-300 font-light text-xl tracking-wider">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-200 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-slate-500 text-sm tracking-widest">SAISON 2024</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-thin text-white tracking-wider mb-3 animate-fade-in">GARDIENS</h1>
                    <div class="text-slate-400 text-lg tracking-widest font-light">CLASSEMENT DES GARDIENS</div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="max-w-5xl mx-auto px-4 py-12 relative z-10">
                
                <!-- Message si aucun classement -->
                <div v-if="props.gardiens.length === 0" class="text-center py-32">
                    <div class="animate-float-slow">
                        <i class="fas fa-hand-paper text-slate-700 text-7xl mb-8"></i>
                    </div>
                    <p class="text-3xl font-thin text-slate-400">AUCUNE DONNÉE</p>
                    <p class="text-slate-500 mt-3">Aucun gardien n'a de statistiques suffisantes</p>
                </div>

                <!-- Tableau Premium Style -->
                <div v-else class="bg-slate-900/50 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-800 shadow-2xl animate-slide-up">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-slate-800/80 px-8 py-6 border-b border-slate-700">
                        <div class="grid grid-cols-12 gap-6 text-slate-300 text-sm font-light tracking-wider">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5">JOUEUR</div>
                            <div class="col-span-2 text-center">MOY BUTS</div>
                            <div class="col-span-2 text-center">MATCHS</div>
                            <div class="col-span-2 text-center">ZÉRO</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-slate-800/50">
                        <div v-for="(gardien, index) in props.gardiens" 
                             :key="gardien.id"
                             class="px-8 py-6 hover:bg-slate-800/30 transition-all duration-300 transform hover:scale-[1.02] hover:translate-x-2"
                             :class="index === 0 ? 'animate-glow' : ''">
                            
                            <div class="grid grid-cols-12 gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full font-light text-lg transform transition-all duration-300 hover:scale-110 hover:rotate-12"
                                         :class="index === 0 ? 'bg-gradient-to-br from-yellow-600 to-yellow-700 text-black animate-pulse-slow' : 
                                                 index === 1 ? 'bg-gradient-to-br from-slate-600 to-slate-700 text-white' : 
                                                 index === 2 ? 'bg-gradient-to-br from-orange-700 to-orange-800 text-white' : 'bg-slate-800 text-slate-300'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-4">
                                    <div class="relative">
                                        <img :src="gardien.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=G'"
                                             class="w-14 h-14 rounded-full object-cover border-2 border-slate-700 transform transition-all duration-300 hover:scale-110 hover:rotate-6" />
                                        <div v-if="index === 0" class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-500 rounded-full animate-ping"></div>
                                    </div>
                                    <div>
                                        <p class="font-light text-white uppercase tracking-wide text-lg">{{ gardien.name }}</p>
                                        <p class="text-slate-400 text-sm">{{ gardien.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Moyenne de buts encaissés -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-slate-800/50 rounded-xl px-4 py-3 border border-slate-700 transform transition-all duration-300 hover:bg-slate-700/50 hover:scale-105">
                                        <span class="text-white font-light text-2xl">
                                            {{ (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0) > 0 ? 
                                                (gardien.total_goals_against / (gardien.stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0)).toFixed(1) : '0.0' }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Matchs -->
                                <div class="col-span-2 text-center text-slate-400 font-light text-lg">
                                    {{ gardien.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Clean sheets -->
                                <div class="col-span-2 text-center">
                                    <span class="text-white font-light text-lg">
                                        {{ gardien.stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0 }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques premium du bas -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 transform transition-all duration-300 hover:scale-105 hover:bg-slate-800/50 animate-slide-up-delay">
                        <div class="bg-gradient-to-br from-yellow-600/20 to-yellow-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-600/30 animate-pulse-slow">
                            <i class="fas fa-trophy text-yellow-500 text-3xl animate-bounce-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-yellow-500">
                            {{ props.gardiens[0] ? ((props.gardiens[0].stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0) > 0 ? 
                                (props.gardiens[0].total_goals_against / (props.gardiens[0].stats?.filter(s => s.validated_by_admin && s.goals_against > 0).length || 0)).toFixed(1) : '0.0') : '0.0' }}
                        </p>
                        <p class="text-slate-400 text-sm tracking-widest font-light mt-2">MEILLEURE MOYENNE</p>
                        <p class="text-white mt-2 font-light">{{ props.gardiens[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 transform transition-all duration-300 hover:scale-105 hover:bg-slate-800/50 animate-slide-up-delay-2">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30">
                            <i class="fas fa-shield-alt text-slate-400 text-3xl animate-pulse-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-slate-300">
                            {{ props.gardiens.reduce((sum, g) => sum + (g.stats?.filter(s => s.validated_by_admin && s.goals_against === 0).length || 0), 0) }}
                        </p>
                        <p class="text-slate-400 text-sm tracking-widest font-light mt-2">TOTAL ZÉRO BUTS</p>
                    </div>

                    <div class="bg-slate-900/50 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-800 transform transition-all duration-300 hover:scale-105 hover:bg-slate-800/50 animate-slide-up-delay-3">
                        <div class="bg-gradient-to-br from-slate-600/20 to-slate-700/20 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-600/30">
                            <i class="fas fa-users text-slate-400 text-3xl animate-pulse-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-slate-300">{{ props.gardiens.length }}</p>
                        <p class="text-slate-400 text-sm tracking-widest font-light mt-2">GARDIENS CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour premium -->
                <div class="text-center mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-3 bg-slate-800 text-white px-8 py-4 rounded-2xl font-light hover:bg-slate-700 transition-all duration-300 tracking-wider text-lg border border-slate-800 transform hover:scale-105 hover:shadow-2xl">
                        <i class="fas fa-arrow-left animate-pulse-slow"></i>
                        RETOUR AUX CLASSEMENTS
                    </Link>
                </div>

            </div>

        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
@keyframes float-slow {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(-10px) rotate(5deg); }
    50% { transform: translateY(-5px) rotate(-5deg); }
    75% { transform: translateY(-15px) rotate(3deg); }
}

@keyframes float-reverse {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    25% { transform: translateY(10px) rotate(-5deg); }
    50% { transform: translateY(5px) rotate(5deg); }
    75% { transform: translateY(15px) rotate(-3deg); }
}

@keyframes bounce-slow {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-5px); }
}

@keyframes pulse-slow {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

@keyframes spin-slow {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

@keyframes fade-in {
    from { opacity: 0; transform: translateY(-20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slide-up {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes glow {
    0%, 100% { box-shadow: 0 0 20px rgba(255, 215, 0, 0.3); }
    50% { box-shadow: 0 0 40px rgba(255, 215, 0, 0.6); }
}

.animate-float-slow {
    animation: float-slow 6s ease-in-out infinite;
}

.animate-float-reverse {
    animation: float-reverse 8s ease-in-out infinite;
}

.animate-float-delay {
    animation: float-slow 6s ease-in-out infinite;
    animation-delay: 2s;
}

.animate-bounce-slow {
    animation: bounce-slow 3s ease-in-out infinite;
}

.animate-pulse-slow {
    animation: pulse-slow 4s ease-in-out infinite;
}

.animate-spin-slow {
    animation: spin-slow 20s linear infinite;
}

.animate-fade-in {
    animation: fade-in 1s ease-out;
}

.animate-slide-up {
    animation: slide-up 0.8s ease-out;
}

.animate-slide-up-delay {
    animation: slide-up 0.8s ease-out;
    animation-delay: 0.2s;
}

.animate-slide-up-delay-2 {
    animation: slide-up 0.8s ease-out;
    animation-delay: 0.4s;
}

.animate-slide-up-delay-3 {
    animation: slide-up 0.8s ease-out;
    animation-delay: 0.6s;
}

.animate-glow {
    animation: glow 3s ease-in-out infinite;
}
</style>
