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
    <div class="relative overflow-hidden rounded-2xl bg-slate-50 dark:bg-gray-900/95 backdrop-blur-sm border-2 border-lime-300 dark:border-emerald-800/60 p-6 shadow-xl">
        <!-- Header -->
        <div class="mb-6">
            <h2 class="text-xl font-black text-slate-950 dark:text-white flex items-center gap-3">
                <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-lime-400 to-emerald-600 flex items-center justify-center">
                    <i class="fas fa-tools text-white"></i>
                </div>
                 ACTIONS ADMINISTRATIVES
            </h2>
            <p class="text-sm text-slate-700 dark:text-gray-400 mt-2">
                Outils de gestion financière avancés
            </p>
        </div>

        <!-- Actions Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <!-- Valider Dépôts -->
            <button
                v-if="isAdmin"
                @click="validerDepots"
                class="group relative flex items-center gap-4 p-4 bg-white dark:from-gray-800 dark:to-emerald-900/20 border-2 border-green-300 dark:border-emerald-800/60 rounded-xl hover:border-green-500 dark:hover:border-emerald-500 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
            >
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center">
                    <i class="fas fa-check-circle text-white text-lg"></i>
                </div>
                <div class="text-left">
                    <div class="font-bold text-slate-950 dark:text-white">Valider Dépôts</div>
                    <div class="text-xs text-slate-700 dark:text-gray-400">Approuver les cotisations</div>
                </div>
                <i class="fas fa-arrow-right text-green-700 dark:text-emerald-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
                <div class="absolute inset-0 bg-gradient-to-r from-green-500/5 to-emerald-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>

            <!-- Valider Dépenses -->
            <button
                v-if="isAdmin"
                @click="validerDepenses"
                class="group relative flex items-center gap-4 p-4 bg-white dark:from-gray-800 dark:to-red-900/20 border-2 border-red-300 dark:border-red-800/60 rounded-xl hover:border-red-500 dark:hover:border-red-500 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
            >
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-red-400 to-pink-500 flex items-center justify-center">
                    <i class="fas fa-clipboard-check text-white text-lg"></i>
                </div>
                <div class="text-left">
                    <div class="font-bold text-slate-950 dark:text-white">Valider Dépenses</div>
                    <div class="text-xs text-slate-700 dark:text-gray-400">Approuver les sorties</div>
                </div>
                <i class="fas fa-arrow-right text-red-700 dark:text-red-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
                <div class="absolute inset-0 bg-gradient-to-r from-red-500/5 to-pink-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>

            <!-- Ajouter Dépense -->
            <button
                v-if="isAdmin || isBureau"
                @click="ajouterDepense"
                class="group relative flex items-center gap-4 p-4 bg-white dark:from-gray-800 dark:to-orange-900/20 border-2 border-orange-300 dark:border-orange-800/60 rounded-xl hover:border-orange-500 dark:hover:border-orange-500 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
            >
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center">
                    <i class="fas fa-minus-circle text-white text-lg"></i>
                </div>
                <div class="text-left">
                    <div class="font-bold text-slate-950 dark:text-white">Ajouter Dépense</div>
                    <div class="text-xs text-slate-700 dark:text-gray-400">Nouvelle sortie d'argent</div>
                </div>
                <i class="fas fa-arrow-right text-orange-700 dark:text-orange-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-500/5 to-red-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>

            <!-- Ajustement Manuel -->
            <button
                v-if="isAdmin"
                @click="ajustementManuel"
                class="group relative flex items-center gap-4 p-4 bg-white dark:from-gray-800 dark:to-purple-900/20 border-2 border-purple-300 dark:border-purple-800/60 rounded-xl hover:border-purple-500 dark:hover:border-purple-500 hover:scale-[1.02] transition-all duration-300 overflow-hidden"
            >
                <div class="flex-shrink-0 w-12 h-12 rounded-lg bg-gradient-to-br from-purple-400 to-blue-500 flex items-center justify-center">
                    <i class="fas fa-edit text-white text-lg"></i>
                </div>
                <div class="text-left">
                    <div class="font-bold text-slate-950 dark:text-white">Ajustement Manuel</div>
                    <div class="text-xs text-slate-700 dark:text-gray-400">Correction de trésorerie</div>
                </div>
                <i class="fas fa-arrow-right text-purple-700 dark:text-purple-400 ml-auto group-hover:translate-x-1 transition-transform"></i>
                <div class="absolute inset-0 bg-gradient-to-r from-purple-500/5 to-blue-600/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
            </button>
        </div>

        <!-- Admin Badge -->
        <div class="mt-6 pt-4 border-t border-lime-200 dark:border-emerald-900/30">
            <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-lime-100 dark:from-emerald-900/30 dark:to-lime-900/30">
                <i class="fas fa-shield-alt text-lime-700 dark:text-emerald-400 text-xs"></i>
                <span class="text-xs font-bold text-lime-800 dark:text-emerald-400">
                    ACCÈS RESTREINT 
                </span>
            </div>
        </div>
    </div>
</template>


<style scoped>
/* Touch optimization */
@media (max-width: 640px) {
    .grid-cols-1 {
        gap: 0.75rem;
    }
    
    .p-4 {
        padding: 1rem;
    }
    
    .gap-4 {
        gap: 1rem;
    }
    
    button {
        min-height: 70px;
    }
}
</style>