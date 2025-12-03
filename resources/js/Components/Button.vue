<script setup>
import { computed } from 'vue';

const props = defineProps({
  variant: {
    type: String,
    default: 'primary', // 'primary', 'secondary', 'danger'
  },
  size: {
    type: String,
    default: 'md', // 'sm', 'md', 'lg'
  },
});

const buttonClass = computed(() => {
  const baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-200 transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-offset-2 rounded-lg cursor-pointer';
  
  const sizeClasses = {
    sm: 'px-3 py-2 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
  };
  
  const variantClasses = {
    primary: 'bg-gradient-to-r from-emerald-600 to-lime-600 text-white hover:from-emerald-700 hover:to-lime-700 focus:ring-emerald-500 shadow-md hover:shadow-lg',
    secondary: 'bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-100 hover:bg-gray-300 dark:hover:bg-gray-600 focus:ring-gray-400',
    danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-md hover:shadow-lg',
  };
  
  return `${baseClasses} ${sizeClasses[props.size]} ${variantClasses[props.variant]}`;
});
</script>

<template>
  <button class="px-4 py-2 rounded-lg font-medium transition-all duration-200 bg-citron-500 hover:bg-citron-600 text-white shadow-sm hover:shadow-md active:scale-[0.98]">
    <slot />
  </button>
</template>