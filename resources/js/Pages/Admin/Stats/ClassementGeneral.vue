<script setup>
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    classement: Array,
    current_month: String,
    is_player_of_month: Boolean
})

const format = (number) => {
    return parseFloat(number).toFixed(1);
}

const formatMonth = (month) => {
    const date = new Date(month + '-01');
    return date.toLocaleDateString('fr-FR', { 
        month: 'long', 
        year: 'numeric' 
    });
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="min-h-screen bg-gradient-to-b from-slate-50 via-slate-100 to-white text-slate-900 font-sans relative overflow-hidden">
            
            <!-- Animations de foot en arrière-plan -->
            <div class="absolute inset-0 pointer-events-none">
                <!-- Ballon flottant 1 -->
                <div class="absolute top-20 left-10 animate-float-slow">
                    <div class="w-8 h-8 bg-slate-200/30 rounded-full border border-slate-300/40 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 2 -->
                <div class="absolute top-40 right-20 animate-float-delay">
                    <div class="w-6 h-6 bg-slate-200/25 rounded-full border border-slate-300/35 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 3 -->
                <div class="absolute bottom-32 left-1/4 animate-float-reverse">
                    <div class="w-10 h-10 bg-slate-200/35 rounded-full border border-slate-300/45 backdrop-blur-sm"></div>
                </div>
                <!-- Ballon flottant 4 -->
                <div class="absolute bottom-20 right-1/3 animate-float-slow">
                    <div class="w-7 h-7 bg-slate-200/28 rounded-full border border-slate-300/38 backdrop-blur-sm"></div>
                </div>
                <!-- Trajectoire de général -->
                <div class="absolute top-1/3 left-20 animate-spin-slow">
                    <svg class="w-16 h-16 text-slate-300/10" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                </div>
                <!-- Étoile scintillante -->
                <div class="absolute top-1/2 right-10 animate-pulse-slow">
                    <div class="w-4 h-4 bg-yellow-400/30 rounded-full"></div>
                </div>
            </div>

            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 py-12 px-4 border-b border-slate-200">
                <!-- Logo et titre -->
                <div class="flex items-center justify-between max-w-6xl mx-auto mb-12">
                    <!-- Logo à gauche -->
                    <div class="flex items-center gap-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center border border-slate-300 hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-medal text-slate-700 text-2xl animate-bounce-slow"></i>
                        </div>
                        <div class="text-slate-700 font-light text-xl tracking-wider">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-600 text-xl font-light tracking-widest mb-2">CLASSEMENT OFFICIEL</div>
                        <div class="text-slate-500 text-sm tracking-widest">SAISON 2024</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8">
                    <h1 class="text-6xl font-thin text-slate-900 tracking-wider mb-3 animate-fade-in">GÉNÉRAL</h1>
                    <div class="text-slate-600 text-lg tracking-widest font-light">CLASSEMENT GÉNÉRAL</div>
                </div>
            </div>

            <!-- Tableau des classements -->
            <div class="max-w-5xl mx-auto px-4 py-12 relative z-10">
                
                <!-- Message si aucun classement -->
                <div v-if="props.classement.length === 0" class="text-center py-32">
                    <div class="animate-float-slow">
                        <i class="fas fa-medal text-slate-400 text-7xl mb-8"></i>
                    </div>
                    <p class="text-3xl font-thin text-slate-600">AUCUNE DONNÉE</p>
                    <p class="text-slate-500 mt-3">Aucune donnée de classement disponible</p>
                </div>

                <!-- Podium Premium -->
                <div v-else-if="props.classement.length >= 3" class="mb-16">
                    <div class="flex justify-center items-end gap-8 mb-8">
                        <!-- 2ème place -->
                        <div class="flex flex-col items-center animate-slide-up-delay-2">
                            <div class="bg-gradient-to-br from-slate-200 to-slate-300 w-28 h-28 rounded-2xl flex items-center justify-center mb-4 border border-slate-300 shadow-lg">
                                <span class="text-4xl font-thin text-slate-700">2</span>
                            </div>
                            <img :src="props.classement[1].avatar || 'https://via.placeholder.com/100x100.png/C0C0C0?text=2'" 
                                 class="w-16 h-16 rounded-full border-3 border-slate-300 mb-2 transform transition-all duration-300 hover:scale-110 hover:rotate-6" />
                            <p class="text-slate-900 font-light text-lg">{{ props.classement[1].name }}</p>
                            <p class="text-slate-600 text-sm">{{ props.classement[1].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-slate-600 text-sm">B: {{ props.classement[1].total_goals }} | P: {{ props.classement[1].total_assists }}</p>
                                <p class="text-2xl font-thin text-slate-900 mt-1">{{ props.classement[1].points }} pts</p>
                            </div>
                        </div>

                        <!-- 1ère place -->
                        <div class="flex flex-col items-center animate-slide-up-delay">
                            <div class="bg-gradient-to-br from-yellow-400 to-yellow-500 w-32 h-32 rounded-2xl flex items-center justify-center mb-4 border border-yellow-400/50 shadow-lg animate-glow">
                                <span class="text-5xl font-thin text-white">1</span>
                            </div>
                            <img :src="props.classement[0].avatar || 'https://via.placeholder.com/100x100.png/FFD700?text=1'" 
                                 class="w-20 h-20 rounded-full border-4 border-yellow-400/50 mb-2 transform transition-all duration-300 hover:scale-110 hover:rotate-6" />
                            <p class="text-slate-900 font-light text-xl">{{ props.classement[0].name }}</p>
                            <p class="text-slate-600 text-sm">{{ props.classement[0].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-yellow-600 text-sm">B: {{ props.classement[0].total_goals }} | P: {{ props.classement[0].total_assists }}</p>
                                <p class="text-3xl font-thin text-yellow-600 mt-1">{{ props.classement[0].points }} pts</p>
                            </div>
                        </div>

                        <!-- 3ème place -->
                        <div class="flex flex-col items-center animate-slide-up-delay-3">
                            <div class="bg-gradient-to-br from-orange-300 to-orange-400 w-28 h-28 rounded-2xl flex items-center justify-center mb-4 border border-orange-300/50 shadow-lg">
                                <span class="text-4xl font-thin text-white">3</span>
                            </div>
                            <img :src="props.classement[2].avatar || 'https://via.placeholder.com/100x100.png/CD7F32?text=3'" 
                                 class="w-16 h-16 rounded-full border-3 border-orange-300 mb-2 transform transition-all duration-300 hover:scale-110 hover:rotate-6" />
                            <p class="text-slate-900 font-light text-lg">{{ props.classement[2].name }}</p>
                            <p class="text-slate-600 text-sm">{{ props.classement[2].pseudo }}</p>
                            <div class="mt-2">
                                <p class="text-orange-600 text-sm">B: {{ props.classement[2].total_goals }} | P: {{ props.classement[2].total_assists }}</p>
                                <p class="text-2xl font-thin text-slate-900 mt-1">{{ props.classement[2].points }} pts</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau Premium Style -->
                <div v-if="props.classement.length > 0" class="bg-white/80 backdrop-blur-sm rounded-2xl overflow-hidden border border-slate-200 shadow-lg animate-slide-up">
                    
                    <!-- En-tête du tableau -->
                    <div class="bg-slate-100/80 px-8 py-6 border-b border-slate-200">
                        <div class="grid grid-cols-12 gap-6 text-slate-600 text-sm font-light tracking-wider">
                            <div class="col-span-1 text-center">RANG</div>
                            <div class="col-span-5">JOUEUR</div>
                            <div class="col-span-2 text-center">POINTS</div>
                            <div class="col-span-2 text-center">MATCHS</div>
                            <div class="col-span-2 text-center">MOY</div>
                        </div>
                    </div>

                    <!-- Lignes du classement -->
                    <div class="divide-y divide-slate-200/50">
                        <div v-for="(joueur, index) in props.classement" 
                             :key="joueur.id"
                             class="px-8 py-6 hover:bg-slate-50 transition-all duration-300 transform hover:scale-[1.02] hover:translate-x-2"
                             :class="index === 0 ? 'animate-glow' : ''">
                            
                            <div class="grid grid-cols-12 gap-6 items-center">
                                <!-- Position -->
                                <div class="col-span-1 text-center">
                                    <div class="flex items-center justify-center w-12 h-12 rounded-full font-light text-lg transform transition-all duration-300 hover:scale-110 hover:rotate-12"
                                         :class="index === 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-500 text-white animate-pulse-slow' : 
                                                 index === 1 ? 'bg-gradient-to-br from-slate-200 to-slate-300 text-slate-700' : 
                                                 index === 2 ? 'bg-gradient-to-br from-orange-300 to-orange-400 text-white' : 'bg-slate-200 text-slate-700'">
                                        {{ index + 1 }}
                                    </div>
                                </div>

                                <!-- Joueur -->
                                <div class="col-span-5 flex items-center gap-4">
                                    <div class="relative">
                                        <img :src="joueur.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=G'"
                                             class="w-14 h-14 rounded-full object-cover border-2 border-slate-300 transform transition-all duration-300 hover:scale-110 hover:rotate-6" />
                                        <div v-if="index === 0" class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full animate-ping"></div>
                                    </div>
                                    <div>
                                        <p class="font-light text-slate-900 uppercase tracking-wide text-lg">{{ joueur.name }}</p>
                                        <p class="text-slate-600 text-sm">{{ joueur.pseudo }}</p>
                                    </div>
                                </div>

                                <!-- Points -->
                                <div class="col-span-2 text-center">
                                    <div class="bg-slate-100 rounded-xl px-4 py-3 border border-slate-200 transform transition-all duration-300 hover:bg-slate-200 hover:scale-105">
                                        <span class="text-slate-900 font-light text-2xl">{{ joueur.points }}</span>
                                    </div>
                                </div>

                                <!-- Matchs -->
                                <div class="col-span-2 text-center text-slate-600 font-light text-lg">
                                    {{ joueur.stats?.filter(s => s.validated_by_admin).length || 0 }}
                                </div>

                                <!-- Moyenne -->
                                <div class="col-span-2 text-center">
                                    <span class="text-slate-700 font-light text-lg">
                                        {{ (joueur.stats?.filter(s => s.validated_by_admin).length || 0) > 0 ? 
                                            (joueur.points / (joueur.stats?.filter(s => s.validated_by_admin).length || 0)).toFixed(1) : '0.0' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques premium du bas -->
                <div v-if="props.classement.length > 0" class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-12">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg transform transition-all duration-300 hover:scale-105 hover:bg-slate-50 animate-slide-up-delay">
                        <div class="bg-gradient-to-br from-yellow-100 to-yellow-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-yellow-300 animate-pulse-slow">
                            <i class="fas fa-trophy text-yellow-600 text-3xl animate-bounce-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-yellow-600">{{ props.classement[0]?.points || 0 }}</p>
                        <p class="text-slate-600 text-sm tracking-widest font-light mt-2">LEADER POINTS</p>
                        <p class="text-slate-900 mt-2 font-light">{{ props.classement[0]?.name || '-' }}</p>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg transform transition-all duration-300 hover:scale-105 hover:bg-slate-50 animate-slide-up-delay-2">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                            <i class="fas fa-chart-line text-slate-600 text-3xl animate-pulse-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-slate-700">
                            {{ props.classement.reduce((sum, j) => sum + j.points, 0) }}
                        </p>
                        <p class="text-slate-600 text-sm tracking-widest font-light mt-2">TOTAL POINTS</p>
                    </div>

                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg transform transition-all duration-300 hover:scale-105 hover:bg-slate-50 animate-slide-up-delay-3">
                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-20 h-20 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                            <i class="fas fa-users text-slate-600 text-3xl animate-pulse-slow"></i>
                        </div>
                        <p class="text-4xl font-thin text-slate-700">{{ props.classement.length }}</p>
                        <p class="text-slate-600 text-sm tracking-widest font-light mt-2">JOUEURS CLASSÉS</p>
                    </div>
                </div>

                <!-- Bouton retour premium -->
                <div class="text-center mt-12">
                    <Link href="/classements" 
                          class="inline-flex items-center gap-3 bg-slate-100 text-slate-700 px-8 py-4 rounded-2xl font-light hover:bg-slate-200 transition-all duration-300 tracking-wider text-lg border border-slate-300 transform hover:scale-105 hover:shadow-lg">
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
