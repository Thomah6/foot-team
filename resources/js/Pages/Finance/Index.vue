<script setup>
import FinanceStats from "@/Pages/Finance/FinanceStats.vue";
import FinanceCreateDepot from "@/Pages/Finance/FinanceCreateDepot.vue";
import FinanceAdminActions from "@/Pages/Finance/FinanceAdminActions.vue";
import FinanceFilter from "@/Pages/Finance/FinanceFilter.vue";
import FinanceHistoriqueTable from "@/Pages/Finance/FinanceHistoriqueTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Toast from "@/Shared/Toast.vue";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";

import { ref, watch, onMounted } from "vue";
import { router, usePage } from "@inertiajs/vue3";

// Props reçues du backend Laravel
const props = defineProps({
    finances: Object,
    users: Array,
    filters: {
        type: Object,
        default: () => ({
            member_id: "",
            date_from: "",
            date_to: "",
            type: "",
        }),
    },
    soldeCotisations: [Number, String],
    soldeDepenses: [Number, String],
    soldeTotal: [Number, String],
    totalAttente: [Number, String],
    nbAttente: [Number, String],
    pendingDepensesTotal: [Number, String],
    pendingDepensesCount: [Number, String],
});

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

const financesData = ref(props.finances.data || []);
const links = ref(props.finances.links || []);
const currentPage = ref(props.finances.current_page || 1);
const lastPage = ref(props.finances.last_page || 1);

function handleFiltre({ selectedUser, dateFrom, dateTo, selectedType }) {
    router.get(
        route("finances.index"),
        {
            member_id: selectedUser || "",
            date_from: dateFrom || "",
            date_to: dateTo || "",
            type: selectedType || "",
            page: 1,
        },
        {
            preserveState: false,
            preserveScroll: true,
            onSuccess: (page) => {
                financesData.value = page.props.finances.data || [];
                links.value = page.props.finances.links || [];
                currentPage.value = page.props.finances.current_page || 1;
                lastPage.value = page.props.finances.last_page || 1;
            },
        }
    );
}

const showConfirmAll = ref(false);
const showConfirmAllType = ref("cotisation");
const showConfirmAllLoading = ref(false);

function askValiderTous(type = "cotisation") {
    showConfirmAllType.value = type;
    showConfirmAll.value = true;
}

function confirmValiderTous() {
    showConfirmAllLoading.value = true;
    router.post(
        route("finances.validerTous"),
        { type: showConfirmAllType.value },
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
                refreshTable();
            },
            onFinish: () => {
                showConfirmAllLoading.value = false;
            },
            onError: () => {
                showConfirmAllLoading.value = false;
            },
        }
    );
}

function cancelValiderTous() {
    showConfirmAll.value = false;
}

function onChangePage(pageNumber) {
    const currentFilters = page.props.filters || props.filters || {};

    router.get(
        route("finances.index"),
        {
            ...currentFilters,
            page: pageNumber,
        },
        {
            preserveState: false,
            preserveScroll: true,
            onSuccess: (page) => {
                financesData.value = page.props.finances.data || [];
                links.value = page.props.finances.links || [];
                currentPage.value = page.props.finances.current_page || 1;
                lastPage.value = page.props.finances.last_page || 1;
            },
        }
    );
}

function refreshTable() {
    const currentFilters = page.props.filters || props.filters || {};

    router.get(
        route("finances.index"),
        {
            ...currentFilters,
            page: currentPage.value,
        },
        {
            preserveState: false,
            preserveScroll: true,
            onSuccess: (page) => {
                financesData.value = page.props.finances.data || [];
                links.value = page.props.finances.links || [];
                currentPage.value = page.props.finances.current_page || 1;
                lastPage.value = page.props.finances.last_page || 1;
                // Display flash messages if present
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

function handleDepense() {
    router.get(route("finances.createDepense"));
}

function handleAjustement() {
    router.get(route("finances.createAjustement"));
}

const role = page.props.auth.user.role;
const isAdmin = role === "admin";
const isBureau = role === "bureau";
</script>

<template>
    <AuthenticatedLayout>
        <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
            <div class="max-w-7xl mx-auto space-y-6 sm:space-y-8">
                <h1
                    class="text-3xl sm:text-4xl font-black text-neutral-900 dark:text-white"
                >
                    Caisse / Finances
                </h1>

                <FinanceStats
                    :solde-total="Number(props.soldeTotal)"
                    :total-attente="Number(props.totalAttente)"
                    :nb-attente="Number(props.nbAttente)"
                    :solde-cotisations="Number(props.soldeCotisations)"
                    :solde-depenses="Number(props.soldeDepenses)"
                    :pending-depenses-total="Number(props.pendingDepensesTotal)"
                    :pending-depenses-count="Number(props.pendingDepensesCount)"
                />

                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6 lg:gap-8"
                >
                    <FinanceCreateDepot @refresh-table="refreshTable" />
                    <FinanceAdminActions
                        v-if="isAdmin || isBureau"
                        @valider="askValiderTous"
                        @depense="handleDepense"
                        @ajustement="handleAjustement"
                    />
                </div>

                <ConfirmModalFinance
                    :show="showConfirmAll"
                    :loading="showConfirmAllLoading"
                    :title="
                        showConfirmAllType === 'dépense'
                            ? 'Valider toutes les dépenses'
                            : 'Valider tous les dépôts'
                    "
                    :message="
                        showConfirmAllType === 'dépense'
                            ? 'Voulez-vous valider toutes les dépenses en attente ?'
                            : 'Voulez-vous valider tous les dépôts en attente ?'
                    "
                    @confirm="confirmValiderTous"
                    @cancel="cancelValiderTous"
                />

                <div
                    class="p-4 sm:p-6 rounded-lg bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700 space-y-4"
                >
                    <h2
                        class="text-lg sm:text-xl font-bold text-neutral-900 dark:text-white"
                    >
                        Historique des transactions
                    </h2>
                    <FinanceFilter
                        :users="props.users"
                        @filter="handleFiltre"
                    />

                    <FinanceHistoriqueTable
                        :finances="financesData"
                        :links="links"
                        :current-page="currentPage"
                        :last-page="lastPage"
                        @change-page="onChangePage"
                        @refresh-table="refreshTable"
                    />
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
