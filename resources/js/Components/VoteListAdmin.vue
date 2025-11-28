<template>
    <div class="vote-list-admin p-4">
        <h2 class="text-lg font-bold mb-4 text-center">Vote Results</h2>
        <div v-for="reflection in reflections" :key="reflection.id" class="mb-6">
            <h3 class="text-md font-semibold">
                {{ reflection.title }}
            </h3>
            <p class="text-sm">
                <strong>POUR:</strong> {{ reflection.pourPercentage }}%
            </p>
            <p class="text-sm">
                <strong>CONTRE:</strong> {{ reflection.contrePercentage }}%
            </p>
            <p class="text-sm font-bold">
                Result: {{ reflection.winner }}
            </p>
            <ul>
                <li
                    v-for="vote in reflection.votes"
                    :key="vote.id"
                    class="border p-4 mb-2 rounded-lg"
                    :class="{
                        'bg-yellow-100': vote.is_bureau,
                    }"
                >
                    <p class="text-sm">
                        <strong>Vote:</strong>
                        <span
                            :class="{
                                'text-green-500': vote.value === 1,
                                'text-red-500': vote.value === -1,
                            }"
                        >
                            {{ vote.value === 1 ? 'POUR' : 'CONTRE' }}
                        </span>
                    </p>
                </li>
            </ul>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const reflections = ref([]);

onMounted(async () => {
    const response = await axios.get(route("vote.list.admin"));
    reflections.value = response.data;
});
</script>

<style scoped>
.vote-list-admin {
    max-width: 800px;
    margin: 0 auto;
}
</style>