<script setup>
import { ref, watch, onMounted, onBeforeUnmount, computed } from "vue";

const props = defineProps({
    message: { type: String, default: "" },
    duration: { type: Number, default: 4000 },
    type: { type: String, default: "success" },
    modelValue: { type: Boolean, default: false },
});
const emit = defineEmits(["close", "update:modelValue"]);

const visible = ref(props.modelValue);
let timer = null;
const progress = ref(0);
let progressInterval = null;

function startTimer() {
    clearTimeout(timer);
    clearInterval(progressInterval);
    progress.value = 0;

    if (props.message || props.modelValue) {
        visible.value = true;
        const duration = Math.max(100, Number(props.duration) || 4000);
        const step = 100 / Math.max(1, Math.floor(duration / 100));
        progressInterval = setInterval(() => {
            progress.value = Math.min(100, progress.value + step);
        }, 100);
        timer = setTimeout(() => {
            visible.value = false;
            clearInterval(progressInterval);
            emit("update:modelValue", false);
            emit("close");
        }, duration);
    }
}

watch(
    () => props.message,
    (newVal) => {
        if (newVal) startTimer();
    }
);

watch(
    () => props.modelValue,
    (newVal) => {
        visible.value = newVal;
        if (newVal) startTimer();
    }
);

onMounted(() => {
    if (props.message) startTimer();
});

onBeforeUnmount(() => {
    clearTimeout(timer);
    clearInterval(progressInterval);
});

function close() {
    visible.value = false;
    clearTimeout(timer);
    clearInterval(progressInterval);
    emit("update:modelValue", false);
    emit("close");
}

const containerClass = computed(() => {
    if (props.type === "error") return "bg-red-600";
    if (props.type === "success") return "bg-green-600";
    return "bg-neutral-800";
});
</script>
<template>
    <transition name="fade">
        <div v-if="visible" class="fixed right-6 top-6 z-50 max-w-sm w-full">
            <div
                :class="containerClass"
                class="px-4 py-3 rounded shadow-lg flex items-start justify-between gap-4"
            >
                <div class="flex-1">
                    <div class="flex items-start gap-3">
                        <span
                            v-if="type === 'success'"
                            class="text-green-100 material-symbols-outlined"
                            >check_circle</span
                        >
                        <span
                            v-else-if="type === 'error'"
                            class="text-red-100 material-symbols-outlined"
                            >error</span
                        >
                        <div class="text-sm text-white break-words">
                            {{ message }}
                        </div>
                    </div>
                    <div
                        class="w-full h-1 bg-white/20 rounded overflow-hidden mt-3"
                    >
                        <div
                            class="bg-white/80 h-1"
                            :style="{
                                width: progress + '%',
                                transition: 'width 0.1s linear',
                            }"
                        ></div>
                    </div>
                </div>
                <button
                    @click="close"
                    class="text-white opacity-90 hover:opacity-100 ml-3"
                >
                    ✕
                </button>
            </div>
        </div>
    </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.15s;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
.material-symbols-outlined {
    font-variation-settings: "wght" 400;
}
</style>
