<script setup>
import { ref,defineProps } from 'vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    pendingCount: Number,
    validatedCount: Number,
    lastStats: Array
})

// Formulaire pour valider/rejeter
const form = useForm({})

const validateStat = (statId) => {
    form.post(`/admin/stats/${statId}/validate`, {
        preserveScroll: true,
        onSuccess: () => {
            // La page se mettra à jour automatiquement via Inertia
        }
    })
}

const showRejectModal = ref(false)
const rejectingId = ref(null)

const rejectStat = (statId) => {
    rejectingId.value = statId
    showRejectModal.value = true
}

const confirmReject = () => {
    if (!rejectingId.value) return
    form.delete(`/admin/stats/${rejectingId.value}/reject`, {
        preserveScroll: true,
    })
    rejectingId.value = null
}

// Formatage des dates JJ/MM/AAAA
const format = (date) => {
    try {
        const dateObj = new Date(date);
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
    <AuthenticatedLayout>
        <div class="relative min-h-screen w-full bg-gradient-to-b from-citron-50 via-citron-100 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 text-gray-900 dark:text-citron-50 font-sans">
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-citron-50 dark:from-gray-900 dark:to-gray-800 py-8 sm:py-12 lg:py-16 px-4 border-b border-citron-200 dark:border-gray-700">
                <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                    <!-- Logo et titre -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-citron-200 to-citron-300 dark:from-citron-800 dark:to-citron-700 rounded-xl flex items-center justify-center border border-citron-300 dark:border-citron-600">
                            <i class="fas fa-chart-bar text-citron-700 dark:text-citron-300 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-citron-700 dark:text-citron-300 font-light text-lg sm:text-xl tracking-wider hidden sm:block dark:text-white">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center order-first sm:order-none">
                        <div class="text-citron-600 dark:text-citron-400 text-lg sm:text-xl font-light tracking-widest mb-1 sm:mb-2 dark:text-white">STATISTIQUES</div>
                        <div class="text-citron-500 dark:text-citron-500 text-xs sm:text-sm tracking-widest">Gestion des stats</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-20 sm:w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8 sm:mb-12">
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-thin text-gray-900 dark:text-citron-50 tracking-wider mb-3 sm:mb-4 dark:text-lime-500">TABLEAU DE BORD</h1>
                    <div class="text-citron-600 dark:text-citron-400 text-sm sm:text-lg tracking-widest font-light dark:text-lime-500">Validation des statistiques</div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-16">
                <!-- Stats Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                <!-- Pending stats -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg hover:shadow-citron-500/20 transition-all duration-300 transform hover:scale-105">
                    <div class="relative">
                        <div class="bg-gradient-to-br from-red-100 to-red-200 dark:from-red-900/50 dark:to-red-800/50 w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-red-300 dark:border-red-700">
                            <i class="fas fa-hourglass-half text-red-600 dark:text-red-400 text-3xl md:text-4xl animate-pulse"></i>
                        </div>
                        <!-- Badge alerte si > 0 -->
                        <div v-if="props.pendingCount > 0" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 rounded-full flex items-center justify-center shadow-lg animate-bounce">
                            <span class="text-white text-sm font-bold">{{ props.pendingCount }}</span>
                        </div>
                    </div>
                    <h3 class="text-xl md:text-2xl font-thin text-gray-900 dark:text-citron-50 mb-3 tracking-wide">EN ATTENTE</h3>
                    <p class="text-4xl md:text-5xl font-thin text-red-600 dark:text-red-400 mb-4 md:mb-6">{{ props.pendingCount }}</p>
                    <Link href="/admin/stats/pending"
                          class="inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-red-500 to-red-600 text-white px-4 md:px-6 py-2.5 md:py-3 rounded-2xl font-light hover:from-red-600 hover:to-red-700 transition-all duration-300 tracking-wider shadow-lg hover:shadow-red-500/30 transform hover:scale-105">
                        <i class="fas fa-check"></i>
                        <span class="text-sm md:text-base">VALIDER</span>
                    </Link>
                </div>

                <!-- Validated stats -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg hover:shadow-citron-500/20 transition-all duration-300 transform hover:scale-105">
                    <div class="bg-gradient-to-br from-green-100 to-green-200 dark:from-green-900/50 dark:to-green-800/50 w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-green-300 dark:border-green-700">
                        <i class="fas fa-circle-check text-green-600 dark:text-green-400 text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-thin text-gray-900 dark:text-citron-50 mb-3 tracking-wide">VALIDÉES</h3>
                    <p class="text-4xl md:text-5xl font-thin text-green-600 dark:text-green-400 mb-4 md:mb-6">{{ props.validatedCount }}</p>
                    <div class="text-gray-600 dark:text-citron-300 font-light tracking-wider text-sm md:text-base">
                        STATS PUBLIÉES
                    </div>
                </div>

                <!-- Add new stats -->
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-2xl p-6 md:p-8 text-center border border-citron-200 dark:border-gray-700 shadow-lg hover:shadow-citron-500/20 transition-all duration-300 transform hover:scale-105">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 dark:from-blue-900/50 dark:to-blue-800/50 w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-blue-300 dark:border-blue-700">
                        <i class="fas fa-chart-line text-blue-600 dark:text-blue-400 text-3xl md:text-4xl"></i>
                    </div>
                    <h3 class="text-xl md:text-2xl font-thin text-gray-900 dark:text-citron-50 mb-3 tracking-wide">AJOUTER</h3>
                    <p class="text-4xl md:text-5xl font-thin text-blue-600 dark:text-blue-400 mb-4 md:mb-6">+</p>
                    <Link href="/admin/stats/create"
                          class="inline-flex items-center gap-2 md:gap-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 md:px-6 py-2.5 md:py-3 rounded-2xl font-light hover:from-blue-600 hover:to-blue-700 transition-all duration-300 tracking-wider shadow-lg hover:shadow-blue-500/30 transform hover:scale-105">
                        <i class="fas fa-plus"></i>
                        <span class="text-sm md:text-base">NOUVELLE STATS</span>
                    </Link>
                </div>
            </div>

            <!-- Historique récent -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-3xl p-6 md:p-12 text-gray-900 dark:text-citron-50 border border-citron-200 dark:border-gray-700 shadow-lg">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
                    <div>
                        <h2 class="text-2xl md:text-4xl font-thin mb-2 tracking-wider text-gray-900 dark:text-citron-50">HISTORIQUE RÉCENT</h2>
                        <p class="text-gray-600 dark:text-citron-300 font-light">Dernières performances enregistrées</p>
                    </div>
                    <div class="bg-gradient-to-br from-citron-100 to-citron-200 dark:from-gray-800 dark:to-gray-700 w-12 h-12 md:w-16 md:h-16 rounded-2xl flex items-center justify-center border border-citron-300 dark:border-gray-600 flex-shrink-0">
                        <i class="fas fa-history text-citron-600 dark:text-citron-400 text-xl md:text-2xl"></i>
                    </div>
                </div>

                <!-- Message si aucun historique -->
                <div v-if="props.lastStats.length === 0"
                     class="text-center py-8 md:py-12">
                    <div class="bg-citron-100 dark:bg-gray-800 w-24 h-24 md:w-32 md:h-32 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-citron-300 dark:border-gray-600">
                        <i class="fas fa-folder-open text-citron-500 dark:text-citron-400 text-3xl md:text-4xl"></i>
                    </div>
                    <p class="text-gray-600 dark:text-citron-300 text-lg md:text-xl font-light">Aucune statistique récente pour le moment.</p>
                </div>

                <!-- Liste des dernières stats -->
                <div v-else class="space-y-3 md:space-y-4">
                    <div v-for="item in props.lastStats" 
                         :key="item.id"
                         class="bg-citron-50 dark:bg-gray-800/50 rounded-2xl p-4 md:p-6 border border-citron-200 dark:border-gray-700 hover:bg-citron-100 dark:hover:bg-gray-800/70 transition-all duration-300 hover:shadow-lg hover:shadow-citron-500/10">
                        <div class="flex flex-col md:flex-row items-start md:items-center gap-4">
                            <!-- Avatar joueur -->
                            <div class="relative flex-shrink-0">
                                <img :src="item.user?.avatar ? '/storage/' + item.user.avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(item.user?.name || 'J')}&background=citron-500&color=white&size=64`"
                                     class="h-12 w-12 md:h-16 md:w-16 rounded-2xl object-cover border-2 border-citron-300 dark:border-citron-600" />
                                <!-- Badge statut -->
                                <div class="absolute -bottom-1 -right-1 w-4 h-4 md:w-5 md:h-5 rounded-full border-2 border-white dark:border-gray-800"
                                     :class="item.validated_by_admin ? 'bg-green-500' : 'bg-yellow-500 animate-pulse'"></div>
                            </div>

                            <!-- Infos joueur -->
                            <div class="flex-grow min-w-0">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-2">
                                    <h4 class="text-lg md:text-xl font-light text-gray-900 dark:text-citron-50 truncate">{{ item.user?.name || 'Joueur inconnu' }}</h4>
                                    <span class="text-xs font-semibold py-1 px-3 rounded-full whitespace-nowrap"
                                          :class="item.validated_by_admin 
                                              ? 'bg-green-100 text-green-700 dark:bg-green-900/50 dark:text-green-300 border border-green-300 dark:border-green-700' 
                                              : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300 border border-yellow-300 dark:border-yellow-700 animate-pulse'">
                                        {{ item.validated_by_admin ? 'Validée' : 'En attente' }}
                                    </span>
                                </div>
                                <div class="flex flex-col sm:flex-row sm:items-center gap-2 text-gray-600 dark:text-citron-300 font-light text-sm md:text-base">
                                    <div class="flex items-center gap-4">
                                        <span class="font-medium text-gray-900 dark:text-citron-50">{{ item.goals }}</span>
                                        <span class="text-xs">⚽</span>
                                        <span class="font-medium text-gray-900 dark:text-citron-50">{{ item.assists }}</span>
                                        <span class="text-xs">🎯</span>
                                        <span class="text-xs">match du {{ format(item.date) }}</span>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-citron-400">
                                        {{ formatRelative(item.date) }}
                                    </div>
                                </div>
                            </div>

                            <!-- Boutons d'action si en attente -->
                            <div v-if="!item.validated_by_admin" class="flex flex-row gap-2 md:flex-col md:gap-3 flex-shrink-0">
                                <button @click="validateStat(item.id)"
                                        class="bg-green-500 text-white px-3 py-2 md:px-4 md:py-2 rounded-xl font-light hover:bg-green-600 transition-all duration-300 shadow hover:shadow-green-500/30 transform hover:scale-105 text-sm md:text-base">
                                    <i class="fas fa-check mr-1"></i>
                                    <span class="hidden sm:inline">Valider</span>
                                </button>
                                <button @click="rejectStat(item.id)"
                                        class="bg-red-500 text-white px-3 py-2 md:px-4 md:py-2 rounded-xl font-light hover:bg-red-600 transition-all duration-300 shadow hover:shadow-red-500/30 transform hover:scale-105 text-sm md:text-base">
                                    <i class="fas fa-times mr-1"></i>
                                    <span class="hidden sm:inline">Rejeter</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </AuthenticatedLayout>
    <ConfirmModal v-model="showRejectModal" title="Rejeter la statistique" message="Êtes-vous sûr de vouloir rejeter cette statistique ?" confirm-text="Rejeter" cancel-text="Annuler" variant="danger" @confirm="confirmReject" />
</template>
