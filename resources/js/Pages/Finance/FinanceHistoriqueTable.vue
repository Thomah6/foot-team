<script setup>
import { router, usePage } from "@inertiajs/vue3";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";
import { ref, computed } from "vue";

const props = defineProps({
    finances: {
        type: Array,
        default: () => [],
    },
    links: {
        type: Array,
        default: () => [],
    },
    currentPage: {
        type: Number,
        default: 1,
    },
    lastPage: {
        type: Number,
        default: 1,
    },
});

const page = usePage();
const filters = computed(() => page.props.filters || {});

const emit = defineEmits(["change-page", "refresh-table"]);

function goToPage(pageNumber) {
    // Emit the desired page number to the parent; parent will perform the Inertia visit.
    emit("change-page", pageNumber);
}

function previousPage() {
    if (props.currentPage > 1) {
        goToPage(props.currentPage - 1);
    }
}

function nextPage() {
    if (props.currentPage < props.lastPage) {
        goToPage(props.currentPage + 1);
    }
}

function handleLink(link) {
    if (!link.url || link.active) return;

    const url = new URL(link.url, window.location.origin);
    const pageNumber = url.searchParams.get("page");

    if (pageNumber) {
        goToPage(parseInt(pageNumber));
    }
}

const role = page.props.auth.user.role;
const isAdmin = role === "admin";
const isBureau = role === "bureau";
const isMember = role === "simple";

function montantClass(finance) {
    if (finance.type === "dépense") return "text-red-600 dark:text-red-300";
    if (finance.statut_valide) return "text-green-600 dark:text-green-300";
    return "text-yellow-600 dark:text-yellow-300";
}

const showConfirm = ref(false);
const showConfirmLoading = ref(false);
const toValidateFinance = ref(null);

function askConfirm(finance) {
    toValidateFinance.value = finance;
    showConfirm.value = true;
}

function confirmValider() {
    if (!toValidateFinance.value) return;
    showConfirmLoading.value = true;
    router.post(
        route("finances.valider", toValidateFinance.value.id),
        {},
        {
            onSuccess: () => {
                showConfirm.value = false;
                toValidateFinance.value = null;
                // Ask parent to refresh table so toast can display
                emit("refresh-table");
            },
            onFinish: () => {
                showConfirmLoading.value = false;
            },
            onError: () => {
                showConfirmLoading.value = false;
            },
        }
    );
}

