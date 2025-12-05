<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useForm } from "@inertiajs/vue3";
import ConfirmModal from "@/Components/ConfirmModal.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    stats: Array,
    votes: Array,
    reflections: Array,
});

// Formulaire pour valider/rejeter
const form = useForm({});

const validateStat = (statId) => {
    form.post(`/admin/stats/${statId}/validate`, {
        preserveScroll: true,
        onSuccess: () => {
            // La page se mettra à jour automatiquement via Inertia
        },
    });
};

const showRejectModal = ref(false);
const rejectingId = ref(null);

const rejectStat = (statId) => {
    rejectingId.value = statId;
    showRejectModal.value = true;
};

const confirmReject = () => {
    if (!rejectingId.value) return;
    form.delete(`/admin/stats/${rejectingId.value}/reject`, {
        preserveScroll: true,
    });
    rejectingId.value = null;
};

const format = (date) => {
    try {
        // Si la date est déjà au format ISO ou valide
        const dateObj = new Date(date);
        
        // Vérifier si la date est valide
        if (isNaN(dateObj.getTime())) {
            return 'Date invalide';
        }
        
        // Format JJ/MM/AAAA: "11/10/2025"
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // +1 car les mois sont 0-11
        const year = dateObj.getFullYear();
        
        return `${day}/${month}/${year}`;
    } catch (error) {
        console.error('Erreur de formatage de date:', error);
        return 'Date erreur';
    }
};

// Formatage court pour les espaces réduits (mobile) - Format JJ/MM/AAAA
const formatShort = (date) => {
    try {
        const dateObj = new Date(date);
        if (isNaN(dateObj.getTime())) return 'Date invalide';
        
        // Format court: "11/10/2025" (JJ/MM/AAAA)
        const day = String(dateObj.getDate()).padStart(2, '0');
        const month = String(dateObj.getMonth() + 1).padStart(2, '0'); // +1 car les mois sont 0-11
        const year = dateObj.getFullYear();
        
        return `${day}/${month}/${year}`;
    } catch (error) {
        return 'Err';
    }
};

// Formatage relatif (il y a X jours)
const formatRelative = (date) => {
    try {
        const dateObj = new Date(date);
        if (isNaN(dateObj.getTime())) return 'Date invalide';
        
        const now = new Date();
        const diffTime = Math.abs(now - dateObj);
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
        
        if (diffDays === 0) return "Aujourd'hui";
        if (diffDays === 1) return "Hier";
        if (diffDays < 7) return `Il y a ${diffDays} jours`;
        if (diffDays < 30) return `Il y a ${Math.floor(diffDays / 7)} semaine${Math.floor(diffDays / 7) > 1 ? 's' : ''}`;
        
        return format(date); // Retour au format standard
    } catch (error) {
        return 'Date erreur';
    }
};
</script>

