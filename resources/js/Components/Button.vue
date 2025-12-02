<script setup>
import { computed } from 'vue';
import { useAttrs } from 'vue';

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

const attrs = useAttrs();

const isLink = computed(() => typeof attrs.href !== 'undefined' && attrs.href !== false && attrs.href !== null);

const buttonClass = computed(() => {
  const baseClasses = 'inline-flex items-center justify-center font-semibold transition-all duration-200 transform focus:outline-none focus:ring-2 focus:ring-offset-0 rounded-lg cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed';

  const sizeClasses = {
    sm: 'px-3 py-2 text-xs gap-2',
    md: 'px-4 py-2 text-sm gap-2',
    lg: 'px-6 py-3 text-base gap-3',
  };

  const variantClasses = {
    primary: 'bg-gradient-to-r from-emerald-600 to-lime-600 text-white hover:from-emerald-700 hover:to-lime-700 focus:ring-emerald-500 shadow-md hover:shadow-lg',
    secondary: 'bg-gray-100 dark:bg-gray-800 text-gray-900 dark:text-gray-100 border border-gray-200 dark:border-gray-700 hover:bg-gray-200 dark:hover:bg-gray-700 focus:ring-gray-400',
    danger: 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 shadow-md hover:shadow-lg',
  };

  const size = sizeClasses[props.size] || sizeClasses.md;
  const variant = variantClasses[props.variant] || variantClasses.primary;

  return `${baseClasses} ${size} ${variant}`;
});
</script>

<template>
  <component :is="isLink ? 'a' : 'button'" :class="buttonClass" v-bind="$attrs" :aria-disabled="$attrs.disabled">
    <span class="flex items-center">
      <slot name="icon" />
      <span><slot /></span>
    </span>
  </component>
</template>