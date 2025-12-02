<script setup>
import { ref, watch, onMounted } from "vue";
import { router, usePage, Link } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Toast from "@/Shared/Toast.vue";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";


const montant = ref(0);
const sens = ref("credit");
const description = ref("");


const showConfirm = ref(false);
const showConfirmLoading = ref(false);


// Toast
const page = usePage();
const toastVisible = ref(false);
const toastMessage = ref("");
const toastType = ref("success");


function showToast(message, type = "success") {
    toastMessage.value = message;
    toastType.value = type;
    toastVisible.value = true;
}


watch(
    () => page.props.flash && page.props.flash.success,
    (val) => {
        if (val) showToast(val, "success");
    }
);
watch(
    () => page.props.flash && page.props.flash.error,
    (val) => {
        if (val) showToast(val, "error");
    }
);


onMounted(() => {
    const p = page.props;
    if (p.flash && p.flash.success) showToast(p.flash.success, "success");
    if (p.flash && p.flash.error) showToast(p.flash.error, "error");
});


// 1er clic: ouvrir le modal
function openConfirm() {
    showConfirm.value = true;
}


// ConfirmModal: confirmer => envoyer la requête
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
                // Show toast if flash is provided by the server
                if (
                    page &&
                    page.props &&
                    page.props.flash &&
                    page.props.flash.success
                ) {
                    showToast(page.props.flash.success, "success");
                }
                // Delay navigation briefly so the toast is visible to the user
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
                          class="inline-flex items-center gap-2 text-lime-600 dark:text-emerald-400 hover:text-lime-700 dark:hover:text-emerald-300 mb-6 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la caisse
                    </Link>
                    
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">
                        ⚡ AJUSTEMENT MANUEL
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Correction directe de la trésorerie - Action réservée
                    </p>
                </div>

                <!-- Form Card -->
                <div class="relative overflow-hidden rounded-2xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-2 border-purple-200 dark:border-purple-800/50 p-6 shadow-xl">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-purple-400/10 to-blue-600/10 rounded-full -translate-y-12 translate-x-12"></div>
                    
                    <div class="space-y-6 relative z-10">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-3">
                                TYPE D'AJUSTEMENT
                            </label>
                            <div class="grid grid-cols-2 gap-4">
                                <button
                                    @click="sens = 'credit'"
                                    :class="[
                                        'flex items-center justify-center gap-3 py-3 rounded-xl border-2 font-bold transition-all',
                                        sens === 'credit'
                                            ? 'bg-gradient-to-r from-emerald-500 to-lime-500 border-emerald-400 dark:border-emerald-500 text-white shadow-lg'
                                            : 'bg-gradient-to-r from-white to-emerald-50/50 dark:from-gray-800 dark:to-emerald-900/20 border-emerald-200 dark:border-emerald-800/50 text-gray-900 dark:text-gray-300 hover:border-emerald-400 dark:hover:border-emerald-500'
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
                                            : 'bg-gradient-to-r from-white to-red-50/50 dark:from-gray-800 dark:to-red-900/20 border-red-200 dark:border-red-800/50 text-gray-900 dark:text-gray-300 hover:border-red-400 dark:hover:border-red-500'
                                    ]"
                                >
                                    <i class="fas fa-minus-circle"></i>
                                    DÉBIT (-)
                                </button>
                            </div>
                        </div>

                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">
                                MONTANT DE L'AJUSTEMENT
                            </label>
                            <div class="relative">
                                <input
                                    type="number"
                                    v-model.number="montant"
                                    class="w-full px-4 py-3 pl-12 bg-gradient-to-r from-white to-purple-50/50 dark:from-gray-800 dark:to-purple-900/10 border-2 border-purple-200 dark:border-purple-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-purple-400 dark:focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900/30 outline-none transition-all"
                                    placeholder="Ex: 20000"
                                />
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-purple-500 dark:text-purple-400">
                                    <i class="fas fa-coins"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">
                                RAISON DE L'AJUSTEMENT
                            </label>
                            <input
                                type="text"
                                v-model="description"
                                class="w-full px-4 py-3 bg-gradient-to-r from-white to-purple-50/50 dark:from-gray-800 dark:to-purple-900/10 border-2 border-purple-200 dark:border-purple-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-purple-400 dark:focus:border-purple-500 focus:ring-2 focus:ring-purple-200 dark:focus:ring-purple-900/30 outline-none transition-all"
                                placeholder='Ex: "Correction erreur de saisie"'
                            />
                        </div>

                        <!-- Warning -->
                        <div class="p-4 rounded-xl bg-gradient-to-r from-yellow-50 to-amber-50/50 dark:from-yellow-900/10 dark:to-amber-900/10 border-2 border-yellow-200 dark:border-yellow-800/50">
                            <div class="flex items-start gap-3">
                                <i class="fas fa-exclamation-triangle text-yellow-600 dark:text-yellow-400 text-lg mt-0.5"></i>
                                <div>
                                    <p class="text-sm font-bold text-yellow-800 dark:text-yellow-300">
                                        ATTENTION : ACTION IRRÉVERSIBLE
                                    </p>
                                    <p class="text-xs text-yellow-700/80 dark:text-yellow-400/80 mt-1">
                                        Cette opération modifie directement la trésorerie sans validation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <Link
                                :href="route('finances.index')"
                                class="flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-2 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-800 transition-all"
                            >
                                <i class="fas fa-times"></i>
                                ANNULER
                            </Link>

                            <button
                                @click="openConfirm"
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

            <Toast
                v-model="toastVisible"
                :message="toastMessage"
                :type="toastType"
            />
        </div>
    </AuthenticatedLayout>
</template>