<template>
    <AuthenticatedLayout :votes="props.votes" :reflections="props.reflections">
        <div class="relative min-h-screen w-full bg-gradient-to-b from-slate-50 via-slate-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-slate-900 dark:text-citron-50 font-sans">
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 dark:from-gray-900 dark:to-gray-800 py-6 sm:py-8 lg:py-12 px-4 border-b border-slate-200 dark:border-gray-700">
                <div class="flex flex-col items-center justify-center max-w-6xl mx-auto mb-6 sm:mb-8 lg:mb-12 gap-4">
                    <!-- Titre central -->
                    <div class="text-center">
                        <div class="text-slate-600 dark:text-gray-400 text-xl sm:text-2xl lg:text-3xl font-light tracking-widest mb-2">STATS EN ATTENTE</div>
                        <div class="text-slate-500 dark:text-gray-500 text-sm sm:text-base tracking-widest">Validation des performances des joueurs</div>
                    </div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-6 sm:mb-8">
                    <h1 class="text-2xl sm:text-3xl lg:text-4xl font-thin text-slate-900 dark:text-white tracking-wider mb-2">VALIDATION</h1>
                </div>
                
                <!-- Bouton retour -->
                <div class="text-center">
                    <Link href="/admin/stats"
                          class="inline-flex items-center gap-2 sm:gap-3 bg-slate-100 dark:bg-gray-800 text-slate-700 dark:text-gray-300 px-4 sm:px-6 py-2 sm:py-3 rounded-xl sm:rounded-2xl font-light hover:bg-slate-200 dark:hover:bg-gray-700 transition-all duration-300 tracking-wider border border-slate-300 dark:border-gray-600 text-sm sm:text-base">
                        <i class="fas fa-arrow-left text-sm sm:text-base dark:text-gray-400"></i>
                        <span class="hidden sm:inline">RETOUR TABLEAU DE BORD</span>
                        <span class="sm:hidden">RETOUR</span>
                    </Link>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-3 sm:px-4 py-8 sm:py-12 lg:py-16">
                <!-- Message si aucune stat en attente -->
                <div v-if="props.stats.length === 0"
                     class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-8 sm:p-16 text-center border border-slate-200 dark:border-gray-700 shadow-lg">
                    <div class="bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/50 dark:to-purple-900/50 w-24 h-24 sm:w-32 sm:h-32 rounded-2xl flex items-center justify-center mx-auto mb-6 sm:mb-8 border border-indigo-300 dark:border-indigo-700">
                        <i class="fas fa-check-circle text-indigo-600 dark:text-indigo-400 text-4xl sm:text-6xl"></i>
                    </div>
                    <h2 class="text-2xl sm:text-4xl font-thin mb-3 sm:mb-4 tracking-wider text-slate-900 dark:text-white">TOUT EST À JOUR !</h2>
                    <p class="text-lg sm:text-xl text-slate-600 dark:text-gray-300 font-light mb-6 sm:mb-8">Aucune statistique en attente de validation</p>
                    <div class="text-slate-500 dark:text-gray-400 font-light tracking-wider text-sm sm:text-base">
                        <i class="fas fa-trophy text-amber-600 dark:text-amber-400 mr-2"></i>
                        Toutes les performances ont été validées
                    </div>
                </div>

                <!-- Cartes de stats à valider -->
                <div v-else class="space-y-4 sm:space-y-6 lg:space-y-8">
                    <div v-for="stat in props.stats"
                         :key="stat.id"
                         class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-3 sm:p-4 lg:p-6 border border-slate-200 dark:border-gray-700 shadow-lg hover:border-slate-300 dark:hover:border-gray-600 transition-all duration-300">

                        <!-- En-tête avec utilisateur et date -->
                        <div class="flex flex-col gap-3 mb-4 sm:mb-6">
                            <div class="flex items-center gap-3">
                                <img :src="stat.user.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                     class="w-12 h-12 sm:w-16 sm:h-20 rounded-xl object-cover border-2 border-slate-300 dark:border-citron-600" />
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-base sm:text-lg lg:text-xl font-light text-slate-900 dark:text-white mb-1 truncate">{{ stat.user.name }}</h3>
                                    <p class="text-slate-600 dark:text-gray-300 font-light text-xs sm:text-sm mb-1">@{{ stat.user.pseudo }}</p>
                                    <div class="flex items-center gap-2 text-slate-500 dark:text-gray-400 text-xs group relative">
                                        <i class="fas fa-calendar text-xs dark:text-gray-400"></i>
                                        <span class="truncate">{{ formatShort(stat.date) }}</span>
                                        <!-- Tooltip avec date relative au survol -->
                                        <div class="absolute bottom-full left-0 mb-2 px-2 py-1 bg-slate-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                            {{ formatRelative(stat.date) }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-gradient-to-br from-slate-100 to-slate-200 dark:from-gray-800 dark:to-gray-700 px-3 py-1.5 rounded-xl border border-slate-300 dark:border-gray-600 w-fit">
                                <div class="flex items-center justify-center gap-2 text-slate-600 dark:text-gray-300 font-light tracking-wider text-xs">
                                    <i class="fas fa-clock text-xs dark:text-gray-400"></i>
                                    EN ATTENTE
                                </div>
                            </div>
                        </div>

                        <!-- Stats détaillées -->
                        <div class="grid grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4 lg:gap-6 mb-4 sm:mb-6">
                            <div class="bg-gradient-to-br from-indigo-100 to-indigo-200 dark:from-indigo-900/50 dark:to-indigo-800/50 rounded-xl p-2 sm:p-3 lg:p-4 text-center border border-indigo-300 dark:border-indigo-700">
                                <i class="fas fa-futbol text-indigo-600 dark:text-indigo-400 text-lg sm:text-xl lg:text-2xl mb-1 sm:mb-2"></i>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-thin text-indigo-700 dark:text-indigo-300 mb-1">{{ stat.goals }}</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 font-light tracking-wider">BUTS</p>
                            </div>
                            <div class="bg-gradient-to-br from-violet-100 to-violet-200 dark:from-violet-900/50 dark:to-violet-800/50 rounded-xl p-2 sm:p-3 lg:p-4 text-center border border-violet-300 dark:border-violet-700">
                                <i class="fas fa-hands-helping text-violet-600 dark:text-violet-400 text-lg sm:text-xl lg:text-2xl mb-1 sm:mb-2"></i>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-thin text-violet-700 dark:text-violet-300 mb-1">{{ stat.assists }}</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 font-light tracking-wider">PASSES</p>
                            </div>
                            <div class="bg-gradient-to-br from-amber-100 to-amber-200 dark:from-amber-900/50 dark:to-amber-800/50 rounded-xl p-2 sm:p-3 lg:p-4 text-center border border-amber-300 dark:border-amber-700">
                                <i class="fas fa-shield-alt text-amber-600 dark:text-amber-400 text-lg sm:text-xl lg:text-2xl mb-1 sm:mb-2"></i>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-thin text-amber-700 dark:text-amber-300 mb-1">{{ stat.goals_against }}</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 font-light tracking-wider">ENCAISSÉS</p>
                            </div>
                            <div class="bg-gradient-to-br from-rose-100 to-rose-200 dark:from-rose-900/50 dark:to-rose-800/50 rounded-xl p-2 sm:p-3 lg:p-4 text-center border border-rose-300 dark:border-rose-700">
                                <i class="fas fa-calendar-check text-rose-600 dark:text-rose-400 text-lg sm:text-xl lg:text-2xl mb-1 sm:mb-2"></i>
                                <p class="text-xl sm:text-2xl lg:text-3xl font-thin text-rose-700 dark:text-rose-300 mb-1">{{ stat.matches_played }}</p>
                                <p class="text-xs sm:text-sm text-slate-600 dark:text-gray-300 font-light tracking-wider">MATCHS</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                            <button @click="validateStat(stat.id)"
                                    :disabled="form.processing"
                                    class="w-full sm:flex-1 bg-gradient-to-r from-indigo-100 to-indigo-200 dark:from-indigo-900/50 dark:to-indigo-800/50 text-indigo-700 dark:text-indigo-300 px-3 sm:px-4 py-2 sm:py-3 rounded-xl font-light hover:from-indigo-200 hover:to-indigo-300 dark:hover:from-indigo-800/50 dark:hover:to-indigo-700/50 transition-all duration-300 tracking-wider border border-indigo-300 dark:border-indigo-700 flex items-center justify-center gap-2 text-sm sm:text-base">
                                <i class="fas fa-check-circle text-sm sm:text-base dark:text-indigo-400"></i>
                                <span>VALIDER</span>
                            </button>

                            <button @click="rejectStat(stat.id)"
                                    :disabled="form.processing"
                                    class="w-full sm:flex-1 bg-gradient-to-r from-rose-100 to-rose-200 dark:from-rose-900/50 dark:to-rose-800/50 text-rose-700 dark:text-rose-300 px-3 sm:px-4 py-2 sm:py-3 rounded-xl font-light hover:from-rose-200 hover:to-rose-300 dark:hover:from-rose-800/50 dark:hover:to-rose-700/50 transition-all duration-300 tracking-wider border border-rose-300 dark:border-rose-700 flex items-center justify-center gap-2 text-sm sm:text-base">
                                <i class="fas fa-times-circle text-sm sm:text-base dark:text-rose-400"></i>
                                <span>REJETER</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
    <ConfirmModal
        v-model="showRejectModal"
        title="Rejeter la statistique"
        message="Êtes-vous sûr de vouloir rejeter cette statistique ?"
        confirm-text="Rejeter"
        cancel-text="Annuler"
        variant="danger"
        @confirm="confirmReject"
    />
</template>
