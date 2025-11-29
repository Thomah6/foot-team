<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white rounded-lg shadow-lg w-full max-w-sm p-4">
      <h2 class="font-bold text-lg mb-2">{{ title }}</h2>
      <p class="text-sm text-gray-700 mb-4">{{ message }}</p>
      <div class="flex justify-end gap-2">
        <button
          type="button"
          class="px-3 py-1 text-sm border rounded"
          @click="$emit('cancel')"
          :disabled="loading"
        >
          Annuler
        </button>
        <button
          type="button"
          class="px-3 py-1 text-sm bg-blue-600 text-white rounded flex items-center gap-2"
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

<script setup>
defineProps({
  show: Boolean,
  title: { type: String, default: 'Confirmation' },
  message: { type: String, default: 'Voulez-vous continuer ?' },
  loading: { type: Boolean, default: false },
})
defineEmits(['confirm', 'cancel'])
</script>
