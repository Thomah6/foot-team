<template>
  <transition name="fade">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/50" @click="close"></div>

      <div class="bg-white rounded-lg shadow-lg max-w-lg w-full z-10 overflow-hidden">
        <div class="p-4 border-b">
          <h3 class="text-lg font-semibold text-sky-900">{{ title }}</h3>
        </div>

        <div class="p-4">
          <p class="text-sm text-gray-600">{{ message }}</p>
        </div>

        <div class="p-4 border-t flex justify-end gap-3">
          <button @click="close" class="px-4 py-2 rounded bg-gray-100 text-gray-700 hover:bg-gray-200">{{ cancelText }}</button>
          <button @click="confirm" :class="['px-4 py-2 rounded font-medium', confirmClass]">{{ confirmText }}</button>
        </div>
      </div>
    </div>
  </transition>
</template>

<script setup>
import { computed } from 'vue';
const props = defineProps({
  modelValue: { type: Boolean, default: false },
  title: { type: String, default: 'Confirmer' },
  message: { type: String, default: 'Êtes-vous sûr ?' },
  confirmText: { type: String, default: 'Confirmer' },
  cancelText: { type: String, default: 'Annuler' },
  variant: { type: String, default: 'danger' },
});
const emit = defineEmits(['update:modelValue','confirm']);

const confirmClass = computed(() => {
  return props.variant === 'danger'
    ? 'bg-red-600 text-white hover:bg-red-700'
    : 'bg-sky-700 text-white hover:bg-sky-800';
});

function close() {
  emit('update:modelValue', false);
}

function confirm() {
  emit('confirm');
  emit('update:modelValue', false);
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .2s }
.fade-enter-from, .fade-leave-to { opacity: 0 }
</style>
