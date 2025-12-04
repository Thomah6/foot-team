<script setup>
import { ref, watch, onMounted, computed } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";

const props = defineProps({
    soldeTotal: {
        type: Number,
        required: true,
    },
});

const montant = ref(0);
const sens = ref("credit");
const description = ref("");

const showConfirm = ref(false);
const showConfirmLoading = ref(false);

const isDebitInterdit = computed(() => {
    return sens.value === 'debit' && props.soldeTotal < montant.value;
});

function openConfirm() {
    if (isDebitInterdit.value) {
        return;
    }
     if (!montant.value || !description.value) {
        return
    }
    showConfirm.value = true;
}

function confirmSubmit() {
    showConfirmLoading.value = true;
    router.post(
        route("finances.storeAjustement"),
        {
            montant: montant.value,
            sens: sens.value,
            description: description.value,
        },
        {
            onSuccess: (page) => {
                showConfirm.value = false;
                montant.value = 0;
                sens.value = "credit";
                description.value = "";
                setTimeout(() => {
                    router.visit(route("finances.index"));
                }, 1500);
            },
            onFinish: () => {
                showConfirmLoading.value = false;
            },
            onError: () => {
                showConfirm.value = false;
                showConfirmLoading.value = false;
            },
        }
    );
}

function cancelSubmit() {
    showConfirm.value = false;
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="relative min-h-screen bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iIzExMTExMSIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjMTExMTExIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')] opacity-20 dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')]"></div>
            
            <div class="relative max-w-2xl mx-auto px-4 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <Link :href="route('finances.index')"
                          class="inline-flex items-center gap-2 text-lime-700 dark:text-emerald-400 hover:text-lime-800 dark:hover:text-emerald-300 mb-6 transition-colors font-semibold">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la caisse
                    </Link>
                    
                    <h1 class="text-3xl font-black text-slate-950 dark:text-white mb-2">
                        ⚡ AJUSTEMENT MANUEL
                    </h1>
                    <p class="text-slate-700 dark:text-gray-400">
                        Correction directe de la trésorerie - Action réservée
                    </p>
                </div>

                <!-- Form Card -->
                <div class="relative overflow-hidden rounded-2xl bg-slate-50 dark:bg-gray-900/95 backdrop-blur-sm border-2 border-purple-300 dark:border-purple-800/60 p-6 shadow-xl">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-purple-400/10 to-blue-600/10 rounded-full -translate-y-12 translate-x-12"></div>
                    
                    <div class="space-y-6 relative z-10">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-bold text-slate-950 dark:text-white mb-3 uppercase tracking-wider">
                                Type d'ajustement
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    @click="sens = 'credit'"
                                    :class="[
                                        'flex items-center justify-center gap-3 py-3 rounded-xl border-2 font-bold transition-all',
                                        sens === 'credit'
                                            ? 'bg-gradient-to-r from-emerald-500 to-lime-500 border-emerald-400 dark:border-emerald-500 text-white shadow-lg'
                                            : 'bg-white dark:bg-gray-800 border-emerald-300 dark:border-emerald-800/60 text-slate-950 dark:text-slate-300 hover:border-emerald-400 dark:hover:border-emerald-500'
                                    ]"
                                >
                                    <i class="fas fa-plus-circle"></i>
                                    CRÉDIT (+)
                                </button>
                                
                                <button
                                    @click="sens = 'debit'"
                                    :class="[
                                        'flex items-center justify-center gap-3 py-3 rounded-xl border-2 font-bold transition-all',
                                        sens === 'debit'
                                            ? 'bg-gradient-to-r from-red-500 to-pink-600 border-red-400 dark:border-red-500 text-white shadow-lg'
                                            : 'bg-white dark:bg-gray-800 border-red-300 dark:border-red-800/60 text-slate-950 dark:text-slate-300 hover:border-red-400 dark:hover:border-red-500'
                                    ]"
                                >
                                    <i class="fas fa-minus-circle"></i>
                                    DÉBIT (-)
                                </button>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-bold text-slate-950 dark:text-white mb-2 uppercase tracking-wider">
                                <i class="fas fa-coins mr-1 text-purple-600 dark:text-purple-400"></i>
                                Montant de l'ajustement
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    v-model.number="montant"
                                    class="w-full px-4 py-3 pl-12 bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900/40 outline-none transition-all"
                                    placeholder="Ex: 20000"
                                />
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-600 dark:text-purple-400 pointer-events-none">
                                    <i class="fas fa-coins"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-slate-950 dark:text-white mb-2 uppercase tracking-wider">
                                <i class="fas fa-pen mr-1 text-purple-600 dark:text-purple-400"></i>
                                Raison de l'ajustement
                            </label>
                            <input
                                type="text"
                                v-model="description"
                                maxlength="150"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border-2 border-purple-300 dark:border-purple-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 focus:border-purple-500 dark:focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900/40 outline-none transition-all"
                                placeholder='Ex: "Correction erreur de saisie"'
                            />
                        </div>

                        <!-- Debit Forbidden Warning -->
                        <div v-if="isDebitInterdit" class="!mt-8 p-4 rounded-xl bg-red-100 dark:bg-red-900/40 border-2 border-red-300 dark:border-red-800">
                            <p class="text-center text-red-900 dark:text-red-200 font-bold">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Le montant du débit ne peut pas dépasser le solde total disponible.
                            </p>
                        </div>

                        <!-- General Warning -->
                        <div v-else class="p-4 rounded-xl bg-amber-100 dark:bg-amber-900/30 border-2 border-amber-300 dark:border-amber-800">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-amber-700 dark:text-amber-400 text-lg mt-0.5 flex-shrink-0"></i>
                                <div>
                                    <p class="text-sm font-bold text-amber-900 dark:text-amber-300">
                                        ATTENTION : ACTION IRRÉVERSIBLE
                                    </p>
                                    <p class="text-xs text-amber-800 dark:text-amber-400 mt-1">
                                        Cette opération modifie directement la trésorerie sans validation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <Link
                                :href="route('finances.index')"
                                class="flex items-center justify-center gap-2 py-3 px-4 bg-white dark:bg-gray-800 border-2 border-slate-300 dark:border-slate-700 text-slate-950 dark:text-slate-300 rounded-xl font-bold hover:bg-slate-100 dark:hover:bg-gray-700 transition-all"
                            >
                                <i class="fas fa-times"></i>
                                ANNULER
                            </Link>

                            <button
                                @click="openConfirm"
                                :disabled="isDebitInterdit"
                                :class="{ 'opacity-50 cursor-not-allowed': isDebitInterdit }"
                                class="flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-purple-600 to-blue-700 dark:from-purple-500 dark:to-blue-600 text-white rounded-xl font-bold shadow-lg hover:shadow-purple-500/30 transform hover:scale-[1.02] active:scale-95 transition-all"
                            >
                                <i class="fas fa-bolt"></i>
                                APPLIQUER L'AJUSTEMENT
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <ConfirmModalFinance
                :show="showConfirm"
                :loading="showConfirmLoading"
                title="⚠️ CONFIRMER L'AJUSTEMENT"
                :message="`${sens === 'credit' ? 'CRÉDITER' : 'DÉBITER'} le compte de ${montant} FCFA ?`"
                @confirm="confirmSubmit"
                @cancel="cancelSubmit"
            />
        </div>
    </AuthenticatedLayout>
</template>
