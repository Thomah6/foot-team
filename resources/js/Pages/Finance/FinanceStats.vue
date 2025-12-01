<script setup>
import { computed } from "vue";

const props = defineProps({
    soldeTotal: Number,
    totalAttente: Number,
    nbAttente: Number,
    soldeCotisations: Number,
    soldeDepenses: Number,
    pendingDepensesTotal: { type: Number, default: 0 },
    pendingDepensesCount: { type: Number, default: 0 },
});
</script>
<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 sm:gap-6">
        <div
            class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700"
        >
            <p
                class="text-neutral-800 dark:text-slate-300 text-base font-medium"
            >
                Solde Total Actuel
            </p>
            <p
                :class="
                    Number(props.soldeTotal ?? 0) < 0
                        ? 'text-red-600 dark:text-red-300'
                        : Number(props.soldeTotal ?? 0) > 0
                        ? 'text-green-600 dark:text-green-300'
                        : 'text-neutral-900 dark:text-white'
                "
                class="text-3xl font-bold"
            >
                {{
                    Number(props.soldeTotal ?? 0).toLocaleString("fr-FR", {
                        minimumFractionDigits: 2,
                    })
                }}F CFA
            </p>
        </div>

        <div
            class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700"
        >
            <p
                class="text-neutral-800 dark:text-slate-300 text-base font-medium"
            >
                Total des Cotisations (validées)
            </p>
            <p
                class="text-green-600 dark:text-green-300 text-2xl sm:text-3xl font-bold"
            >
                {{
                    Number(props.soldeCotisations ?? 0).toLocaleString(
                        "fr-FR",
                        { minimumFractionDigits: 2 }
                    )
                }}F CFA
            </p>
        </div>

        <div
            class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700"
        >
            <p
                class="text-neutral-800 dark:text-slate-300 text-base font-medium"
            >
                Total des Dépenses (validées)
            </p>
            <p
                class="text-red-600 dark:text-red-300 text-2xl sm:text-3xl font-bold"
            >
                {{
                    Number(props.soldeDepenses ?? 0).toLocaleString("fr-FR", {
                        minimumFractionDigits: 2,
                    })
                }}F CFA
            </p>
        </div>

        <div
            class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700"
        >
            <p
                class="text-neutral-800 dark:text-slate-300 text-base font-medium"
            >
                Total des Dépôts en Attente
            </p>
            <p
                class="text-yellow-600 dark:text-yellow-300 text-2xl sm:text-3xl font-bold"
            >
                {{
                    Number(props.totalAttente ?? 0).toLocaleString("fr-FR", {
                        minimumFractionDigits: 2,
                    })
                }}F CFA
            </p>
            <span
                class="text-sm text-neutral-800 dark:text-slate-400 font-medium"
                >{{ props.nbAttente ?? 0 }} transaction(s)</span
            >
        </div>

        <div
            class="flex flex-col gap-2 rounded-lg p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700"
        >
            <p
                class="text-neutral-800 dark:text-slate-300 text-base font-medium"
            >
                Dépenses en Attente (admin)
            </p>
            <p
                class="text-yellow-600 dark:text-yellow-300 text-2xl sm:text-3xl font-bold"
            >
                {{
                    Number(props.pendingDepensesTotal ?? 0).toLocaleString(
                        "fr-FR",
                        { minimumFractionDigits: 2 }
                    )
                }}F CFA
            </p>
            <span
                class="text-sm text-neutral-800 dark:text-slate-400 font-medium"
                >{{ props.pendingDepensesCount ?? 0 }} transaction(s)</span
            >
        </div>
    </div>
</template>
