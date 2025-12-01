<script setup>
import { Link } from "@inertiajs/vue3";
import { ref } from 'vue';
import { useForm } from "@inertiajs/vue3";
import AdminsideBar from "@/Components/AdminsideBar.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

const props = defineProps({
    stats: Array,
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

// Fonction pour gérer l'ouverture du menu sidebar
const toggleSidebarMenu = () => {
    // Émettre un événement global pour ouvrir/fermer le menu
    window.dispatchEvent(new CustomEvent('toggle-sidebar'));
};
</script>

<template>
    <div class="flex">
        <div>
            <AdminsideBar />
        </div>

    <div class="relative min-h-screen w-full bg-gradient-to-b from-slate-50 via-slate-100 to-white text-slate-900 font-sans">
        <!-- Header Premium Style -->
        <div class="relative bg-gradient-to-b from-white to-slate-50 py-8 sm:py-12 lg:py-16 px-4 border-b border-slate-200">
            <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                <!-- Logo et titre -->
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center border border-slate-300">
                        <i class="fas fa-hourglass-half text-slate-700 text-lg sm:text-2xl"></i>
                    </div>
                    <div class="text-slate-700 font-light text-lg sm:text-xl tracking-wider hidden sm:block">FOOT TEAM</div>
                </div>
                
                <!-- Titre central avec bouton hamburger -->
                <div class="text-center order-first sm:order-none flex items-center gap-6 sm:pb-4">
                    <div>
                        <div class="text-slate-600 text-lg sm:text-xl font-light tracking-widest mb-1 sm:mb-2">VALIDATION DES STATS</div>
                        <div class="text-slate-500 text-xs sm:text-sm tracking-widest">PANEL DE CONTRÔLE</div>
                    </div>
                </div>
                
                <!-- Logo à droite -->
                <div class="w-20 sm:w-24"></div>
            </div>
            
            <!-- Titre principal -->
            <div class="text-center mb-8 sm:mb-12">
                <h1 class="text-3xl sm:text-4xl lg:text-6xl font-thin text-slate-900 tracking-wider mb-3 sm:mb-4">STATS EN ATTENTE</h1>
                <div class="text-slate-600 text-sm sm:text-lg tracking-widest font-light">Validation des performances des joueurs</div>
            </div>
            
            <!-- Bouton retour -->
            <div class="text-center">
                <Link href="/admin/stats"
                      class="inline-flex items-center gap-2 sm:gap-3 bg-slate-100 text-slate-700 px-4 sm:px-6 py-2 sm:py-3 rounded-xl sm:rounded-2xl font-light hover:bg-slate-200 transition-all duration-300 tracking-wider border border-slate-300 text-sm sm:text-base">
                    <i class="fas fa-arrow-left text-sm sm:text-base"></i>
                    <span class="hidden sm:inline">RETOUR TABLEAU DE BORD</span>
                    <span class="sm:hidden">RETOUR</span>
                </Link>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-4 py-16">
            <!-- Message si aucune stat en attente -->
            <div v-if="props.stats.length === 0"
                 class="bg-white/80 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-8 sm:p-16 text-center border border-slate-200 shadow-lg">
                <div class="bg-gradient-to-br from-indigo-100 to-purple-100 w-24 h-24 sm:w-32 sm:h-32 rounded-2xl flex items-center justify-center mx-auto mb-6 sm:mb-8 border border-indigo-300">
                    <i class="fas fa-check-circle text-indigo-600 text-4xl sm:text-6xl"></i>
                </div>
                <h2 class="text-2xl sm:text-4xl font-thin mb-3 sm:mb-4 tracking-wider text-slate-900">TOUT EST À JOUR !</h2>
                <p class="text-lg sm:text-xl text-slate-600 font-light mb-6 sm:mb-8">Aucune statistique en attente de validation</p>
                <div class="text-slate-500 font-light tracking-wider text-sm sm:text-base">
                    <i class="fas fa-trophy text-amber-600 mr-2"></i>
                    Toutes les performances ont été validées
                </div>
            </div>

            <!-- Cartes de stats à valider -->
            <div v-else class="space-y-6 sm:space-y-8">
                <div v-for="stat in props.stats"
                     :key="stat.id"
                     class="bg-white/80 backdrop-blur-sm rounded-2xl sm:rounded-3xl p-4 sm:p-6 lg:p-8 border border-slate-200 shadow-lg hover:border-slate-300 transition-all duration-300">

                    <!-- En-tête avec utilisateur et date -->
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-6 sm:mb-8">
                        <div class="flex items-center gap-3 sm:gap-6">
                            <img :src="stat.user.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                 class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl object-cover border-2 border-slate-300" />
                            <div>
                                <h3 class="text-lg sm:text-2xl font-light text-slate-900 mb-1">{{ stat.user.name }}</h3>
                                <p class="text-slate-600 font-light mb-1 sm:mb-2 text-sm sm:text-base">@{{ stat.user.pseudo }}</p>
                                <div class="flex items-center gap-2 text-slate-500 text-xs sm:text-sm group relative">
                                    <i class="fas fa-calendar text-xs sm:text-sm"></i>
                                    {{ format(stat.date) }}
                                    <!-- Tooltip avec date relative au survol -->
                                    <div class="absolute bottom-full left-0 mb-2 px-2 py-1 bg-slate-700 text-white text-xs rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap z-10">
                                        {{ formatRelative(stat.date) }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gradient-to-br from-slate-100 to-slate-200 px-3 sm:px-4 py-1 sm:py-2 rounded-xl sm:rounded-2xl border border-slate-300 w-full sm:w-auto text-center">
                            <div class="flex items-center justify-center gap-2 text-slate-600 font-light tracking-wider text-xs sm:text-sm">
                                <i class="fas fa-clock text-xs sm:text-sm"></i>
                                EN ATTENTE
                            </div>
                        </div>
                    </div>

                    <!-- Stats détaillées -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
                        <div class="bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-xl sm:rounded-2xl p-3 sm:p-6 text-center border border-indigo-300">
                            <i class="fas fa-futbol text-indigo-600 text-xl sm:text-3xl mb-2 sm:mb-3"></i>
                            <p class="text-2xl sm:text-4xl font-thin text-indigo-700 mb-1 sm:mb-2">{{ stat.goals }}</p>
                            <p class="text-xs sm:text-sm text-slate-600 font-light tracking-wider">BUTS</p>
                        </div>
                        <div class="bg-gradient-to-br from-violet-100 to-violet-200 rounded-xl sm:rounded-2xl p-3 sm:p-6 text-center border border-violet-300">
                            <i class="fas fa-hands-helping text-violet-600 text-xl sm:text-3xl mb-2 sm:mb-3"></i>
                            <p class="text-2xl sm:text-4xl font-thin text-violet-700 mb-1 sm:mb-2">{{ stat.assists }}</p>
                            <p class="text-xs sm:text-sm text-slate-600 font-light tracking-wider">PASSES</p>
                        </div>
                        <div class="bg-gradient-to-br from-amber-100 to-amber-200 rounded-xl sm:rounded-2xl p-3 sm:p-6 text-center border border-amber-300">
                            <i class="fas fa-shield-alt text-amber-600 text-xl sm:text-3xl mb-2 sm:mb-3"></i>
                            <p class="text-2xl sm:text-4xl font-thin text-amber-700 mb-1 sm:mb-2">{{ stat.goals_against }}</p>
                            <p class="text-xs sm:text-sm text-slate-600 font-light tracking-wider">ENCAISSÉS</p>
                        </div>
                        <div class="bg-gradient-to-br from-rose-100 to-rose-200 rounded-xl sm:rounded-2xl p-3 sm:p-6 text-center border border-rose-300">
                            <i class="fas fa-calendar-check text-rose-600 text-xl sm:text-3xl mb-2 sm:mb-3"></i>
                            <p class="text-2xl sm:text-4xl font-thin text-rose-700 mb-1 sm:mb-2">{{ stat.matches_played }}</p>
                            <p class="text-xs sm:text-sm text-slate-600 font-light tracking-wider">MATCHS</p>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <button @click="validateStat(stat.id)"
                                :disabled="form.processing"
                                class="w-full sm:flex-1 bg-gradient-to-r from-indigo-100 to-indigo-200 text-indigo-700 px-4 sm:px-6 py-2 sm:py-4 rounded-xl sm:rounded-2xl font-light hover:from-indigo-200 hover:to-indigo-300 transition-all duration-300 tracking-wider border border-indigo-300 flex items-center justify-center gap-2 sm:gap-3 text-sm sm:text-base">
                            <i class="fas fa-check-circle text-sm sm:text-base"></i>
                            <span class="hidden sm:inline">VALIDER cette statistique</span>
                            <span class="sm:hidden">VALIDER</span>
                        </button>

                        <button @click="rejectStat(stat.id)"
                                :disabled="form.processing"
                                class="w-full sm:flex-1 bg-gradient-to-r from-rose-100 to-rose-200 text-rose-700 px-4 sm:px-6 py-2 sm:py-4 rounded-xl sm:rounded-2xl font-light hover:from-rose-200 hover:to-rose-300 transition-all duration-300 tracking-wider border border-rose-300 flex items-center justify-center gap-2 sm:gap-3 text-sm sm:text-base">
                            <i class="fas fa-times-circle text-sm sm:text-base"></i>
                            <span class="hidden sm:inline">REJETER</span>
                            <span class="sm:hidden">REJETER</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
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
