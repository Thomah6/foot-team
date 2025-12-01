<template>
    <AuthenticatedLayout>
        <Layout>
            <div class="max-w-full  mx-auto py-10 px-4 sm:px-6 lg:px-8">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-[2rem] max-h-[95vh] ">

                    <div class="max-w-2xs max-h-[95vh] mx-auto md:mx-0 flex flex-col">
                        <CreateForm/>
                        <h1 class="mt-6">Mes reflexions</h1>
                        <div v-for="reflection in reflections.filter(reflection => reflection.user_id === $page.props.auth.user?.id)" class="scroller mt-6 max-h-90 overflow-y-auto w-full" :key="reflection.id">
                            <UserReflection 
                            :reflection="reflection" 
                            @edit="openEditModal"
                            @delete="confirmDelete"
                            />
                        </div>
                        
                    </div>
                    <div class="scroller overflow-y-scroll">

                        <div class=" scroller space-y-6 max-h-[900px] overflow-y-auto">
                            <div v-if="reflections.length === 0"
                                class="text-center p-8 bg-gray-50 rounded-xl border border-gray-200">
                                <p class="text-gray-500 italic text-lg">
                                    Aucune réflexion active n'a été trouvée pour les derniers 7 jours.
                                </p>
                            </div>

                            <ReflectionCard 
                                v-for="reflection in sortedReflections" 
                                :key="reflection.id"
                                :reflection="reflection" 
                            />
                        </div>
                    </div>
                </div>
            </div>
        </Layout>



        <!-- ================== MODAL D'ÉDITION ================== -->
<div 
    v-if="editModalOpen" 
    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
>
    <div class="bg-white rounded-lg p-6 w-[400px] shadow-xl">

        <h2 class="text-xl font-semibold mb-4">Modifier la réflexion</h2>
        
        <label class="block text-sm mb-1">Titre</label>
        <input type="text"
            name="titre"
            v-model="form.titre"

            class="cursor-custom-icon w-full my-6 p-4 border-2 border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 resize-none text-gray-700 placeholder-gray-400 font-medium"
            placeholder="Choisissez un titre percutant"
        />

        <label class="block text-sm mb-1">Contenu :</label>
        <textarea 
            name="contenu"
            v-model="form.contenu"
            class="w-full border rounded p-2 mb-4"
            rows="4"
        ></textarea>


        <div class="flex justify-end gap-2">
            <button 
                @click="editModalOpen = false"
                class="px-4 py-2 bg-gray-500 text-white rounded"
            >
                Annuler
            </button>

            <button 
                @click="saveReflection"
                class="px-4 py-2 bg-blue-600 text-white rounded"
            >
                Enregistrer
            </button>
        </div>
    </div>
</div>

    </AuthenticatedLayout>
</template>

<script setup>
import { defineProps, computed, onMounted } from "vue";
import { ref } from "vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
// import Layout from "@/Layouts/Layout.vue";
import UserReflection from "./UserReflection.vue";
import CreateForm from "./CreateForm.vue";
import ReflectionCard from "@/Components/ReflectionCard.vue";
import { useForm } from '@inertiajs/vue3';

// Récupération des props (ne pas destructurer directement defineProps)
const props = defineProps({
    reflections: {
        type: Array,
        default: () => []
    },
    updateNotifications: {
        type: Function,
        required: false // Peut ne pas être nécessaire partout
    }
});


const notifications = computed(() => {
    return props.reflections.filter(r => r.statut === 'ferme' || r.votes.find(v => v.user_id === $page.props.auth.user?.id)===undefined).length;
});
onMounted(() => {
    console.log("Notifications:", notifications.value);
    // 2. Vérifiez et appelez la prop
    if (props.updateNotifications) {
        props.updateNotifications(notifications.value);
    }else{
        console.log("La prop updateNotifications n'est pas fournie.");
    }
});

// exposer reflections pour le template (réactif)
const reflections = props.reflections;

// computed : filtrer (ouvert | valide) et trier (ouvert avant valide)
const sortedReflections = computed(() => {
    return reflections
        .filter(r => ['ouvert', 'valide'].includes(r.statut))
        .sort((a, b) => {
            const order = { ouvert: 1, valide: 2 };
            return order[a.statut] - order[b.statut];
        });
});

// ============= AJOUT DE TOUT CE QUI MANQUAIT =============

// 1️⃣ État pour savoir si le modal est ouvert
const editModalOpen = ref(false);

// 2️⃣ Réflexion actuellement éditée (stocke id au minimum)
const editingReflection = ref(null);

// 3️⃣ useForm pour l'édition (titre/contenu)
// initialise vide ; on ouvrira le modal en remplissant form avec les valeurs
const form = useForm({
    titre: '',
    contenu: ''
});

// 4️⃣ Fonction quand l’enfant émet @edit
function openEditModal(reflection) {
    editingReflection.value = { ...reflection }; // clone pour la référence
    // remplir le form avec les valeurs de la réflexion
    form.titre = reflection.titre ?? reflection.titre ?? '';    // adapte au champ exact
    form.contenu = reflection.contenu ?? reflection.contenu ?? '';
    editModalOpen.value = true;
}

// 5️⃣ Fonction pour sauvegarder les modifications via useForm
function saveReflection() {
    if (!editingReflection.value) return;

    // URL de la route: reflections.update (id)
    form.put(route('reflections.update', editingReflection.value.id), {
        preserveScroll: true,
        onSuccess: () => {
            editModalOpen.value = false;
            // facultatif : réinitialiser le form
            // form.reset(); // si tu veux tout vider
        },
        onError: (errors) => {
            // errors est un objet envoyé par le backend (validation)
            console.error("Validation errors:", errors);
        }
    });
}

// 6️⃣ Fonction pour supprimer une réflexion (useForm.delete)
function confirmDelete(id) {
    if (!confirm("Voulez-vous vraiment supprimer cette réflexion ?")) {
        return;
    }

    // utilise form.delete; on passe une route différente mais c'est OK
    form.delete(route('reflections.destroy', id), {
        preserveScroll: true,
        onSuccess: () => {
            // rien de particulier à faire, Inertia mettra à jour la page
        },
        onError: (err) => {
            console.error("Erreur suppression:", err);
        }
    });
}
</script>


<style scoped>
.scroller {
    scrollbar-width: none;
    scrollbar-color: #a0aec0 transparent;
}
</style>
