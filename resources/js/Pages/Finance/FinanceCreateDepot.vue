<script setup>
import { ref, defineEmits, onMounted } from "vue";
import { router } from "@inertiajs/vue3";
import ConfirmModalFinance from "@/Components/ConfirmModalFinance.vue";

const emit = defineEmits(["refresh-table"]);

const montant = ref(100);
const description = ref("");
const showConfirmModal = ref(false);
const showConfirmModalLoading = ref(false);
const isAnimating = ref(false);
const MIN_MONTANT = 100;
const MAX_MONTANT = 10000;
const ANIMATION_DURATION = 2500; //

function askConfirmation() {
    showConfirmModal.value = true;
}

function confirmDepot() {
    showConfirmModalLoading.value = true;

    const data = {
        montant: montant.value,
        description: description.value,
    };

    router.post(route("finances.storeDepot"), data, {
        onSuccess: () => {
            montant.value = 100;
            description.value = "";
            emit("refresh-table");
            showConfirmModal.value = false;
        },
        onFinish: () => {
            showConfirmModalLoading.value = false;
        },
        onError: () => {
            showConfirmModalLoading.value = false;
            showConfirmModal.value = false;
        },
    });
}

function cancelDepot() {
    showConfirmModal.value = false;
}

function animateMontantDecrement() {
    if (isAnimating.value) return;
    isAnimating.value = true;

    const start = MAX_MONTANT;
    const end = MIN_MONTANT;
    const duration = ANIMATION_DURATION;
    const startTime = performance.now();

    function step(now) {
        const elapsed = now - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.round((start + (end - start) * eased) / 100) * 100;
        montant.value = Math.max(end, Math.min(start, value));

        if (progress < 1 && isAnimating.value) {
            requestAnimationFrame(step);
        } else {
            montant.value = end;
            isAnimating.value = false;
        }
    }

    montant.value = MAX_MONTANT;
    requestAnimationFrame(step);
}

function onUserInteract() {
    isAnimating.value = false;
}

onMounted(() => {
    setTimeout(() => {
        animateMontantDecrement();
    }, 100);
});
</script>

<template>
    <div class="deposit-animate relative overflow-hidden rounded-2xl p-6 border-2 border-lime-200 dark:border-emerald-800/50 bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm shadow-xl">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-lime-400/10 to-emerald-600/10 rounded-full -translate-y-16 translate-x-16"></div>
        
        <h2 class="text-xl font-black text-gray-900 dark:text-white mb-2">
            📥 FAIRE UN DÉPÔT
        </h2>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
            Investis dans l'avenir du club. Chaque franc compte !
        </p>
        
        <div class="space-y-6">
            <!-- Amount Display -->
            <div class="text-center">
                <div class="text-5xl md:text-6xl font-black bg-gradient-to-r from-emerald-700 to-lime-600 dark:from-lime-400 dark:to-emerald-500 bg-clip-text text-transparent">
                    {{ montant }} F
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">FCFA</div>
            </div>

            <!-- Range Slider -->
            <div class="space-y-3">
                <input
                    type="range"
                    min="100"
                    max="10000"
                    step="100"
                    v-model.number="montant"
                    @input="onUserInteract"
                    class="w-full h-3 bg-gradient-to-r from-lime-200 to-emerald-200 dark:from-emerald-900/30 dark:to-lime-900/30 rounded-full appearance-none [&::-webkit-slider-thumb]:appearance-none [&::-webkit-slider-thumb]:h-6 [&::-webkit-slider-thumb]:w-6 [&::-webkit-slider-thumb]:rounded-full [&::-webkit-slider-thumb]:bg-gradient-to-r [&::-webkit-slider-thumb]:from-lime-500 [&::-webkit-slider-thumb]:to-emerald-600 [&::-webkit-slider-thumb]:border-2 [&::-webkit-slider-thumb]:border-white [&::-webkit-slider-thumb]:dark:border-gray-900 [&::-webkit-slider-thumb]:shadow-lg"
                />
                <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400">
                    <span>Min: 100 F</span>
                    <span class="font-bold text-lime-600 dark:text-emerald-400">ÉCHELLE</span>
                    <span>Max: 10 000 F</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <input
                    v-model="description"
                    type="text"
                    placeholder="Motivation de ton investissement (optionnel)"
                    class="w-full px-4 py-3 bg-gradient-to-r from-white to-lime-50/50 dark:from-gray-800 dark:to-emerald-900/20 border-2 border-lime-200 dark:border-emerald-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-lime-400 dark:focus:border-emerald-500 focus:ring-2 focus:ring-lime-200 dark:focus:ring-emerald-900/30 outline-none transition-all"
                />
            </div>

            <!-- Submit Button -->
            <button
                @click="askConfirmation"
                class="group relative w-full py-4 px-6 bg-gradient-to-r from-emerald-600 to-lime-600 dark:from-emerald-500 dark:to-lime-500 text-white font-black rounded-xl shadow-lg hover:shadow-emerald-500/30 transform hover:scale-[1.02] active:scale-95 transition-all duration-300 overflow-hidden"
            >
                <span class="relative z-10 flex items-center justify-center gap-3">
                    <i class="fas fa-paper-plane text-lg group-hover:rotate-12 transition-transform"></i>
                    VALIDER LE DÉPÔT
                </span>
                <div class="absolute inset-0 bg-gradient-to-r from-emerald-700 to-lime-700 dark:from-emerald-600 dark:to-lime-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
            </button>
        </div>

        <ConfirmModalFinance
            :show="showConfirmModal"
            :loading="showConfirmModalLoading"
            title="🏦 CONFIRMER L'INVESTISSEMENT"
            :message="`Tu investis ${montant} FCFA pour faire avancer le club ?`"
            @confirm="confirmDepot"
            @cancel="cancelDepot"
        />
    </div>
</template>

<style scoped>
@keyframes floatDown {
    from {
        opacity: 0;
        transform: translateY(-24px) scale(0.995);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}
.deposit-animate {
    animation: floatDown 420ms cubic-bezier(0.22, 1, 0.36, 1) forwards;
}

/* Touch optimization */
@media (max-width: 640px) {
    button {
        min-height: 52px;
    }
    
    input[type="range"] {
        min-height: 44px;
    }
}
</style>

