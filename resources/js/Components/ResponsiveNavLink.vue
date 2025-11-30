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
        ? 'flex items-center w-full ps-3 pe-4 py-3 border-l-4 border-primary-600 text-start text-base font-semibold text-primary-700 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 focus:outline-none focus:text-primary-800 focus:bg-primary-100 transition-colors duration-150 ease-in-out'
        : 'flex items-center w-full ps-3 pe-4 py-3 border-l-4 border-transparent text-start text-base font-semibold text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-900 focus:bg-gray-100 transition-colors duration-150 ease-in-out',
);
</script>

<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>
