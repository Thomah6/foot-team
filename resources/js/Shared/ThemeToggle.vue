<script setup>
import { ref, onMounted } from 'vue';

const isDark = ref(false);

onMounted(() => {
  // Récupère la préférence sauvegardée ou utilise la préférence système
  const saved = localStorage.getItem('theme');
  const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
  
  isDark.value = saved ? saved === 'dark' : prefersDark;
  applyTheme();
});

function toggleTheme() {
  isDark.value = !isDark.value;
  applyTheme();
}

function applyTheme() {
  const html = document.documentElement;
  
  if (isDark.value) {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  } else {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  }
}
</script>

<template>
  <button
    @click="toggleTheme"
    class="relative inline-flex items-center justify-center w-10 h-10 rounded-lg bg-slate-200 dark:bg-slate-700 hover:bg-slate-300 dark:hover:bg-slate-600 transition-colors duration-300 focus:outline-none focus:ring-2 focus:ring-lime-500"
    :title="isDark ? 'Mode clair' : 'Mode sombre'"
  >
    <!-- Icône Soleil (mode light) -->
    <i 
      :class="[
        'fas fa-sun text-yellow-500 text-lg transition-all duration-300',
        isDark ? 'scale-0 opacity-0' : 'scale-100 opacity-100'
      ]"
    ></i>
    
    <!-- Icône Lune (mode dark) -->
    <i 
      :class="[
        'fas fa-moon text-blue-400 text-lg absolute transition-all duration-300',
        isDark ? 'scale-100 opacity-100' : 'scale-0 opacity-0'
      ]"
    ></i>
  </button>
</template>

<style scoped>
/* Optionnel : animation plus smooth */
button {
  transition: all 300ms cubic-bezier(0.4, 0, 0.2, 1);
}
</style>
