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
    <div
        class="deposit-animate flex flex-col gap-4 p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700 rounded-lg"
    >
        <h2 class="text-xl font-bold text-neutral-900 dark:text-white">
            Déclarer un dépôt
        </h2>
        <p class="text-neutral-600 dark:text-slate-400 text-sm">
            Faites glisser pour sélectionner le montant de votre dépôt.
        </p>
        <div class="flex flex-col items-center gap-4">
            <span class="text-5xl font-bold text-primary dark:text-blue-400"
                >{{ montant }} F CFA</span
            >
            <input
                type="range"
                min="100"
                max="10000"
                step="100"
                v-model.number="montant"
                @input="onUserInteract"
                class="w-full h-2 bg-neutral-200 dark:bg-slate-700 rounded-lg accent-primary dark:accent-blue-500"
            />
            <div
                class="w-full flex justify-between text-xs text-neutral-600 dark:text-slate-400"
            >
                <span>100 F CFA</span>
                <span>10 000 F CFA</span>
            </div>
            <input
                v-model="description"
                type="text"
                placeholder="Description (optionnel)"
                class="w-full px-4 py-2 border border-neutral-200 dark:border-slate-700 rounded-lg text-sm bg-white dark:bg-slate-800 text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-slate-400"
            />
            <button
                @click="askConfirmation"
                class="w-full h-12 px-4 bg-blue-600 dark:bg-blue-700 text-white rounded-lg font-bold mt-4 hover:bg-blue-700 dark:hover:bg-blue-800"
            >
                Envoyer
            </button>
        </div>

        <ConfirmModalFinance
            :show="showConfirmModal"
            :loading="showConfirmModalLoading"
            title="Confirmer le dépôt"
            :message="`Voulez-vous déclarer un dépôt de ${montant} F CFA ?`"
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

.deposit-animate .text-5xl {
    transition: transform 220ms ease, color 220ms ease;
}
.deposit-animate button:active {
    transform: translateY(1px) scale(0.998);
}
</style>
