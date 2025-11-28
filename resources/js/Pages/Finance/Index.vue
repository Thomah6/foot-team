<script setup>
import FinanceStats from "@/Pages/Finance/FinanceStats.vue";
import FinanceCreateDepot from "@/Pages/Finance/FinanceCreateDepot.vue";
import FinanceAdminActions from "@/Pages/Finance/FinanceAdminActions.vue";
import FinanceFilter from "@/Pages/Finance/FinanceFilter.vue";
import FinanceHistoriqueTable from "@/Pages/Finance/FinanceHistoriqueTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Toast from "@/Shared/Toast.vue";
import ConfirmModal from "@/Components/ConfirmModal.vue";

import { ref, watch, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";

// Props reçues du backend Laravel
const props = defineProps({
    soldeCotisations: Number,
    soldeDepenses: Number,
    soldeTotal: Number,
    totalAttente: Number,
    nbAttente: Number,
    // autres
    finances: Array,
    users: Array,
});

// Filtrage dynamique
const filteredfinances = ref(props.finances);

// Toast notification (Inertia flash)
const page = usePage();
const toastVisible = ref(false);
const toastMessage = ref("");
const toastType = ref("success");

function showToast(message, type = "success") {
    toastMessage.value = message;
    toastType.value = type;
    toastVisible.value = true;
}

// watch success and error flash messages
watch(
    () => page.props.flash && page.props.value.flash.success,
    (val) => {
        if (val) showToast(val, "success");
    }
);
watch(
    () => page.props.flash && page.props.value.flash.error,
    (val) => {
        if (val) showToast(val, "error");
    }
);

onMounted(() => {
    const p = page.props;
    if (p.flash && p.flash.success) showToast(p.flash.success, "success");
    if (p.flash && p.flash.error) showToast(p.flash.error, "error");
});

function handleFiltre({ selectedUser, dateFrom, dateTo, selectedType }) {
    router.get(
        route("finances.index"),
        {
            member_id: selectedUser,
            date_from: dateFrom,
            date_to: dateTo,
            type: selectedType,
        },
        {
            preserveState: true,
            preserveScroll: true,
            // ⚠️ si tu veux que les stats se mettent à jour avec les filtres,
            // enlève "only" OU ajoute les clés nécessaires
            // only: ['finances'],
            onSuccess: (page) => {
                filteredfinances.value = page.props.finances;
            },
        }
    );
}

const showConfirmAll = ref(false);

function askValiderTous() {
    showConfirmAll.value = true;
}

function confirmValiderTous() {
    router.post(
        route("finances.validerTous"),
        {},
        {
            onSuccess: (page) => {
                showConfirmAll.value = false;
                filteredfinances.value = page.props.finances;
                if (
                    page.props &&
                    page.props.flash &&
                    page.props.flash.success
                ) {
                    showToast(page.props.flash.success, "success");
                }
                if (page.props && page.props.flash && page.props.flash.error) {
                    showToast(page.props.flash.error, "error");
                }
            },
        }
    );
}

function cancelValiderTous() {
    showConfirmAll.value = false;
}

function handleDepense() {
    router.get(route("finances.createDepense"));
}

function handleAjustement() {
    // À implémenter : modal ou route pour ajustement manuel
}
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
                <h1 class="text-3xl sm:text-4xl font-black text-neutral-900">
                    Caisse / Finances
                </h1>

                <FinanceStats
                    :solde-total="props.soldeTotal"
                    :total-attente="props.totalAttente"
                    :nb-attente="props.nbAttente"
                    :solde-cotisations="props.soldeCotisations"
                    :solde-depenses="props.soldeDepenses"
                />

                <div
                    class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 lg:gap-8"
                >
                    <FinanceCreateDepot />
                    <FinanceAdminActions
                        @valider="askValiderTous"
                        @depense="handleDepense"
                        @ajustement="handleAjustement"
                    />

                    <ConfirmModal
                        :show="showConfirmAll"
                        title="Valider tous les dépôts"
                        message="Voulez-vous valider tous les dépôts en attente ?"
                        @confirm="confirmValiderTous"
                        @cancel="cancelValiderTous"
                    />

                    <div
                        class="p-4 sm:p-6 rounded-lg bg-white border border-neutral-200 space-y-4"
                    >
                        <h2
                            class="text-lg sm:text-xl font-bold text-neutral-900"
                        >
                            Historique des transactions
                        </h2>
                        <FinanceFilter
                            :users="props.users"
                            @filter="handleFiltre"
                        />
                        <FinanceHistoriqueTable :finances="filteredfinances" />
                    </div>
                </div>
            </div>
        </div>
        <Toast
            v-model="toastVisible"
            :message="toastMessage"
            :type="toastType"
        />
    </AuthenticatedLayout>
</template>
