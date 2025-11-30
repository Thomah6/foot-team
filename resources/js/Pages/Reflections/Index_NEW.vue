<template>
    <AuthenticatedLayout>
        <div class="page-container">
            <div class="page-wrapper">
                <!-- Page Header -->
                <div class="page-header">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-lg bg-gradient-to-br from-accent-400 to-accent-600 flex items-center justify-center">
                            <i class="fas fa-lightbulb text-white text-xl"></i>
                        </div>
                        <div>
                            <h1 class="heading-1">Réflexions & Débats</h1>
                            <p class="text-gray-500">Partage d'idées et de feedback</p>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <section class="section grid grid-cols-1 lg:grid-cols-3 gap-6">
                    
                    <!-- Left Sidebar: Create Form & My Reflections -->
                    <div class="lg:col-span-1 space-y-6">
                        <!-- Create Form Card -->
                        <Card title="Partager votre réflexion" padding="lg">
                            <CreateForm />
                        </Card>

                        <!-- My Reflections Section -->
                        <div>
                            <h2 class="heading-3 mb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-accent-600"></i>
                                Mes réflexions
                            </h2>
                            <div class="space-y-3 max-h-[600px] overflow-y-auto">
                                <div v-if="userReflections.length === 0" class="text-center py-6 text-gray-500">
                                    <i class="fas fa-inbox text-3xl text-gray-300 mb-2 block"></i>
                                    <p class="text-sm">Vous n'avez pas encore écrit de réflexion</p>
                                </div>
                                <div v-for="reflection in userReflections" :key="reflection.id" class="group">
                                    <UserReflection 
                                        :reflection="reflection" 
                                        @edit="openEditModal"
                                        @delete="confirmDelete"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Content: All Reflections -->
                    <div class="lg:col-span-2">
                        <h2 class="heading-3 mb-4 flex items-center gap-2">
                            <i class="fas fa-fire text-warning-600"></i>
                            <span>Discussions actives</span>
                            <span class="ml-auto text-sm font-normal px-3 py-1 bg-accent-100 text-accent-700 rounded-full">
                                {{ sortedReflections.length }}
                            </span>
                        </h2>

                        <!-- Empty State -->
                        <div v-if="sortedReflections.length === 0" class="card py-12 text-center">
                            <i class="fas fa-comment-dots text-5xl text-gray-300 mb-4"></i>
                            <p class="text-gray-500 font-medium mb-2">Aucune réflexion active</p>
                            <p class="text-sm text-gray-400">Commencez une discussion en partageant votre idée</p>
                        </div>

                        <!-- Reflections Grid -->
                        <div v-else class="space-y-4">
                            <ReflectionCard 
                                v-for="reflection in sortedReflections" 
                                :key="reflection.id"
                                :reflection="reflection" 
                            />
                        </div>
                    </div>
                </section>
            </div>
        </div>

        <!-- Edit Modal -->
        <transition name="fade">
            <div v-if="editModalOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="card max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-xl animate-scale-up">
                    <div class="flex items-center justify-between mb-6 sticky top-0 bg-white/95">
                        <h3 class="heading-3">
                            <i class="fas fa-edit mr-2 text-accent-600"></i>
                            Modifier la réflexion
                        </h3>
                        <button 
                            @click="editModalOpen = false"
                            class="text-gray-400 hover:text-gray-600 text-2xl"
                        >
                            ✕
                        </button>
                    </div>
                    
                    <form @submit.prevent="saveReflection" class="space-y-4">
                        <div class="form-group">
                            <InputLabel for="edit-titre" required>Titre</InputLabel>
                            <TextInput 
                                id="edit-titre"
                                v-model="form.titre"
                                placeholder="Choisissez un titre percutant"
                            />
                            <InputError :message="form.errors.titre" />
                        </div>

                        <div class="form-group">
                            <InputLabel for="edit-contenu" required>Contenu</InputLabel>
                            <textarea 
                                id="edit-contenu"
                                v-model="form.contenu"
                                placeholder="Écrivez ce qui vous vient à l'esprit..."
                                maxlength="500"
                                rows="6"
                                class="form-input"
                            ></textarea>
                            <div class="flex items-center justify-between mt-2">
                                <InputError :message="form.errors.contenu" />
                                <span class="text-xs text-gray-500">
                                    {{ 500 - form.contenu.length }} caractères restants
                                </span>
                            </div>
                        </div>

                        <div class="flex gap-3 justify-end pt-4">
                            <button 
                                type="button"
                                @click="editModalOpen = false"
                                class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium"
                            >
                                Annuler
                            </button>
                            <PrimaryButton type="submit" :disabled="form.processing">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </transition>

        <!-- Delete Confirmation Modal -->
        <transition name="fade">
            <div v-if="showDeleteConfirm" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                <div class="card max-w-md w-full shadow-xl animate-scale-up">
                    <h3 class="heading-3 text-error-600 mb-4">
                        <i class="fas fa-trash mr-2"></i>
                        Supprimer la réflexion
                    </h3>
                    <p class="text-gray-600 mb-6">Êtes-vous sûr de vouloir supprimer cette réflexion ? Cette action est irréversible.</p>
                    <div class="flex gap-3 justify-end">
                        <button 
                            type="button"
                            @click="showDeleteConfirm = false"
                            class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition font-medium"
                        >
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
import { defineProps, computed, ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Card from "@/Components/Card.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import UserReflection from "./UserReflection.vue";
import CreateForm from "./CreateForm.vue";
import ReflectionCard from "@/Components/ReflectionCard.vue";
import { useForm } from '@inertiajs/vue3';
import { usePage } from '@inertiajs/vue3';

// Props
const props = defineProps({
    reflections: {
        type: Array,
        default: () => []
    }
});

const page = usePage();

// State
const editModalOpen = ref(false);
const editingReflection = ref(null);
const showDeleteConfirm = ref(false);
const deletingId = ref(null);

// Form
const form = useForm({
    titre: '',
    contenu: ''
});

// Computed
const userReflections = computed(() => {
    return props.reflections.filter(r => r.user_id === page.props.auth.user?.id);
});

const sortedReflections = computed(() => {
    return props.reflections
        .filter(r => ['ouvert', 'valide'].includes(r.statut))
        .sort((a, b) => {
            const order = { ouvert: 1, valide: 2 };
            return order[a.statut] - order[b.statut];
        });
});

// Methods
function openEditModal(reflection) {
    editingReflection.value = { ...reflection };
    form.titre = reflection.titre || '';
    form.contenu = reflection.contenu || '';
    editModalOpen.value = true;
}

function saveReflection() {
    if (!editingReflection.value) return;

    form.put(route('reflections.update', editingReflection.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            editModalOpen.value = false;
        },
        onError: (errors) => {
            console.error("Validation errors:", errors);
        }
    });
}

function confirmDelete(id) {
    deletingId.value = id;
    showDeleteConfirm.value = true;
}

function executeDelete() {
    if (!deletingId.value) return;

    form.delete(route('reflections.destroy', deletingId.value), {
        preserveScroll: true,
        onSuccess: () => {
            showDeleteConfirm.value = false;
        },
        onError: (err) => {
            console.error("Erreur suppression:", err);
        }
    });
}
</script>
