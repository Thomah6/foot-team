<template>
  <transition name="fade">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4">
      <div class="fixed inset-0 bg-black/50 dark:bg-black/70" @click="close"></div>

      <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-lg w-full z-10 overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100">{{ title }}</h3>
        </div>

        <div class="p-6">
          <p class="text-sm text-gray-600 dark:text-gray-300">{{ message }}</p>
        </div>

        <div class="p-6 border-t border-gray-200 dark:border-gray-700 flex justify-end gap-3">
          <button @click="close" class="px-4 py-2 rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors font-medium">
            {{ cancelText }}
          </button>
          <button @click="confirm" :class="['px-4 py-2 rounded-lg font-medium transition-colors', confirmClass]">
            {{ confirmText }}
          </button>
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
    ? 'bg-red-600 hover:bg-red-700 text-white shadow-sm'
    : 'bg-citron-500 hover:bg-citron-600 text-white shadow-sm';
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
