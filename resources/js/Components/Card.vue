<script setup>
defineProps({
  title: {
    type: String,
    default: null,
  },
  subtitle: {
    type: String,
    default: null,
  },
  padding: {
    type: String,
    default: 'md',
    validator: (value) => ['sm', 'md', 'lg'].includes(value),
  },
  variant: {
    type: String,
    default: 'default',
    validator: (value) => ['default', 'elevated', 'bordered'].includes(value),
  },
});

const paddingClasses = {
  sm: 'p-3 sm:p-4',
  md: 'p-4 sm:p-6',
  lg: 'p-6 sm:p-8',
};

const variantClasses = {
  default: 'bg-white dark:bg-gray-800 rounded-card shadow-card border border-gray-200 dark:border-gray-700',
  elevated: 'bg-white dark:bg-gray-800 rounded-card shadow-elevation border-0',
  bordered: 'bg-transparent dark:bg-transparent rounded-card border-2 border-primary-500 dark:border-primary-400',
};
</script>

<template>
  <div :class="[variantClasses[variant], paddingClasses[padding], 'transition-all duration-200 hover:shadow-card-hover']">
    <!-- Card Header -->
    <div v-if="title || $slots.header" class="mb-4">
      <div v-if="$slots.header" class="card-header">
        <slot name="header" />
      </div>
      <div v-else>
        <h3 class="card-title">{{ title }}</h3>
        <p v-if="subtitle" class="card-subtitle mt-1">{{ subtitle }}</p>
      </div>
    </div>

    <!-- Card Content -->
    <div class="card-content">
      <slot />
    </div>

    <!-- Card Footer -->
    <div v-if="$slots.footer" class="card-footer">
      <slot name="footer" />
    </div>
  </div>
</template>