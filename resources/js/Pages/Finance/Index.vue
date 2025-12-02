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
        <div class="min-h-screen bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="relative px-4 py-6 sm:px-6 lg:px-8">
                <!-- Background Pattern -->
                <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iIzExMTExMSIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjMTExMTExIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')] opacity-20 dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')]"></div>
                
                <!-- Header -->
                <div class="relative mb-8">
                    <h1 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-2">
                         TRÉSORERIE
                    </h1>
                    <p class="text-lg text-gray-600 dark:text-gray-400">
                        Gestion financière - <span class="text-lime-600 dark:text-emerald-400 font-bold">La force du collectif</span>
                    </p>
                </div>

                <!-- Stats Section -->
                <div class="mb-8">
                    <FinanceStats
                        :solde-total="Number(props.soldeTotal)"
                        :total-attente="Number(props.totalAttente)"
                        :nb-attente="Number(props.nbAttente)"
                        :solde-cotisations="Number(props.soldeCotisations)"
                        :solde-depenses="Number(props.soldeDepenses)"
                        :pending-depenses-total="Number(props.pendingDepensesTotal)"
                        :pending-depenses-count="Number(props.pendingDepensesCount)"
                    />
                </div>

                <!-- Action Cards Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                    <FinanceCreateDepot @refresh-table="refreshTable" />
                    <FinanceAdminActions
                        v-if="isAdmin || isBureau"
                        @valider="askValiderTous"
                        @depense="handleDepense"
                        @ajustement="handleAjustement"
                    />
                </div>

                <!-- Modal for Bulk Validation -->
                <ConfirmModalFinance
                    :show="showConfirmAll"
                    :loading="showConfirmAllLoading"
                    :title="showConfirmAllType === 'dépense' ? '💸 VALIDER TOUTES LES DÉPENSES' : '📥 VALIDER TOUS LES DÉPÔTS'"
                    :message="showConfirmAllType === 'dépense' ? 'Valider toutes les dépenses en attente simultanément ?' : 'Valider tous les dépôts en attente simultanément ?'"
                    @confirm="confirmValiderTous"
                    @cancel="cancelValiderTous"
                />

                <!-- Transactions History -->
                <div class="mb-8">
                    <FinanceFilter
                        :users="props.users"
                        @filter="handleFiltre"
                    />
                </div>

                <div class="mb-12">
                    <FinanceHistoriqueTable
                        :finances="financesData"
                        :links="links"
                        :current-page="currentPage"
                        :last-page="lastPage"
                        @change-page="onChangePage"
                        @refresh-table="refreshTable"
                    />
                </div>

                <!-- Quote -->
                <div class="max-w-2xl mx-auto">
                    <div class="p-6 rounded-2xl bg-gradient-to-r from-lime-50/50 to-emerald-50/50 dark:from-emerald-900/10 dark:to-lime-900/10 border-2 border-lime-200/50 dark:border-emerald-800/30">
                        <p class="text-center text-gray-700 dark:text-gray-300 italic">
                            "La force financière d'un club se mesure à la rigueur de sa gestion, 
                            pas à la taille de son trésor."
                        </p>
                        <p class="text-center text-sm font-bold text-lime-700 dark:text-emerald-400 mt-2">
                            — Philosophie Dynamo Finance
                        </p>
                    </div>
                </div>
            </div>

            <!-- Toast Notification -->
            <Toast
                v-model="toastVisible"
                :message="toastMessage"
                :type="toastType"
            />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Mobile optimizations */
@media (max-width: 640px) {
    .px-4 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-6 {
        padding-top: 1.5rem;
        padding-bottom: 1.5rem;
    }
    
    .text-3xl {
        font-size: 2rem;
    }
}
</style>