<script setup>
import { usePage } from "@inertiajs/vue3";


const emit = defineEmits(["valider", "depense", "ajustement"]);
const page = usePage();
const role = page.props.auth.user.role;


const isAdmin = role === "admin";
const isBureau = role === "bureau";


function validerDepots() {
    emit("valider", "cotisation");
}
function validerDepenses() {
    emit("valider", "dépense");
}
function ajouterDepense() {
    emit("depense");
}
function ajustementManuel() {
    emit("ajustement");
}
</script>


<template>
    <div
        class="flex flex-col gap-4 p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700 rounded-lg"
    >
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Actions Spéciales
        </h2>


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <!-- Valider dépôts : admin par exemple -->
            <button
                v-if="isAdmin"
                @click="validerDepots"
                type="button"
                class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg hover:bg-neutral-100 dark:hover:bg-slate-700 transition-colors"
            >
                <span class="material-symbols-outlined text-secondary"
                    >check_circle</span
                >
                <span
                    class="text-sm font-semibold text-neutral-800 dark:text-slate-200"
                    >Valider Dépôts</span
                >
            </button>


            <!-- Valider dépenses en attente : admin -->
            <button
                v-if="isAdmin"
                @click="validerDepenses"
                type="button"
                class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg hover:bg-neutral-100 dark:hover:bg-slate-700 transition-colors"
            >
                <span class="material-symbols-outlined text-secondary"
                    >checklist_rtl</span
                >
                <span
                    class="text-sm font-semibold text-neutral-800 dark:text-slate-200"
                    >Valider Dépenses</span
                >
            </button>


            <!-- Ajouter dépense : admin + bureau -->
            <button
                v-if="isAdmin || isBureau"
                @click="ajouterDepense"
                type="button"
                class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg hover:bg-neutral-100 dark:hover:bg-slate-700 transition-colors"
            >
                <span class="material-symbols-outlined text-danger"
                    >remove_circle</span
                >
                <span
                    class="text-sm font-semibold text-neutral-800 dark:text-slate-200"
                    >Ajouter Dépense</span
                >
            </button>


            <!-- Ajustement manuel : admin seulement -->
            <button
                v-if="isAdmin"
                @click="ajustementManuel"
                type="button"
                class="flex items-center justify-center gap-2 h-12 px-4 border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 rounded-lg hover:bg-neutral-100 dark:hover:bg-slate-700 transition-colors"
            >
                <span class="material-symbols-outlined text-primary">edit</span>
                <span
                    class="text-sm font-semibold text-neutral-800 dark:text-slate-200"
                    >Ajustement Manuel</span
                >
            </button>
        </div>
    </div>
</template>
