<script setup>
defineProps({
    disabled: {
        type: Boolean,
        default: false,
    },
    size: {
        type: String,
        default: 'md',
        validator: (value) => ['sm', 'md', 'lg'].includes(value),
    },
    variant: {
        type: String,
        default: 'solid',
        validator: (value) => ['solid', 'outline', 'ghost'].includes(value),
    },
});

const sizeClasses = {
    sm: 'px-3 py-1.5 text-xs',
    md: 'px-4 py-2 text-sm',
    lg: 'px-6 py-3 text-base',
};

const variantClasses = {
    solid: 'bg-primary-600 text-white hover:bg-primary-700 active:bg-primary-800 border border-transparent',
    outline: 'bg-transparent border-2 border-primary-600 text-primary-600 hover:bg-primary-50 active:bg-primary-100 dark:hover:bg-primary-900/20',
    ghost: 'bg-transparent text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 border border-transparent',
};
</script>

<template>
    <button
        :disabled="disabled"
        :class="[
            'inline-flex items-center justify-center font-semibold rounded-button',
            'transition-all duration-200 ease-in-out',
            'focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900',
            'disabled:opacity-50 disabled:cursor-not-allowed',
            sizeClasses[size],
            variantClasses[variant],
        ]"
    >
        <slot />
    </button>
</template>
