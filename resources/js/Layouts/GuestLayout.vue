<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div
        class="flex min-h-screen flex-col items-center bg-gradient-to-br from-gray-50 to-citron-50 dark:from-gray-900 dark:to-gray-800 pt-6 sm:justify-center sm:pt-0"
    >
        <div class="mb-8">
            <Link href="/">
                <ApplicationLogo class="h-24 w-auto fill-current text-gray-800 dark:text-gray-100" />
            </Link>
        </div>

        <div
            class="mt-6 w-full overflow-hidden bg-white dark:bg-gray-800 px-6 py-8 shadow-xl sm:max-w-md sm:rounded-xl border border-gray-200 dark:border-gray-700"
        >
            <slot />
        </div>
        
        <!-- Footer avec lien de retour -->
        <div class="mt-8 text-sm text-gray-600 dark:text-gray-400">
            <Link :href="route('dashboard')" class="hover:text-citron-600 dark:hover:text-citron-400 transition-colors">
                ← Retour au tableau de bord
            </Link>
        </div>
    </div>
</template>