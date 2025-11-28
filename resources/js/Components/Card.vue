<script setup>
defineProps({
  title: String,
});
</script>

<template>
  <div class="card">
    <h3 v-if="title" class="card-title">{{ title }}</h3>
    <div class="card-body">
      <slot />
    </div>
  </div>
</template>

<style scoped>
.card {
  background-color: #ffffff;
  border-radius: 8px;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
  padding: 1.5rem;
  transition: transform 0.2s;
}
.card:hover {
    transform: translateY(-5px);
}
.card-title {
  margin: 0 0 1rem 0;
  font-size: 1.1rem;
  font-weight: 600;
  color: #34495e;
  border-bottom: 1px solid #f0f0f0;
  padding-bottom: 0.75rem;
}
</style>