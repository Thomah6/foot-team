<template>
    <section class="p-4 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-2">À la une</h2>
    <div v-if="featured && featured.title" class="space-y-2">
    <h3 class="text-lg font-semibold">{{ featured.title }}</h3>
    <p class="text-gray-600">{{ featured.content }}</p>
    <img v-if="featured.image" :src="featured.image" class="w-full mt-2 rounded" />
    </div>
    <p v-else class="text-gray-500">Aucun contenu mis en avant.</p>
    </section>
    </template>
    
    
    <script setup>
    const props = defineProps({
    featured: Object,
    });
    </script>