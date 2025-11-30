<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-warning-400 to-warning-600 flex items-center justify-center">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="heading-1">Suggestion Box</h1>
                            <p class="text-gray-500">Partage tes idées pour améliorer le club</p>
                        </div>
                    </div>
                    <PrimaryButton @click="showNew = true">
                        <i class="fas fa-plus mr-2"></i>
                        Nouvelle suggestion
                    </PrimaryButton>
                </div>

                <!-- Suggestions Grid -->
                <section class="section">
                    <div v-if="!suggestions || suggestions.length === 0" class="card py-12 text-center">
                        <i class="fas fa-inbox text-5xl text-gray-300 mb-4"></i>
                        <p class="text-gray-500 font-medium">Aucune suggestion pour le moment</p>
                        <p class="text-sm text-gray-400">Sois le premier à proposer une idée !</p>
                    </div>

                    <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <Card v-for="suggestion in suggestions" :key="suggestion.id" padding="lg" variant="elevated" class="hover:shadow-lg transition">
                            <!-- User Info -->
                            <div class="flex items-center gap-3 mb-4 pb-4 border-b">
                                <img 
                                    :src="suggestion.user?.avatar || `https://ui-avatars.com/api/?name=${suggestion.user?.name}`"
                                    :alt="suggestion.user?.name"
                                    class="h-10 w-10 rounded-full object-cover"
                                />
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ suggestion.user?.pseudo || suggestion.user?.name }}</p>
                                    <p class="text-xs text-gray-500">{{ timeAgo(suggestion.created_at) }}</p>
                                </div>
                                <div class="ml-auto">
                                    <button v-if="isAdmin" @click="confirmDelete(suggestion.id)" class="text-error-600 hover:text-error-700">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Content -->
                            <h3 class="heading-3 mb-2 text-gray-900 dark:text-white">{{ suggestion.title }}</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">{{ suggestion.content }}</p>

                            <!-- Reactions -->
                            <div class="flex gap-2 pt-4 border-t">
                                <button 
                                    @click="react(suggestion.id, 'like')"
                                    :class="[
                                        'flex items-center gap-1 px-3 py-1 rounded-lg text-sm font-medium transition',
                                        userReacted(suggestion, 'like') 
                                            ? 'bg-primary-100 text-primary-700 dark:bg-primary-900 dark:text-primary-300'
                                            : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                                    ]"
                                >
                                    <i class="fas fa-thumbs-up"></i>
                                    {{ countReactions(suggestion, 'like') }}
                                </button>
                                <button 
                                    @click="react(suggestion.id, 'dislike')"
                                    :class="[
                                        'flex items-center gap-1 px-3 py-1 rounded-lg text-sm font-medium transition',
                                        userReacted(suggestion, 'dislike') 
                                            ? 'bg-error-100 text-error-700 dark:bg-error-900 dark:text-error-300'
                                            : 'bg-gray-100 hover:bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300'
                                    ]"
                                >
                                    <i class="fas fa-thumbs-down"></i>
                                    {{ countReactions(suggestion, 'dislike') }}
                                </button>
                            </div>
                        </Card>
                    </div>
                </section>
            </div>
        </div>

        <!-- New Suggestion Modal -->
        <transition name="fade">
            <div v-if="showNew" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="card max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-xl animate-scale-up">
                    <div class="flex items-center justify-between mb-6 sticky top-0 bg-white/95">
                        <h3 class="heading-3">
                            <i class="fas fa-edit mr-2 text-warning-600"></i>
                            Nouvelle suggestion
                        </h3>
                        <button @click="showNew = false" class="text-gray-400 hover:text-gray-600 text-2xl">✕</button>
                    </div>

                    <form @submit.prevent="storeSuggestion" class="space-y-4">
                        <div class="form-group">
                            <InputLabel for="title" required>Titre</InputLabel>
                            <TextInput 
                                id="title"
                                v-model="form.title"
                                placeholder="Une idée courte et percutante"
                            />
                            <InputError :message="form.errors.title" />
                        </div>

                        <div class="form-group">
                            <InputLabel for="content" required>Description</InputLabel>
                            <textarea 
                                id="content"
                                v-model="form.content"
                                placeholder="Décris ton idée en détail..."
                                rows="6"
                                class="form-input"
                            ></textarea>
                            <InputError :message="form.errors.content" />
                        </div>

                        <div class="flex gap-3 justify-end pt-4">
                            <button 
                                type="button"
                                @click="showNew = false"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium"
                            >
                                Annuler
                            </button>
                            <PrimaryButton type="submit" :disabled="form.processing">
                                <i class="fas fa-paper-plane mr-2"></i>
                                Envoyer
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Delete Confirmation Modal -->
        <transition name="fade">
            <div v-if="showDelete" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="card max-w-md w-full shadow-xl animate-scale-up">
                    <h3 class="heading-3 text-error-600 mb-4">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer la suggestion
                    </h3>
                    <p class="text-gray-600 mb-6">Êtes-vous sûr ? Cette action est irréversible.</p>
                    <div class="flex gap-3 justify-end">
                        <button type="button" @click="showDelete = false" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium">
                            Annuler
                        </button>
                        <DangerButton @click="executeDelete" :disabled="form.processing">
                            <i class="fas fa-trash mr-2"></i>
                            Supprimer
                        </DangerButton>
                    </div>
                </div>
            </div>
        </transition>
    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed, ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Card from '@/Components/Card.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'

const props = defineProps({
    suggestions: Array,
})

const page = usePage()
const isAdmin = computed(() => page.props.auth.user?.role === 'admin')

const showNew = ref(false)
const showDelete = ref(false)
const deletingId = ref(null)

const form = useForm({
    title: '',
    content: ''
})

function storeSuggestion() {
    form.post(route('suggestions.store'), {
        onSuccess: () => {
            showNew.value = false
            form.reset()
        }
    })
}

function react(id, type) {
    form.post(route(`suggestions.react`, id), {
        type: type
    })
}

function userReacted(suggestion, type) {
    return suggestion.reactions?.some(r => r.user_id === page.props.auth.user?.id && r.type === type)
}

function countReactions(suggestion, type) {
    return suggestion.reactions?.filter(r => r.type === type).length ?? 0
}

function confirmDelete(id) {
    deletingId.value = id
    showDelete.value = true
}

function executeDelete() {
    if (!deletingId.value) return
    form.delete(route('suggestions.destroy', deletingId.value), {
        onSuccess: () => {
            showDelete.value = false
        }
    })
}

function timeAgo(date) {
    const diff = Date.now() - new Date(date).getTime()
    const minutes = Math.floor(diff / 60000)
    if (minutes < 60) return `${minutes}m`
    const hours = Math.floor(minutes / 60)
    if (hours < 24) return `${hours}h`
    const days = Math.floor(hours / 24)
    return `${days}j`
}
</script>
