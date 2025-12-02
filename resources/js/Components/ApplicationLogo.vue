<script setup>
import { usePage } from '@inertiajs/vue3';

const identity = usePage().props.identity;
</script>

<template>
    <div class="flex items-center space-x-3">
      <img
        v-if="identity?.logo"
        :src="identity.logo"
        alt="Logo du club"
         class="w-12 h-12 rounded-full object-cover border-2 border-citron-500 dark:border-citron-600 shadow-lg"
      />
      <span class="font-bold text-gray-800 dark:text-gray-100 text-lg">{{ identity?.name }}</span>
    </div>
</template>
