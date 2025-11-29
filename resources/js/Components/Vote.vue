<template>
    <div class="vote-component p-4">
        <h2 class="text-lg font-bold mb-4 text-center">Cast Your Vote</h2>
        <div v-if="!isVoteEnded">
            <div v-for="option in options" :key="option.id" class="vote-option">
                <label
                    @click="submitVote(option)"
                    :class="{
                        'bg-green-400 border-green-500':
                            selectedOption === option.id &&
                            option.option === 'POUR',
                        'bg-red-400 border-red-500':
                            selectedOption === option.id &&
                            option.option === 'CONTRE',
                        'bg-gray-100 border-gray-500': selectedOption !== option.id,
                    }"
                    class="block border rounded-lg p-4 mb-4 cursor-pointer transition-all"
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
            class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg mt-4 text-sm md:text-base"
        >
            Validation du vote
        </button>
    </div>
</template>

<script setup>
import { ref, defineProps } from "vue";
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
        reflection_id: props.reflection.id, // Exemple d'ID de réflexion (à remplacer par une valeur dynamique si nécessaire)
    })
};
</script>

<style scoped>
.vote-component {
    max-width: 600px;
    margin: 0 auto;
}
.vote-option {
    transition: background-color 0.3s, border-color 0.3s;
}
.vote-option:hover {
    background-color: #f0f0f0;
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
