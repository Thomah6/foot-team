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
    if (finance.type === "dépense") return "text-red-600";
    if (finance.statut_valide) return "text-green-600";
    return "text-yellow-500";
}

const showConfirm = ref(false);
const toValidateId = ref(null);

function askConfirm(id) {
    toValidateId.value = id;
    showConfirm.value = true;
}

function confirmValider() {
    if (!toValidateId.value) return;
    router.post(
        route("finances.valider", toValidateId.value),
        {},
        {
            onSuccess: () => {
                showConfirm.value = false;
                toValidateId.value = null;
                // Ask parent to refresh table so toast can display
                emit("refresh-table");
            },
        }
    );
}
function cancelValider() {
    showConfirm.value = false;
    toValidateId.value = null;
}
</script>
<template>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-neutral-200">
            <thead class="bg-neutral-100">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium">
                        Date
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium">
                        Membre
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium">
                        Description
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium">
                        Montant
                    </th>
                    <th class="px-6 py-3 text-left text-xs font-medium">
                        Statut
                    </th>

                    <th
                        v-if="isAdmin"
                        class="px-6 py-3 text-left text-xs font-medium"
                    >
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-neutral-200">
                <tr v-for="finance in finances" :key="finance.id">
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        {{ finance.date }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        {{ finance.user_name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
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
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800"
                            >Validé</span
                        >
                        <span
                            v-else
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800"
                            >En attente</span
                        >
                    </td>
                    <td
                        v-if="isAdmin"
                        class="px-6 py-4 whitespace-nowrap text-sm"
                    >
                        <button
                            v-if="
                                !finance.statut_valide &&
                                finance.type === 'cotisation'
                            "
                            @click="askConfirm(finance.id)"
                            class="text-green-600 hover:text-green-900 font-semibold"
                        >
                            Valider
                        </button>
                        <span v-else class="text-neutral-400">-</span>
                    </td>
                </tr>
                <tr v-if="finances.length === 0">
                    <td
                        colspan="6"
                        class="px-6 py-4 text-center text-neutral-500"
                    >
                        Aucune transaction
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- Pagination -->
        <div v-if="lastPage > 1" class="flex items-center justify-between mt-6">
            <!-- Info page -->
            <div class="text-sm text-gray-700">
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
                            ? 'bg-blue-600 text-white border-blue-600'
                            : !link.url
                            ? 'bg-gray-100 text-gray-500'
                            : 'bg-white text-gray-700 hover:bg-gray-50',
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
            title="Valider le dépôt"
            message="Voulez-vous valider ce dépôt ?"
            @confirm="confirmValider"
            @cancel="cancelValider"
        />
    </div>
</template>
