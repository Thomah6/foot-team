<script setup>
defineProps({
  show: Boolean,
  title: { type: String, default: 'Confirmation' },
  message: { type: String, default: 'Voulez-vous continuer ?' },
  loading: { type: Boolean, default: false },
})
defineEmits(['confirm', 'cancel'])
</script>
<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 dark:bg-black/60">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-sm p-6 border border-gray-200 dark:border-gray-700">
      <h2 class="font-bold text-lg mb-3 text-gray-800 dark:text-gray-100">{{ title }}</h2>
      <p class="text-sm text-gray-600 dark:text-gray-300 mb-6">{{ message }}</p>
      <div class="flex justify-end gap-3">
        <button
          type="button"
          class="px-4 py-2 text-sm font-medium rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          @click="$emit('cancel')"
          :disabled="loading"
        >
          Annuler
        </button>
        <button
          type="button"
          class="px-4 py-2 text-sm font-medium rounded-lg bg-citron-500 hover:bg-citron-600 text-white flex items-center gap-2 transition-colors shadow-sm"
          @click="$emit('confirm')"
          :disabled="loading"
        >
          <svg v-if="loading" class="w-4 h-4 animate-spin text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
          </svg>
          <span>{{ loading ? 'En cours...' : 'Confirmer' }}</span>
        </button>
      </div>
    </div>
  </div>
</template>
