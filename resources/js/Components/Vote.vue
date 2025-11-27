<template>
  <div class="vote-component p-4">
    <h2 class="text-lg font-bold mb-4 text-center">Cast Your Vote</h2>
    <div v-for="option in options" :key="option.id" class="vote-option">
      <label
        @click="selectOption(option.id)"
        :class="{
          'bg-gray-100 border-gray-300': selectedOption !== option.id,
          'bg-red-400 border-red-500': selectedOption == option.id,
        }"
        class="block border rounded-lg p-4 mb-4 cursor-pointer transition-all"
      >
      <div>{{ selectedOption }}</div>
      <div>{{ option.id }}</div>
        <input
          type="radio"
          :value="option.id"
          v-model="selectedOption"
          class="hidden"
        />
        <div class="font-bold text-sm md:text-base">{{ option.title }}</div>
        <div class="text-xs text-gray-600 md:text-sm">{{ option.description }}</div>
      </label>
    </div>
    <button
      v-if="isAdmin && isVoteEnded"
      @click="validateVote"
      class="w-full bg-gray-500 text-black px-4 py-2 rounded-lg mt-4 text-sm md:text-base"
    >
      Validate Vote
    </button>
  </div>
</template>

<script setup>

import { ref, defineProps } from 'vue';

defineProps({
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
});

const selectedOption = ref(null);

const selectOption = (optionId) => {
  selectedOption.value = optionId;
};

const validateVote = () => {
  if (selectedOption.value !== null) {
    alert(`Vote validated for option ID: ${selectedOption.value}`);
    // Emit the event or handle the validation logic here
  } else {
    alert('Please select an option before validating the vote.');
  }
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
