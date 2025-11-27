<template>
  <div class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-slate-800 rounded-lg p-6 max-w-md w-full mx-4">
      <h2 class="text-xl font-bold text-[#111318] dark:text-white mb-4">
        Déclarer votre présence
      </h2>

      <div class="mb-6">
        <label class="block text-sm font-medium text-[#111318] dark:text-white mb-2">
          Date de la présence *
        </label>
        <input
          v-model="selectedDate"
          type="date"
          class="w-full px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary"
        />
      </div>

      <div class="flex gap-3 justify-end">
        <button
          @click="$emit('close')"
          class="px-4 py-2 border border-slate-200 dark:border-slate-600 rounded-lg text-[#111318] dark:text-white hover:bg-black/5 dark:hover:bg-white/5"
        >
          Annuler
        </button>
        <button
          @click="submit"
          class="px-4 py-2 bg-primary text-white rounded-lg hover:opacity-90 font-medium"
        >
          Confirmer
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const selectedDate = ref(new Date().toISOString().split('T')[0])

const emit = defineEmits(['close', 'submit'])

const submit = () => {
  if (selectedDate.value) {
    emit('submit', selectedDate.value)
  }
}
</script>
