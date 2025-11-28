<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3'
import { useForm } from '@inertiajs/vue3'
import AdminsideBar from '@/Components/AdminsideBar.vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

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
</script>

<template>
    <div class="flex">
         <div>
               <AdminsideBar />
         </div>

    <div class="relative min-h-screen w-full bg-background-light text-zinc-800 font-display">
        <div class="px-4 sm:px-8 md:px-16 lg:px-24 xl:px-40 py-5 flex justify-center">
            <div class="max-w-[960px] w-full">

                <!-- Title -->
                <div class="flex justify-between p-4">
                    <p class="text-4xl font-black tracking-tight">Stats & Performances – Administration</p>
                </div>

                <!-- Summary -->
                <h3 class="text-lg font-bold px-4 pb-2 pt-4">Résumé</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 p-4">

                    <!-- Pending stats -->
                    <div class="bg-white p-4 rounded-xl border flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <p class="text-base text-zinc-600">Stats en attente</p>
                            <div class="relative">
                                <i class="fa-solid fa-hourglass-half text-red-500 text-2xl"></i>
                                <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold h-4 w-4 flex items-center justify-center rounded-full">
                                    {{ props.pendingCount }}
                                </span>
                            </div>
                        </div>
                        <p class="text-3xl font-bold">{{ props.pendingCount }}</p>
                        <Link href="/admin/stats/pending"
                              class="w-full text-center py-2 px-4 rounded-lg bg-primary/20 text-primary font-semibold hover:bg-primary/30 transition">
                            Valider maintenant
                        </Link>
                    </div>

                    <!-- Validated stats -->
                    <div class="bg-white p-4 rounded-xl border flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <p class="text-base text-zinc-600">Stats validées</p>
                            <i class="fa-solid fa-circle-check text-green-500 text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold text-green-500">{{ props.validatedCount }}</p>
                        <div class="h-10"></div>
                    </div>

                    <!-- Classements -->
                    <div class="bg-white p-4 rounded-xl border flex flex-col gap-3">
                        <div class="flex justify-between items-center">
                            <p class="text-base text-zinc-600">Classements mis à jour</p>
                            <i class="fa-solid fa-trophy text-yellow-500 text-2xl"></i>
                        </div>
                        <p class="text-3xl font-bold">Aujourd'hui</p>
                        <Link href="/admin/stats/classements"
                              class="w-full text-center py-2 px-4 rounded-lg bg-primary/20 text-primary font-semibold hover:bg-primary/30 transition">
                            Voir classements
                        </Link>
                    </div>
                </div>
 <a href="/admin/create"
       class="px-4 py-2 bg-black text-white rounded-lg shadow hover:bg-gray-800 transition font-medium">
        Renseigner mes stats
    </a>
                <!-- Historique récent -->
                <h3 class="text-lg font-bold px-4 pb-2 pt-8">Historique récent</h3>

                <div class="flex flex-col gap-2 p-4">

                    <!-- Message si aucun historique -->
                    <div v-if="props.lastStats.length === 0"
                         class="p-4 bg-white border rounded-lg text-center text-zinc-600">
                        <i class="fa-regular fa-folder-open text-4xl mb-2 text-zinc-400"></i>
                        <p>Aucune statistique récente pour le moment.</p>
                    </div>

                    <!-- Boucle stats -->
                    <div v-for="item in props.lastStats"
                         :key="item.id"
                         class="flex items-center gap-4 p-3 bg-white rounded-lg border hover:border-zinc-300 transition">

                        <img :src="item.user?.avatar || 'https://via.placeholder.com/100x100.png/00ee99?text=J'"
                             class="h-10 w-10 rounded-full object-cover" />

                        <div class="flex-grow">
                            <p class="font-medium">{{ item.user?.name || 'Joueur inconnu' }}</p>
                            <p class="text-sm text-zinc-500">
                                {{ item.goals }} buts — {{ item.assists }} passes — match du {{ item.date }}
                            </p>
                        </div>

                        <!-- Statut -->
                        <span class="text-xs font-semibold py-1 px-3 rounded-full"
                              :class="item.validated_by_admin ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700'">
                            {{ item.validated_by_admin ? 'Validée' : 'En attente' }}
                        </span>

                        <!-- Boutons d'action si en attente -->
                        <div v-if="!item.validated_by_admin" class="flex gap-2">
                            <button @click="validateStat(item.id)"
                                    :disabled="form.processing"
                                    class="px-3 py-1 bg-green-500 text-white text-xs rounded hover:bg-green-600 transition disabled:opacity-50">
                                <i class="fas fa-check mr-1"></i>
                                Valider
                            </button>

                            <button @click="rejectStat(item.id)"
                                    :disabled="form.processing"
                                    class="px-3 py-1 bg-red-100 text-red-600 text-xs rounded hover:bg-red-200 transition disabled:opacity-50">
                                <i class="fas fa-times mr-1"></i>
                                Rejeter
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <ConfirmModal v-model="showRejectModal" title="Rejeter la statistique" message="Êtes-vous sûr de vouloir rejeter cette statistique ?" confirm-text="Rejeter" cancel-text="Annuler" variant="danger" @confirm="confirmReject" />
    </div>
</template>