function cancelValider() {
    showConfirm.value = false;
    toValidateFinance.value = null;
    showConfirmLoading.value = false;
}
</script>
<template>
    <div class="relative">
        <!-- Table Container -->
        <div class="relative overflow-hidden rounded-2xl border-2 border-lime-200 dark:border-emerald-800/50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm ">
            <!-- Header -->
            <div class="px-6 py-4 border-b border-lime-100 dark:border-emerald-900/30">
                <h3 class="text-lg font-black text-gray-900 dark:text-white">
                    HISTORIQUE DES TRANSACTIONS
                </h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Toutes les opérations financières du club
                </p>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="bg-gradient-to-r from-lime-50/50 to-emerald-50/50 dark:from-emerald-900/10 dark:to-lime-900/10">
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Date
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Membre
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Description
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Montant
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Statut
                            </th>
                            <th v-if="isAdmin" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider text-lime-700 dark:text-emerald-400 border-b border-lime-100 dark:border-emerald-900/30">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-lime-100 dark:divide-emerald-900/20">
                        <tr v-for="finance in finances" :key="finance.id" 
                            class="hover:bg-lime-50/30 dark:hover:bg-emerald-900/10 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-300">
                                {{ finance.date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-white text-xs font-bold">
                                        {{ finance.user_name?.charAt(0) || '?' }}
                                    </div>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ finance.user_name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-400 max-w-xs truncate">
                                {{ finance.description || "-" }}
                            </td>
                            <td :class="['px-6 py-4 whitespace-nowrap text-lg font-black', montantClass(finance)]">
                                <div class="flex items-center gap-2">
                                    <i :class="finance.type === 'dépense' ? 'fas fa-arrow-down' : 'fas fa-arrow-up'"></i>
                                    {{ finance.montant > 0 && finance.type === 'dépense' ? '-' : '+' }} {{ finance.montant }} F
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span :class="[
                                    'inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold',
                                    finance.statut_valide 
                                        ? 'bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-700 dark:text-green-400 border border-green-200 dark:border-green-800'
                                        : 'bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-700 dark:text-yellow-400 border border-yellow-200 dark:border-yellow-800'
                                ]">
                                    <i :class="finance.statut_valide ? 'fas fa-check-circle mr-1.5' : 'fas fa-clock mr-1.5'"></i>
                                    {{ finance.statut_valide ? 'Validé' : 'En attente' }}
                                </span>
                            </td>
                            <td v-if="isAdmin" class="px-6 py-4 whitespace-nowrap">
                                <button
                                    v-if="!finance.statut_valide && (finance.type === 'cotisation' || finance.type === 'dépense')"
                                    @click="askConfirm(finance)"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-emerald-500 to-lime-500 hover:from-emerald-600 hover:to-lime-600 text-white font-bold rounded-lg transition-all transform hover:scale-105 active:scale-95"
                                >
                                    <i class="fas fa-check"></i>
                                    Valider
                                </button>
                                <span v-else class="text-gray-400 dark:text-gray-600 text-sm">
                                    ✔️
                                </span>
                            </td>
                        </tr>
                        
                        <!-- Empty State -->
                        <tr v-if="finances.length === 0">
                            <td :colspan="isAdmin ? 6 : 5" class="px-6 py-12 text-center">
                                <div class="mx-auto max-w-sm">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gradient-to-br from-lime-100 to-emerald-100 dark:from-emerald-900/20 dark:to-lime-900/20 flex items-center justify-center">
                                        <i class="fas fa-coins text-lime-600 dark:text-emerald-400 text-2xl"></i>
                                    </div>
                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                        Aucune transaction
                                    </h4>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                                        Les transactions apparaîtront ici
                                    </p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="lastPage > 1" class="px-6 py-4 border-t border-lime-100 dark:border-emerald-900/30 bg-gradient-to-r from-lime-50/30 to-emerald-50/30 dark:from-emerald-900/5 dark:to-lime-900/5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="text-sm font-medium text-lime-700 dark:text-emerald-400">
                        Page {{ currentPage }} sur {{ lastPage }}
                    </div>
                    
                    <div class="flex items-center gap-2 flex-wrap justify-center">
                        <button
                            v-for="link in links"
                            :key="link.label"
                            @click="handleLink(link)"
                            :disabled="!link.url"
                            :class="[
                                'px-4 py-2 rounded-lg font-bold transition-all min-w-[40px]',
                                link.active
                                    ? 'bg-gradient-to-r from-lime-500 to-emerald-500 text-white shadow-md'
                                    : !link.url
                                    ? 'bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-600 cursor-not-allowed'
                                    : 'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 hover:bg-lime-50 dark:hover:bg-emerald-900/20 hover:text-lime-700 dark:hover:text-emerald-400 border border-lime-200 dark:border-emerald-800/50'
                            ]"
                        >
                            {{ link.label }}
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <ConfirmModalFinance
            :show="showConfirm"
            :title="toValidateFinance ? (toValidateFinance.type === 'dépense' ? `💸 VALIDER CETTE DÉPENSE` : `📥 VALIDER CE DÉPÔT`) : 'Confirmation'"
            :message="toValidateFinance ? (toValidateFinance.type === 'dépense' ? `Valider la dépense de ${toValidateFinance.montant} FCFA par ${toValidateFinance.user_name} ?` : `Valider le dépôt de ${toValidateFinance.montant} FCFA par ${toValidateFinance.user_name} ?`) : 'Voulez-vous continuer ?'"
            :loading="showConfirmLoading"
            @confirm="confirmValider"
            @cancel="cancelValider"
        />
    </div>
</template>

<style scoped>
/* Custom scrollbar for table */
.overflow-x-auto {
    scrollbar-width: thin;
    scrollbar-color: theme('colors.emerald.500') transparent;
}

.overflow-x-auto::-webkit-scrollbar {
    height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
    background: transparent;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
    background: linear-gradient(to right, theme('colors.lime.500'), theme('colors.emerald.500'));
    border-radius: 3px;
}

/* Mobile optimizations */
@media (max-width: 640px) {
    .px-6 {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .py-4 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
    
    table {
        font-size: 0.875rem;
    }
}
</style>
