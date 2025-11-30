<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const classes = computed(() =>
    props.active
        ? 'inline-flex items-center px-3 pt-1 pb-1 border-b-2 border-primary-600 text-sm font-semibold text-gray-900 dark:text-white focus:outline-none transition-colors duration-150 ease-in-out'
        : 'inline-flex items-center px-3 pt-1 pb-1 border-b-2 border-transparent text-sm font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:border-gray-300 dark:hover:border-gray-600 focus:outline-none focus:border-primary-500 transition-colors duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
