<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary', // 'primary', 'secondary'
  },
});

const buttonClass = computed(() => {
  return {
    'btn': true,
    'btn-primary': props.variant === 'primary',
    'btn-secondary': props.variant === 'secondary',
  };
});
</script>

<template>
  <button :class="buttonClass">
    <slot />
  </button>
</template>

<style scoped>
.btn {
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 5px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.3s, transform 0.2s;
}
.btn:hover {
    transform: scale(1.05);
}
.btn-primary {
  background-color: #1abc9c;
  color: white;
}
.btn-primary:hover {
  background-color: #16a085;
}
.btn-secondary {
  background-color: #bdc3c7;
  color: #2c3e50;
}
.btn-secondary:hover {
  background-color: #a0a6ab;
}
</style>