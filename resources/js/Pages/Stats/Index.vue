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
        <div class="relative min-h-screen w-full bg-gradient-to-b from-slate-50 via-slate-100 to-white text-slate-900 font-sans">
            <!-- Header Premium Style -->
            <div class="relative bg-gradient-to-b from-white to-slate-50 py-8 sm:py-12 lg:py-16 px-4 border-b border-slate-200">
                <div class="flex flex-col sm:flex-row items-center justify-between max-w-6xl mx-auto mb-8 sm:mb-12 gap-6">
                    <!-- Logo et titre -->
                    <div class="flex items-center gap-3 sm:gap-4">
                        <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gradient-to-br from-slate-200 to-slate-300 rounded-xl flex items-center justify-center border border-slate-300">
                            <i class="fas fa-chart-bar text-slate-700 text-lg sm:text-2xl"></i>
                        </div>
                        <div class="text-slate-700 font-light text-lg sm:text-xl tracking-wider hidden sm:block">FOOT TEAM</div>
                    </div>
                    
                    <!-- Titre central -->
                    <div class="text-center order-first sm:order-none">
                        <div class="text-slate-600 text-lg sm:text-xl font-light tracking-widest mb-1 sm:mb-2">STATISTIQUES</div>
                        <div class="text-slate-500 text-xs sm:text-sm tracking-widest">Gestion des stats</div>
                    </div>
                    
                    <!-- Logo à droite -->
                    <div class="w-20 sm:w-24"></div>
                </div>
                
                <!-- Titre principal -->
                <div class="text-center mb-8 sm:mb-12">
                    <h1 class="text-3xl sm:text-4xl lg:text-6xl font-thin text-slate-900 tracking-wider mb-3 sm:mb-4">TABLEAU DE BORD</h1>
                    <div class="text-slate-600 text-sm sm:text-lg tracking-widest font-light">Validation des statistiques</div>
                </div>
            </div>

            <div class="max-w-6xl mx-auto px-4 py-16">
                <!-- Stats Cards Grid -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                
                <!-- Pending stats -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                    <div class="bg-gradient-to-br from-red-100 to-red-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-red-300">
                        <i class="fas fa-hourglass-half text-red-600 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">EN ATTENTE</h3>
                    <p class="text-5xl font-thin text-red-600 mb-6">{{ props.pendingCount }}</p>
                    <Link href="/admin/stats/pending"
                          class="inline-flex items-center gap-3 bg-red-100 text-red-700 px-6 py-3 rounded-2xl font-light hover:bg-red-200 transition-all duration-300 tracking-wider border border-red-300">
                        <i class="fas fa-check"></i>
                        VALIDER MAINTENANT
                    </Link>
                </div>

                <!-- Validated stats -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                    <div class="bg-gradient-to-br from-green-100 to-green-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-green-300">
                        <i class="fas fa-circle-check text-green-600 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">VALIDÉES</h3>
                    <p class="text-5xl font-thin text-green-600 mb-6">{{ props.validatedCount }}</p>
                    <div class="text-slate-600 font-light tracking-wider">
                        STATS PUBLIÉES
                    </div>
                </div>

                <!-- Add new stats -->
                <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-8 text-center border border-slate-200 shadow-lg">
                    <div class="bg-gradient-to-br from-blue-100 to-blue-200 w-24 h-24 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-blue-300">
                        <i class="fas fa-plus text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-thin text-slate-900 mb-3 tracking-wide">AJOUTER</h3>
                    <p class="text-5xl font-thin text-blue-600 mb-6">+</p>
                    <Link href="/admin/stats/create"
                          class="inline-flex items-center gap-3 bg-blue-100 text-blue-700 px-6 py-3 rounded-2xl font-light hover:bg-blue-200 transition-all duration-300 tracking-wider border border-blue-300">
                        <i class="fas fa-plus"></i>
                        NOUVELLE STATS
                    </Link>
                </div>
            </div>

            <!-- Historique récent -->
            <div class="bg-white/80 backdrop-blur-sm rounded-3xl p-12 text-slate-900 border border-slate-200 shadow-lg">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-4xl font-thin mb-2 tracking-wider text-slate-900">HISTORIQUE RÉCENT</h2>
                        <p class="text-slate-600 font-light">Dernières performances enregistrées</p>
                    </div>
                    <div class="bg-gradient-to-br from-slate-100 to-slate-200 w-16 h-16 rounded-2xl flex items-center justify-center border border-slate-300">
                        <i class="fas fa-history text-slate-600 text-2xl"></i>
                    </div>
                </div>

                <!-- Message si aucun historique -->
                <div v-if="props.lastStats.length === 0"
                     class="text-center py-12">
                    <div class="bg-slate-100 w-32 h-32 rounded-2xl flex items-center justify-center mx-auto mb-6 border border-slate-300">
                        <i class="fas fa-folder-open text-slate-500 text-4xl"></i>
                    </div>
                    <p class="text-slate-600 text-xl font-light">Aucune statistique récente pour le moment.</p>
                </div>

                <!-- Liste des dernières stats -->
                <div v-else class="space-y-4">
                    <div v-for="item in props.lastStats" 
                         :key="item.id"
                         class="bg-slate-50 rounded-2xl p-6 border border-slate-200 hover:bg-slate-100 transition-colors">
                        <div class="flex items-center gap-6">
                            <img :src="item.user?.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                 class="h-16 w-16 rounded-2xl object-cover border-2 border-slate-300" />

                            <div class="flex-grow">
                                <div class="flex items-center gap-4 mb-2">
                                    <h4 class="text-xl font-light text-slate-900">{{ item.user?.name || 'Joueur inconnu' }}</h4>
                                    <span class="text-xs font-semibold py-1 px-3 rounded-full"
                                          :class="item.validated_by_admin ? 'bg-green-100 text-green-700 border border-green-300' : 'bg-yellow-100 text-yellow-700 border border-yellow-300'">
                                        {{ item.validated_by_admin ? 'Validée' : 'En attente' }}
                                    </span>
                                </div>
                                <p class="text-slate-600 font-light">
                                    <span class="text-slate-900 font-medium">{{ item.goals }}</span> buts — 
                                    <span class="text-slate-900 font-medium">{{ item.assists }}</span> passes — 
                                    match du {{ format(item.date) }}
                                </p>
                            </div>

                            <!-- Boutons d'action si en attente -->
                            <div v-if="!item.validated_by_admin" class="flex flex-col gap-2 sm:flex-row sm:gap-3">
                                <button @click="validateStat(item.id)"
                                        class="bg-green-100 text-green-700 px-3 sm:px-4 py-2 rounded-xl font-light hover:bg-green-200 transition-all duration-300 border border-green-300 text-sm sm:text-base">
                                    <i class="fas fa-check mr-1 sm:mr-2"></i>Valider
                                </button>
                                <button @click="rejectStat(item.id)"
                                        class="bg-red-100 text-red-700 px-3 sm:px-4 py-2 rounded-xl font-light hover:bg-red-200 transition-all duration-300 border border-red-300 text-sm sm:text-base">
                                    <i class="fas fa-times mr-1 sm:mr-2"></i>Rejeter
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
