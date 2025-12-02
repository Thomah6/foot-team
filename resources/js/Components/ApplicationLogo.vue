<script setup>
import { usePage } from '@inertiajs/vue3';

const identity = usePage().props.identity;
</script>

<template>
 
    <div class="flex items-center space-x-2">
      <img 
        v-if="identity?.logo" 
        :src="identity.logo" 
        alt="Logo du club" 
         class="w-50 h-50 rounded-full  object-cover border border-gray-300 shadow-sm"
      />
      <span class="font-bold">{{ identity?.name }}</span>
    </div>

</template>
