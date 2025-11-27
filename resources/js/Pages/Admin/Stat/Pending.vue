
<script setup>
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    stats: Array
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

const format = (date) => {
    return new Date(date).toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    })
}
</script>

<template>
    <div class="flex">
        <div>
            <AdminsideBar />
        </div>
    
        <div class="relative min-h-screen w-full bg-background-light text-zinc-800 font-display">
            <div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 py-5 flex justify-center">
                <div class="max-w-[960px] w-full">

                    <!-- Header -->
                    <div class="flex justify-between items-center p-4">
                        <div>
                            <p class="text-4xl font-black tracking-tight">Validation des Stats</p>
                            <p class="text-zinc-600 mt-2">{{ props.stats.length }} statistique(s) en attente de validation</p>
                        </div>
                        <Link href="/stats" 
                              class="px-4 py-2 bg-zinc-200 text-zinc-700 rounded-lg hover:bg-zinc-300 transition">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour au tableau de bord
                        </Link>
                    </div>

                    <!-- Liste des stats en attente -->
                    <div class="space-y-4 p-4">
                        
                        <!-- Message si aucune stat en attente -->
                        <div v-if="props.stats.length === 0"
                             class="text-center py-12 bg-white rounded-xl border">
                            <i class="fas fa-check-circle text-green-500 text-6xl mb-4"></i>
                            <p class="text-xl font-semibold text-zinc-700">Tout est à jour !</p>
                            <p class="text-zinc-500 mt-2">Aucune statistique en attente de validation</p>
                        </div>

                        <!-- Cartes de stats à valider -->
                        <div v-for="stat in props.stats" 
                             :key="stat.id"
                             class="bg-white rounded-xl border p-6 hover:shadow-lg transition-shadow">

                            <!-- En-tête avec utilisateur et date -->
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center gap-4">
                                    <img :src="stat.user.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                                         class="w-12 h-12 rounded-full object-cover border-2 border-zinc-200" />
                                    <div>
                                        <p class="font-semibold text-lg">{{ stat.user.name }}</p>
                                        <p class="text-zinc-500 text-sm">{{ stat.user.pseudo }}</p>
                                        <p class="text-zinc-400 text-xs">{{ format(stat.date) }}</p>
                                    </div>
                                </div>
                                
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-semibold rounded-full">
                                    En attente
                                </span>
                            </div>

                            <!-- Stats détaillées -->
                            <div class="grid grid-cols-4 gap-4 mb-6">
                                <div class="text-center p-3 bg-green-50 rounded-lg">
                                    <i class="fas fa-futbol text-green-500 text-2xl mb-2"></i>
                                    <p class="text-2xl font-bold text-green-600">{{ stat.goals }}</p>
                                    <p class="text-xs text-zinc-600">Buts</p>
                                </div>
                                <div class="text-center p-3 bg-blue-50 rounded-lg">
                                    <i class="fas fa-hands-helping text-blue-500 text-2xl mb-2"></i>
                                    <p class="text-2xl font-bold text-blue-600">{{ stat.assists }}</p>
                                    <p class="text-xs text-zinc-600">Passes</p>
                                </div>
                                <div class="text-center p-3 bg-red-50 rounded-lg">
                                    <i class="fas fa-shield-alt text-red-500 text-2xl mb-2"></i>
                                    <p class="text-2xl font-bold text-red-600">{{ stat.goals_against }}</p>
                                    <p class="text-xs text-zinc-600">Buts encaissés</p>
                                </div>
                                <div class="text-center p-3 bg-purple-50 rounded-lg">
                                    <i class="fas fa-calendar text-purple-500 text-2xl mb-2"></i>
                                    <p class="text-2xl font-bold text-purple-600">{{ stat.matches_played }}</p>
                                    <p class="text-xs text-zinc-600">Matchs</p>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex gap-3">
                                <button @click="validateStat(stat.id)"
                                        :disabled="form.processing"
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-green-500 text-white rounded-lg hover:bg-green-600 transition disabled:opacity-50">
                                    <i class="fas fa-check"></i>
                                    Valider cette statistique
                                </button>
                                
                                <button @click="rejectStat(stat.id)"
                                        :disabled="form.processing"
                                        class="flex-1 flex items-center justify-center gap-2 px-4 py-3 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition disabled:opacity-50">
                                    <i class="fas fa-times"></i>
                                    Rejeter
                                </button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
                                        <ConfirmModal v-model="showRejectModal" title="Rejeter la statistique" message="Êtes-vous sûr de vouloir rejeter cette statistique ?" confirm-text="Rejeter" cancel-text="Annuler" variant="danger" @confirm="confirmReject" />
</template>
