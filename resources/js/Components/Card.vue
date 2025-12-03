<script setup>
defineProps({
  title: String,
});
</script>

<template>
  <div class="card bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
    <h3 v-if="title" class="card-title text-gray-800 dark:text-gray-100 border-gray-200 dark:border-gray-700">{{ title }}</h3>
    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<style scoped>
.card {
  background-color: #ffffff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
  padding: 1.5rem;
  transition: all 0.3s ease;
  border: 1px solid #e5e7eb;
}
.dark .card {
  background-color: #1f2937;
  border-color: #374151;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}
.card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 16px rgba(163, 230, 53, 0.1);
}
.dark .card:hover {
  box-shadow: 0 8px 16px rgba(163, 230, 53, 0.15);
}
.card-title {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #374151;
  border-bottom: 2px solid #f3f4f6;
  padding-bottom: 0.75rem;
}
.dark .card-title {
  color: #f9fafb;
  border-bottom-color: #374151;
}
</style>
