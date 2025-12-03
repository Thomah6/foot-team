<script setup>
import FinanceStats from "@/Pages/Finance/FinanceStats.vue";
import FinanceCreateDepot from "@/Pages/Finance/FinanceCreateDepot.vue";
import FinanceAdminActions from "@/Pages/Finance/FinanceAdminActions.vue";
import FinanceFilter from "@/Pages/Finance/FinanceFilter.vue";
import FinanceHistoriqueTable from "@/Pages/Finance/FinanceHistoriqueTable.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Toast from "@/Shared/Toast.vue";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";

import { ref, watch, onMounted, reactive } from "vue";
import { router, usePage } from "@inertiajs/vue3";
import { debounce } from 'lodash';

// Props reçues du backend Laravel
const props = defineProps({
    finances: Object,
    users: Array,
    filters: Object,
    soldeCotisations: [Number, String],
    soldeDepenses: [Number, String],
    soldeTotal: [Number, String],
    totalAttente: [Number, String],
    nbAttente: [Number, String],
    pendingDepensesTotal: [Number, String],
    pendingDepensesCount: [Number, String],
});

// Reactive filters object for live filtering
const filters = reactive({
    member_id: props.filters.member_id || "",
    date_from: props.filters.date_from || "",
    date_to: props.filters.date_to || "",
    type: props.filters.type || "",
});

// Debounced function to fetch data from the server
const debouncedSearch = debounce(() => {
    router.get(route("finances.index"), filters, {
        preserveState: true,
        preserveScroll: true,
        replace: true,
    });
}, 300);

// Watch for changes in filters and trigger the debounced search
watch(filters, debouncedSearch);


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
    router.get(
        route("finances.index"),
        {
            ...filters,
            page: pageNumber,
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
}

function refreshTable() {
    router.get(
        route("finances.index"),
        {
            ...filters,
            page: props.finances.current_page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
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

// Handle filter changes from the child component
function onFilterChange(payload) {
    filters.member_id = payload.selectedUser;
    filters.date_from = payload.dateFrom;
    filters.date_to = payload.dateTo;
    filters.type = payload.selectedType;
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
                        💰 TRÉSORERIE FC DYNAMO
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
                        :filters="filters"
                        @filter-change="onFilterChange"
                    />
                </div>

                <div class="mb-12">
                    <FinanceHistoriqueTable
                        :finances="props.finances.data"
                        :links="props.finances.links"
                        :current-page="props.finances.current_page"
                        :last-page="props.finances.last_page"
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