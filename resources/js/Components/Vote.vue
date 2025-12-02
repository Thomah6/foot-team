<template>
    <div class="vote-component p-4 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-bold mb-4 text-center text-gray-800 dark:text-gray-100">Cast Your Vote</h2>
        <div v-if="!isVoteEnded" class="grid grid-cols-2 gap-4 w-full">
            <div v-for="option in options" :key="option.id" class="vote-option">
                <label
                    @click="submitVote(option)"
                    :class="{
                        'bg-citron-100 dark:bg-citron-900 border-citron-500 text-citron-800 dark:text-citron-200':
                            selectedOption === option.id &&
                            option.option === 'POUR',
                        'bg-red-100 dark:bg-red-900 border-red-500 text-red-800 dark:text-red-200':
                            selectedOption === option.id &&
                            option.option === 'CONTRE',
                        'bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600': selectedOption !== option.id,
                    }"
                    class="block border-2 rounded-lg p-4 mb-4 cursor-pointer transition-all duration-200 font-semibold"
                >
                    <input
                        type="radio"
                        :value="option.id"
                        v-model="selectedOption"
                        class="hidden"
                    />
                    <div class="font-bold text-sm md:text-base">
                        {{ option.option }}
                    </div>
                </label>
            </div>
        </div>
        <!-- Bouton de validation visible uniquement pour l'admin et après la date limite -->
        <button
            v-if="isAdmin && isVoteEnded"
            @click="validateVote"
            class="w-full bg-citron-500 hover:bg-citron-600 text-white px-4 py-2 rounded-lg mt-4 text-sm md:text-base font-medium transition-colors shadow-sm"
        >
            Validation du vote
        </button>
    </div>
</template>

<script setup>
import { ref, defineProps, onMounted, watch } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    isAdmin: {
        type: Boolean,
        default: false,
    },
    isVoteEnded: {
        type: Boolean,
        default: false,
    },
    existingVote: {
        type: Number,
        default: null,
    },
    reflection: Object,
});
// Lors du montage, si l'utilisateur a déjà voté, sélectionner l'option correspondante
onMounted(() => {
    const ev = props.existingVote;
    if (ev === 1) {
        // POUR -> option id 1
        selectedOption.value = 1;
    } else if (ev === -1) {
        // CONTRE -> option id 2
        selectedOption.value = 2;
    }
});

// Si la prop existingVote change (Inertia remplace les props), mettre à jour la sélection
watch(() => props.existingVote, (ev) => {
    if (ev === 1) selectedOption.value = 1;
    else if (ev === -1) selectedOption.value = 2;
    else selectedOption.value = null;
});

const selectedOption = ref(null);

const submitVote = (option) => {
    selectedOption.value = option.id;

    const value = option.option === "POUR" ? 1 : -1;

    router.post(route("vote.store"), {
        reflection_id: props.reflection.id,
        value: value,
    });
};

const validateVote = () => {
    router.post(route("vote.validate"), {
        reflection_id: props.reflection.id,
    })
};
</script>

<style scoped>
.vote-component {
    max-width: 600px;
}
.vote-option {
    transition: all 0.3s ease;
    max-height: 40px;
}
.vote-option:hover {
    transform: translateY(-2px);
}
@media (min-width: 768px) {
    .vote-component {
        padding: 2rem;
    }
    .vote-option {
        padding: 1.5rem;
    }
}
</style>