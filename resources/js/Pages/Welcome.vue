<script setup>
import { Link } from '@inertiajs/vue3'
import { ref, onMounted } from 'vue'

defineProps({
  clubIdentity: {
    type: Object,
    required: true
  }
})

const isLoaded = ref(false)

onMounted(() => {
  setTimeout(() => {
    isLoaded.value = true
  }, 100)
})
</script>

<template>
  <Head title="FC Dynamo - Club Management Dashboard" />
  
  <div class="min-h-screen bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 overflow-hidden relative transition-colors duration-300">
    
    <!-- Animated Background Elements -->
    <div class="absolute inset-0 overflow-hidden">
      <!-- Light mode gradient circles -->
      <div class="absolute w-96 h-96 bg-lime-200/30 rounded-full blur-3xl animate-pulse -top-48 -left-48 dark:hidden"></div>
      <div class="absolute w-96 h-96 bg-emerald-200/20 rounded-full blur-3xl animate-pulse delay-700 -bottom-48 -right-48 dark:hidden"></div>
      
      <!-- Dark mode gradient circles -->
      <div class="hidden dark:block absolute w-96 h-96 bg-lime-500/10 rounded-full blur-3xl animate-pulse -top-48 -left-48"></div>
      <div class="hidden dark:block absolute w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl animate-pulse delay-700 -bottom-48 -right-48"></div>
      
      <!-- Geometric pattern overlay -->
      <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iIzExMTExMSIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjMTExMTExIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+CjxzZWc+Cg==')] opacity-20 dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+CjxzZWc+Cg==')]"></div>
    </div>

    <!-- Subtle football field lines pattern -->
    <div class="absolute inset-0 opacity-5">
      <div class="absolute top-1/4 left-0 right-0 h-px bg-black dark:bg-white"></div>
      <div class="absolute top-1/2 left-0 right-0 h-px bg-black dark:bg-white"></div>
      <div class="absolute top-3/4 left-0 right-0 h-px bg-black dark:bg-white"></div>
      <div class="absolute top-1/2 left-1/4 w-1/2 border border-black dark:border-white rounded-full h-2/3"></div>
    </div>

    <!-- Main Content -->
    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 py-12">
      
      <!-- Logo/Club Identity -->
      <div class="text-center mb-8">
        <div class="mb-6 inline-flex items-center justify-center w-24 h-24 rounded-2xl  transform hover:rotate-6 transition-transform duration-300">
          <!-- Logo du club depuis BDD ou icône par défaut -->
          <img 
            v-if="clubIdentity.logo" 
            :src="clubIdentity.logo" 
            :alt="clubIdentity.name"
            class="w-16 h-16 object-contain rounded-lg"
            @error="$event.target.style.display='none'"
          />
          <svg v-else class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7v10c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V7l-10-5z"/>
          </svg>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-black mb-4">
          <span class="bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-emerald-800 dark:from-lime-300 dark:to-emerald-400">
            {{ clubIdentity.name }}
          </span>
        </h1>
        
        <div class="h-1.5 w-48 bg-gradient-to-r from-lime-500 via-emerald-500 to-lime-500 rounded-full mx-auto mb-6 animate-pulse"></div>
      </div>

      <!-- Tagline -->
      <div class="text-center mb-12 max-w-2xl">
        <p class="text-lg md:text-xl font-medium leading-relaxed text-gray-800 dark:text-gray-200 mb-4">
          <span class="bg-gradient-to-r from-lime-600 to-emerald-600 dark:from-lime-400 dark:to-emerald-400 bg-clip-text text-transparent">
            ⚡ Je vois le terrain comme une toile, chaque dribble comme un coup de pinceau. Ma création ? La victoire absolue.
          </span>
        </p>

      </div>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-5 mb-16">
        <Link 
          :href="route('login')" 
          class="group relative px-10 py-4 bg-gradient-to-r from-emerald-600 to-lime-600 dark:from-emerald-500 dark:to-lime-500 text-white font-bold rounded-xl shadow-lg hover:shadow-emerald-500/30 dark:hover:shadow-lime-500/30 transform hover:scale-105 transition-all duration-300 overflow-hidden"
        >
          <span class="relative z-10 flex items-center gap-3">
            <svg class="w-5 h-5 group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            Rejoindre le Vestiaire
          </span>
          <div class="absolute inset-0 bg-gradient-to-r from-emerald-700 to-lime-700 dark:from-emerald-600 dark:to-lime-600 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
        </Link>

        
      </div>

      <!-- Features Preview -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-5xl w-full mb-12">
        <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border-2 border-lime-200 dark:border-emerald-900 rounded-2xl p-7 hover:border-lime-400 dark:hover:border-emerald-500 transition-all duration-300 hover:-translate-y-1 shadow-sm">
          <div class="w-14 h-14 bg-gradient-to-br from-lime-400 to-emerald-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
          </div>
          <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-3">Présences Automatisées</h3>
          <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Plus besoin de feuilles de papier, tout est digital et automatisé !</p>
        </div>

        <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border-2 border-lime-200 dark:border-emerald-900 rounded-2xl p-7 hover:border-lime-400 dark:hover:border-emerald-500 transition-all duration-300 hover:-translate-y-1 shadow-sm">
          <div class="w-14 h-14 bg-gradient-to-br from-emerald-500 to-lime-500 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
            </svg>
          </div>
          <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-3">Stats Professionnelles</h3>
          <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Analysez vos performances avec des données précises et détaillées.</p>
        </div>

        <div class="group bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm border-2 border-lime-200 dark:border-emerald-900 rounded-2xl p-7 hover:border-lime-400 dark:hover:border-emerald-500 transition-all duration-300 hover:-translate-y-1 shadow-sm">
          <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
          </div>
          <h3 class="text-gray-900 dark:text-white font-bold text-lg mb-3">Team Spirit</h3>
          <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">Gérez votre équipe avec cohésion et efficacité, comme une vraie famille !</p>
        </div>
      </div>

      <!-- Subtle Football Elements -->
      <div class="flex items-center justify-center gap-8 opacity-20 mb-8">
        <div class="w-3 h-3 bg-lime-500 rounded-full"></div>
        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
        <div class="w-4 h-4 bg-lime-600 rounded-full"></div>
        <div class="w-2 h-2 bg-emerald-500 rounded-full"></div>
        <div class="w-3 h-3 bg-lime-500 rounded-full"></div>
      </div>

      <!-- Footer -->
      <div class="text-center mt-8">
        <p class="text-gray-500 dark:text-gray-500 text-sm">
          Made with <span class="text-emerald-600 dark:text-lime-400">⚽</span> and 
          <span class="text-lime-600 dark:text-emerald-400"> 💚</span> by {{ clubIdentity.name }} Team
        </p>
        <p class="text-xs text-gray-400 dark:text-gray-600 mt-2">
          Votre succès, notre passion
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped>
@keyframes subtle-pulse {
  0%, 100% { 
    opacity: 0.6; 
    transform: scale(1); 
  }
  50% { 
    opacity: 0.9; 
    transform: scale(1.05); 
  }
}

@keyframes float {
  0%, 100% { 
    transform: translateY(0px); 
  }
  50% { 
    transform: translateY(-10px); 
  }
}

@keyframes fade-slide-up {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate-pulse {
  animation: subtle-pulse 3s ease-in-out infinite;
}

.animate-float {
  animation: float 6s ease-in-out infinite;
}

.animate-fade-in {
  animation: fade-slide-up 0.8s ease-out forwards;
}

.animate-fade-in-delay-1 {
  animation: fade-slide-up 0.8s ease-out 0.2s forwards;
  opacity: 0;
}

.animate-fade-in-delay-2 {
  animation: fade-slide-up 0.8s ease-out 0.4s forwards;
  opacity: 0;
}

.animate-fade-in-delay-3 {
  animation: fade-slide-up 0.8s ease-out 0.6s forwards;
  opacity: 0;
}

.animate-fade-in-delay-4 {
  animation: fade-slide-up 0.8s ease-out 0.8s forwards;
  opacity: 0;
}

/* Dark mode transitions */
* {
  transition: background-color 0.3s ease, border-color 0.3s ease;
}
</style>

