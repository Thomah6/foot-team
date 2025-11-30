<script setup>
defineProps({
    message: {
        type: String,
    },
});
</script>

<template>
    <div v-show="message" class="alert alert-error">
        <i class="fa-solid fa-circle-exclamation flex-shrink-0"></i>
        <p class="text-sm font-medium">
            {{ message }}
        </p>
    </div>
</template>
