<script setup>
defineProps({
  title: String,
});
</script>

<template>
  <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md hover:shadow-lg transition-all duration-200 hover:-translate-y-1 p-4 sm:p-6">
    <h3 v-if="title" class="text-base sm:text-lg font-semibold text-gray-800 dark:text-gray-100 border-b-2 border-lime-200 dark:border-lime-700 pb-3 mb-4">
      {{ title }}
    </h3>
    <div class="text-gray-700 dark:text-gray-300">
      <slot />
    </div>
  </div>
</template>