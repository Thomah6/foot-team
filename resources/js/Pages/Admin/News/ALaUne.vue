<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    featured: Object,
});

// console.log(props.featured)

</script>

<template>
    <Link :href="route('admin.news.index')">
        <section class="p-4 bg-white shadow rounded cursor-pointer hover:bg-gray-50 transition">
            <h2 class="text-xl font-bold mb-2">À la une</h2>
            <div v-if="featured && featured.title" class="space-y-2">
                <h3 class="text-lg font-semibold">{{ featured.title }}</h3>
            </div>
            <p v-else class="text-gray-500">Aucun contenu mis en avant.</p>
        </section>
    </Link>
</template>
