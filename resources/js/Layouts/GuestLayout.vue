<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-primary-50 to-primary-100 dark:from-gray-900 dark:to-gray-800 flex flex-col items-center justify-center px-4 py-6 sm:py-12">
        <!-- Logo Section -->
        <div class="mb-8 sm:mb-10">
            <Link href="/" class="inline-block">
                <ApplicationLogo class="h-16 w-16 sm:h-20 sm:w-20 fill-current text-primary-600 dark:text-primary-400" />
            </Link>
        </div>

        <!-- Auth Card -->
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-elevation border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-8 sm:px-8 sm:py-10">
                    <slot />
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 text-center text-sm text-gray-600 dark:text-gray-400">
            <p>© 2024 FC Dynamo. Tous droits réservés.</p>
        </div>
    </div>
</template>
