<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="heading-1">Mon Historique de Votes</h1>
                            <p class="text-gray-500">Retrouve tous tes votes précédents</p>
                        </div>
                    </div>
                </div>

                <!-- Votes Grid -->
                <section class="section">
                    <!-- Empty State -->
                    <div v-if="!votes || votes.length === 0" class="card py-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium">Aucun vote pour le moment</p>
                        <p class="text-sm text-gray-400">Tu n'as pas encore voté sur les réflexions</p>
                    </div>

                    <!-- Votes List -->
                    <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <Card v-for="vote in votes" :key="vote.id" variant="elevated" padding="lg">
                            <div>
                                <!-- Réflexion Title -->
                                <h3 class="heading-3 mb-3 text-gray-900 dark:text-white line-clamp-2">
                                    {{ vote.reflection?.titre || 'Réflexion supprimée' }}
                                </h3>

                                <!-- User Info -->
                                <div v-if="vote.reflection?.user" class="flex items-center gap-2 mb-4 pb-3 border-b">
                                    <img 
                                        :src="vote.reflection.user.avatar || `https://ui-avatars.com/api/?name=${vote.reflection.user.name}`"
                                        :alt="vote.reflection.user.name"
                                        class="h-8 w-8 rounded-full object-cover"
                                    />
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ vote.reflection.user.name }}</p>
                                </div>

                                <!-- Vote Status -->
                                <div class="flex items-center justify-between mb-4">
                                    <span 
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-sm font-semibold"
                                        :class="vote.value === 1 
                                            ? 'bg-success-100 text-success-700 dark:bg-success-900 dark:text-success-300'
                                            : 'bg-error-100 text-error-700 dark:bg-error-900 dark:text-error-300'"
                                    >
                                        <i :class="vote.value === 1 ? 'fas fa-thumbs-up' : 'fas fa-thumbs-down'"></i>
                                        {{ vote.value === 1 ? 'POUR' : 'CONTRE' }}
                                    </span>
                                    <span class="text-xs text-gray-500">{{ formatDate(vote.created_at) }}</span>
                                </div>

                                <!-- Reflection Preview -->
                                <p v-if="vote.reflection?.contenu" class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    "{{ vote.reflection.contenu }}"
                                </p>
                            </div>

                            <!-- Link to Reflection -->
                            <div class="mt-4 pt-4 border-t">
                                <Link 
                                    v-if="vote.reflection"
                                    :href="route('reflections.show', vote.reflection.id)"
                                    class="text-sm text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1">
                                    Voir la réflexion
                                    <i class="fas fa-arrow-right text-xs"></i>
                                </Link>
                            </div>
                        </Card>
                    </div>
                </section>

                <!-- Stats Summary -->
                <section class="section" v-if="votes && votes.length > 0">
                    <h2 class="heading-3 mb-4">Statistiques</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Total Votes</p>
                                <p class="heading-1 text-primary-600">{{ votes.length }}</p>
                            </div>
                        </Card>
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Votes POUR</p>
                                <p class="heading-1 text-success-600">{{ votesPour }}</p>
                            </div>
                        </Card>
                        <Card padding="lg">
                            <div class="text-center">
                                <p class="text-gray-600 text-sm mb-2">Votes CONTRE</p>
                                <p class="heading-1 text-error-600">{{ votesContre }}</p>
                            </div>
                        </Card>
                    </div>
                </section>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Card from '@/Components/Card.vue'

const props = defineProps({
    votes: Array,
})

const votesPour = computed(() => props.votes?.filter(v => v.value === 1).length ?? 0)
const votesContre = computed(() => props.votes?.filter(v => v.value === -1).length ?? 0)

function formatDate(dateString) {
    const date = new Date(dateString)
    return new Intl.DateTimeFormat('fr-FR', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }).format(date)
}
</script>
