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
        >
          Annuler
        </button>
        <button
          type="button"
          class="px-3 py-1 text-sm bg-blue-600 text-white rounded"
          @click="$emit('confirm')"
        >
          Confirmer
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
})
defineEmits(['confirm', 'cancel'])
</script>
