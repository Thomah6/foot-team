<template>
    <div class="vote-component p-4">
        <h2 class="text-lg font-bold mb-4 text-center">Cast Your Vote</h2>
        <div v-for="option in options" :key="option.id" class="vote-option">
            <label
                :class="{
                    'bg-green-100 border-green-500':
                        selectedOption === option.id,
                    'bg-gray-100 border-gray-300': selectedOption !== option.id,
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
                    {{ option.title }}
                </div>
                <div class="text-xs text-gray-600 md:text-sm">
                    {{ option.description }}
                </div>
            </label>
        </div>
        <button
            v-if="isAdmin && isVoteEnded"
            @click="validateVote"
            class="w-full bg-blue-500 text-black px-4 py-2 rounded-lg mt-4 text-sm md:text-base"
        >
            Validate Vote
        </button>
    </div>
</template>

<script>
export default {
    props: {
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
    },
    data() {
        return {
            selectedOption: null,
        };
    },
    methods: {
        validateVote() {
            this.$emit("validate-vote", this.selectedOption);
        },
    },
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
