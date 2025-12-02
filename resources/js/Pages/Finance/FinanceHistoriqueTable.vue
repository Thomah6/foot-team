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
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200 dark:divide-slate-700">
            <thead class="bg-neutral-100 dark:bg-slate-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white">
                        Membre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white">
                        Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white">
                        Statut
                    </th>

                    <th
                        v-if="isAdmin"
                        class="px-6 py-3 text-left text-xs font-medium text-neutral-900 dark:text-white"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-slate-900 divide-y divide-neutral-200 dark:divide-slate-700">
                <tr v-for="finance in finances" :key="finance.id" class="dark:hover:bg-slate-800">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-slate-100">
                        {{ finance.date }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-neutral-900 dark:text-slate-100">
                        {{ finance.user_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-neutral-900 dark:text-slate-100">
                        {{ finance.description || "-" }}
                    </td>
                    <td
                        class="px-6 py-4 whitespace-nowrap text-sm font-semibold"
                        :class="montantClass(finance)"
                    >
                        {{ finance.montant > 0 ? "+" : ""
                        }}{{ finance.montant }}F CFA
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                            v-if="finance.statut_valide"
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 dark:bg-green-800 text-green-900 dark:text-green-100"
                            >Validé</span
                        >
                        <span
                            v-else
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-yellow-100 dark:bg-yellow-800 text-yellow-900 dark:text-yellow-100"
                            >En attente</span
                        >
                    </td>
                    <td
                        v-if="isAdmin"
                        class="px-6 py-4 whitespace-nowrap text-sm"
                    >
                        <button
                            v-if="!finance.statut_valide && (finance.type === 'cotisation' || finance.type === 'dépense')"
                            @click="askConfirm(finance)"
                            class="text-green-600 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100 font-semibold"
                        >
                            Valider
                        </button>
                        <span v-else class="text-neutral-400 dark:text-slate-500">-</span>
                    </td>
                </tr>
                <tr v-if="finances.length === 0">
                    <td
                        colspan="6"
                        class="px-6 py-4 text-center text-neutral-500 dark:text-slate-300"
                    >
                        Aucune transaction
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <div v-if="lastPage > 1" class="flex items-center justify-between mt-6">
            <!-- Info page -->
            <div class="text-sm text-gray-700 dark:text-slate-200">
                Page {{ currentPage }} sur {{ lastPage }}
            </div>

            <!-- Navigation -->
            <div class="flex items-center gap-2">
                <!-- Précédent -->
                <!-- <button
                    @click="previousPage"
                    :disabled="currentPage === 1"
                    class="px-3 py-2 text-sm border rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="
                        currentPage === 1
                            ? 'bg-gray-100 text-gray-500'
                            : 'bg-white hover:bg-gray-50'
                    "
                >
                    Précédent
                </button> -->

                <!-- Liens de pages (simplifié) -->
                <button
                    v-for="link in links"
                    :key="link.label"
                    @click="handleLink(link)"
                    class="px-3 py-2 text-sm border rounded"
                    :class="[
                        link.active
                            ? 'bg-blue-600 dark:bg-blue-700 text-white border-blue-600 dark:border-blue-700'
                            : !link.url
                            ? 'bg-gray-100 dark:bg-slate-700 text-gray-500 dark:text-slate-400 border-gray-300 dark:border-slate-600'
                            : 'bg-white dark:bg-slate-800 text-gray-700 dark:text-slate-200 hover:bg-gray-50 dark:hover:bg-slate-700 border-gray-300 dark:border-slate-600',
                    ]"
                    :disabled="!link.url"
                >
                    {{ link.label }}
                </button>

                <!-- Suivant -->
                <!-- <button
                    @click="nextPage"
                    :disabled="currentPage === lastPage"
                    class="px-3 py-2 text-sm border rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    :class="
                        currentPage === lastPage
                            ? 'bg-gray-100 text-gray-500'
                            : 'bg-white hover:bg-gray-50'
                    "
                >
                    Suivant
                </button> -->
            </div>
        </div>

        <!-- Modal -->
        <ConfirmModalFinance
            :show="showConfirm"
            :title="toValidateFinance ? (toValidateFinance.type === 'dépense' ? `Valider la dépense de ${toValidateFinance.user_name}` : `Valider le dépôt de ${toValidateFinance.user_name}`) : 'Valider'"
            :message="toValidateFinance ? (toValidateFinance.type === 'dépense' ? `Voulez-vous valider cette dépense de ${toValidateFinance.montant} F CFA ? Elle sera déduite du solde.` : `Voulez-vous valider ce dépôt de ${toValidateFinance.montant} F CFA ? Il sera ajouté au solde.`) : 'Voulez-vous continuer ?'"
            :loading="showConfirmLoading"
            @confirm="confirmValider"
            @cancel="cancelValider"
        />
    </div>
</template>
