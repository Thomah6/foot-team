<script setup>
import { ref, defineProps, computed } from 'vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Card from '@/Components/Card.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';

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
    showRejectModal.value = false
}

// Computed pour afficher les stats en attente en premier
const sortedStats = computed(() => {
    if (!props.lastStats) return []
    return [...props.lastStats].sort((a, b) => {
        if (a.validated_by_admin === b.validated_by_admin) return 0
        return a.validated_by_admin ? 1 : -1
    })
})
</script>

<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <i class="fas fa-chart-bar text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="heading-1">Statistiques & Performances</h1>
                            <p class="text-gray-500">Gestion des stats joueurs</p>
                        </div>
                    </div>
                    <Link href="/admin/create" class="flex items-center gap-2 px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition font-medium">
                        <i class="fas fa-plus"></i>
                        Ajouter une stat
                    </Link>
                </div>

                <!-- Stats Summary Cards -->
                <section class="section">
                    <h2 class="heading-3 mb-4">Résumé</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Pending Stats Card -->
                        <Card variant="elevated" padding="lg">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium mb-2">
                                        <i class="fas fa-clock mr-2 text-warning-500"></i>
                                        En attente de validation
                                    </p>
                                    <p class="heading-2 text-warning-600">{{ props.pendingCount }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Stats à examiner</p>
                                </div>
                                <div class="text-4xl text-warning-100">
                                    <i class="fas fa-hourglass-half"></i>
                                </div>
                            </div>
                            <Link href="/admin/stats/pending" class="block mt-4 w-full text-center px-3 py-2 bg-warning-100 hover:bg-warning-200 text-warning-700 rounded-lg transition text-sm font-medium">
                                Valider maintenant
                            </Link>
                        </Card>

                        <!-- Validated Stats Card -->
                        <Card variant="elevated" padding="lg">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium mb-2">
                                        <i class="fas fa-check-circle mr-2 text-success-500"></i>
                                        Validées ce mois
                                    </p>
                                    <p class="heading-2 text-success-600">{{ props.validatedCount }}</p>
                                    <p class="text-xs text-gray-500 mt-1">Stats confirmées</p>
                                </div>
                                <div class="text-4xl text-success-100">
                                    <i class="fas fa-badge-check"></i>
                                </div>
                            </div>
                        </Card>

                        <!-- Classements Card -->
                        <Card variant="elevated" padding="lg">
                            <div class="flex items-start justify-between">
                                <div>
                                    <p class="text-gray-600 text-sm font-medium mb-2">
                                        <i class="fas fa-trophy mr-2 text-accent-500"></i>
                                        Classements
                                    </p>
                                    <p class="heading-2 text-accent-600">Mis à jour</p>
                                    <p class="text-xs text-gray-500 mt-1">Automatiquement</p>
                                </div>
                                <div class="text-4xl text-accent-100">
                                    <i class="fas fa-crown"></i>
                                </div>
                            </div>
                            <Link href="/classements" class="block mt-4 w-full text-center px-3 py-2 bg-accent-100 hover:bg-accent-200 text-accent-700 rounded-lg transition text-sm font-medium">
                                Voir classements
                            </Link>
                        </Card>
                    </div>
                </section>

                <!-- Recent Stats Table -->
                <section class="section">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="heading-3">Historique récent</h2>
                        <span class="text-xs px-3 py-1 bg-primary-100 text-primary-700 rounded-full font-medium">
                            {{ props.lastStats?.length ?? 0 }} stats
                        </span>
                    </div>

                    <!-- Empty State -->
                    <div v-if="!props.lastStats || props.lastStats.length === 0" class="card">
                        <div class="text-center py-12">
                            <div class="text-6xl text-gray-300 mb-4">
                                <i class="fas fa-inbox"></i>
                            </div>
                            <p class="text-gray-500 font-medium">Aucune statistique récente</p>
                            <p class="text-sm text-gray-400 mt-1">Commencez par ajouter une nouvelle stat</p>
                        </div>
                    </div>

                    <!-- Stats Table -->
                    <div v-else class="card overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Joueur</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Match</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">Buts</th>
                                        <th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 dark:text-gray-300">Passes</th>
                                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300">Statut</th>
                                        <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="stat in sortedStats" :key="stat.id" class="hover:bg-gray-50 dark:hover:bg-gray-800 transition">
                                        <!-- Joueur -->
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <img 
                                                    :src="stat.user?.avatar || 'https://ui-avatars.com/api/?name=' + (stat.user?.name || 'Unknown')"
                                                    :alt="stat.user?.name"
                                                    class="h-10 w-10 rounded-full object-cover"
                                                />
                                                <div>
                                                    <p class="font-medium text-gray-900 dark:text-white">{{ stat.user?.name || 'Joueur inconnu' }}</p>
                                                    <p class="text-xs text-gray-500">{{ stat.user?.pseudo || '-' }}</p>
                                                </div>
                                            </div>
                                        </td>

                                        <!-- Match -->
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            {{ stat.date }}
                                        </td>

                                        <!-- Buts -->
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-primary-100 text-primary-700 font-semibold">
                                                {{ stat.goals ?? 0 }}
                                            </span>
                                        </td>

                                        <!-- Passes -->
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-accent-100 text-accent-700 font-semibold">
                                                {{ stat.assists ?? 0 }}
                                            </span>
                                        </td>

                                        <!-- Statut -->
                                        <td class="px-6 py-4">
                                            <span 
                                                class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold"
                                                :class="stat.validated_by_admin 
                                                    ? 'bg-success-100 text-success-700' 
                                                    : 'bg-warning-100 text-warning-700'"
                                            >
                                                <i :class="stat.validated_by_admin ? 'fas fa-check' : 'fas fa-clock'"></i>
                                                {{ stat.validated_by_admin ? 'Validée' : 'En attente' }}
                                            </span>
                                        </td>

                                        <!-- Actions -->
                                        <td class="px-6 py-4 text-right">
                                            <div v-if="!stat.validated_by_admin" class="flex items-center justify-end gap-2">
                                                <button 
                                                    @click="validateStat(stat.id)"
                                                    :disabled="form.processing"
                                                    class="inline-flex items-center gap-1 px-3 py-1 bg-success-500 hover:bg-success-600 text-white text-xs font-medium rounded transition disabled:opacity-50"
                                                >
                                                    <i class="fas fa-check"></i>
                                                    Valider
                                                </button>
                                                <button 
                                                    @click="rejectStat(stat.id)"
                                                    :disabled="form.processing"
                                                    class="inline-flex items-center gap-1 px-3 py-1 bg-error-100 hover:bg-error-200 text-error-700 text-xs font-medium rounded transition disabled:opacity-50"
                                                >
                                                    <i class="fas fa-ban"></i>
                                                    Rejeter
                                                </button>
                                            </div>
                                            <div v-else>
                                                <span class="text-xs text-gray-500">-</span>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Confirm Reject Modal -->
        <transition name="fade">
            <div v-if="showRejectModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="card max-w-md w-full shadow-xl animate-scale-up">
                    <div class="mb-4">
                        <h3 class="heading-3 text-error-600">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            Rejeter la statistique
                        </h3>
                    </div>
                    <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir rejeter cette statistique ? Cette action ne peut pas être annulée.</p>
                    <div class="flex gap-3 justify-end">
                        <button 
                            @click="showRejectModal = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium"
                        >
                            Annuler
                        </button>
                        <DangerButton 
                            @click="confirmReject"
                            :disabled="form.processing"
                        >
                            <i class="fas fa-trash mr-2"></i>
                            Rejeter
                        </DangerButton>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>
