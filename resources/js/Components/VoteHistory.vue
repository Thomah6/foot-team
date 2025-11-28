<template>
    <div class="vote-history p-4">
        <h2 class="text-lg font-bold mb-4 text-center">My Vote History</h2>
        <ul>
            <li
                v-for="vote in votes"
                :key="vote.id"
                class="border p-4 mb-4 rounded-lg"
            >
                <p class="text-sm">
                    <strong>Reflection:</strong> {{ vote.reflection.title }}
                </p>
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
                <p class="text-sm text-gray-500">
                    <strong>Date:</strong> {{ vote.created_at }}
                </p>
            </li>
        </ul>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "axios";

const votes = ref([]);

onMounted(async () => {
    const response = await axios.get(route("vote.history"));
    votes.value = response.data;
});
</script>

<style scoped>
.vote-history {
    max-width: 600px;
    margin: 0 auto;
}
</style>