<script setup>
import { ref, defineEmits, onMounted, computed } from "vue";
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

const rangeStyle = computed(() => {
    const min = MIN_MONTANT;
    const max = MAX_MONTANT;
    const val = Math.min(Math.max(Number(montant.value || min), min), max);
    const percent = Math.round(((val - min) / (max - min)) * 100);
    // orange when below half, green otherwise
    const mid = min + (max - min) / 2;
    const fillColor =
        val < mid ? "#f97316" /* orange-500 */ : "#16a34a"; /* emerald-600 */
    const emptyColor = "rgba(203,213,225,0.35)"; /* slate/neutral light */
    const thumbGradient =
        val < mid
            ? `linear-gradient(180deg,#ffedd5,#fb923c)`
            : `linear-gradient(180deg,#bbf7d0,#16a34a)`;

    return {
        background: `linear-gradient(to right, ${fillColor} 0%, ${fillColor} ${percent}%, ${emptyColor} ${percent}%, ${emptyColor} 100%)`,
        "--thumb-bg": thumbGradient,
        "--track-empty": emptyColor,
    };
});

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
    <div class="deposit-animate relative overflow-hidden rounded-2xl p-6 border-2 border-lime-300 dark:border-emerald-800/60 bg-slate-50 dark:bg-gray-900/95 backdrop-blur-sm shadow-xl">
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-lime-400/10 to-emerald-600/10 rounded-full -translate-y-16 translate-x-16"></div>
        
        <h2 class="text-xl font-black text-slate-900 dark:text-white mb-2">
             FAIRE UN DÉPÔT
        </h2>
        <p class="text-sm text-slate-700 dark:text-gray-400 mb-6">
            Investis dans l'avenir du club. Chaque franc compte !
        </p>
        
        <div class="space-y-6">
            <!-- Amount Display -->
            <div class="text-center">
                <div class="text-5xl md:text-6xl font-black bg-gradient-to-r from-emerald-800 to-lime-700 dark:from-lime-400 dark:to-emerald-500 bg-clip-text text-transparent">
                    {{ montant }} F
                </div>
                <div class="text-sm text-slate-700 dark:text-gray-400 mt-1">FCFA</div>
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
                    :style="rangeStyle"
                    class="w-full h-3 rounded-full appearance-none range-custom-thumb"
                />
                <div class="flex justify-between text-xs text-slate-700 dark:text-gray-400">
                    <span>Min: 100 F</span>
                    <span class="font-bold text-emerald-700 dark:text-emerald-400">ÉCHELLE</span>
                    <span>Max: 10 000 F</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <input
                    v-model="description"
                    type="text"
                        placeholder="Motivation de ton investissement (optionnel)"
                        maxlength="150"
                    class="w-full px-4 py-3 bg-white dark:from-gray-800 dark:to-emerald-900/20 border-2 border-lime-300 dark:border-emerald-800/60 rounded-xl text-slate-900 dark:text-white placeholder-slate-500 dark:placeholder-gray-500 focus:border-emerald-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100 dark:focus:ring-emerald-900/30 outline-none transition-all"
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

/* Custom range thumb + track styles */
.range-custom-thumb {
    -webkit-appearance: none;
    appearance: none;
    background: transparent; /* background set inline via style binding */
}
.range-custom-thumb:focus {
    outline: none;
}
.range-custom-thumb::-webkit-slider-runnable-track {
    height: 12px;
    border-radius: 9999px;
}
.range-custom-thumb::-webkit-slider-thumb {
    -webkit-appearance: none;
    width: 28px;
    height: 28px;
    border-radius: 9999px;
    margin-top: -8px; /* center the thumb relative to track height */
    background: var(--thumb-bg, linear-gradient(180deg, #bbf7d0, #16a34a));
    border: 3px solid #fff;
    box-shadow: 0 6px 18px rgba(16, 24, 40, 0.18);
}
.range-custom-thumb::-moz-range-track {
    height: 12px;
    border-radius: 9999px;
}
.range-custom-thumb::-moz-range-thumb {
    width: 28px;
    height: 28px;
    border-radius: 9999px;
    background: var(--thumb-bg, linear-gradient(180deg, #bbf7d0, #16a34a));
    border: 3px solid #fff;
    box-shadow: 0 6px 18px rgba(16, 24, 40, 0.18);
}

/* Mobile adjustments: slightly larger thumb and centered */
@media (max-width: 640px) {
    .range-custom-thumb::-webkit-slider-thumb {
        width: 34px;
        height: 34px;
        margin-top: -11px;
    }
    .range-custom-thumb::-moz-range-thumb {
        width: 34px;
        height: 34px;
    }
    .range-custom-thumb::-webkit-slider-runnable-track {
        height: 14px;
    }
}
</style>
