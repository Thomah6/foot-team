<script setup>
import Vote from '@/Components/Vote.vue';
import { defineProps } from 'vue';

defineProps({
    'options': {
        type: Array,
        required: true,
    },
    'isAdmin': {
        type: Boolean,
        default: false,
    },
    'isVoteEnded': {
        type: Boolean,
        default: false,
    },
})
</script>


<template>
<Vote
    :options="options"
    :is-admin="isAdmin"
    :is-vote-ended="isVoteEnded"
    @validate-vote="(selectedOption) => console.log('Vote validated for option:', selectedOption)"
  />

</template>


<style scoped>


</style>