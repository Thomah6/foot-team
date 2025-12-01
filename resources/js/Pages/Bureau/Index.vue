<script setup>
import { Link } from '@inertiajs/vue3'
import { ref } from 'vue'
import AdminsideBar from '@/Components/AdminsideBar.vue'

const props = defineProps({
  totalStats: Number,
  activePlayers: Number,
  latestStats: Array,
  avatar: String
})

const search = ref('')
</script>

<template>
  <div class="flex flex-col lg:flex-row min-h-screen">
    <!-- Sidebar -->
    <div class="lg:w-64">
      <AdminsideBar />
    </div>

    <!-- Main Content -->
    <div class="flex-1 bg-gradient-to-br from-gray-50 to-white px-4 sm:px-6 lg:px-8 py-6 lg:py-8">
      <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 mb-8">
          <div class="text-center lg:text-left">
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-700 bg-clip-text text-transparent">
              Statistiques et performances
            </h1>
            <p class="text-gray-600 mt-2 text-sm sm:text-base">
              Consultez les performances des joueurs, historiques et classements
            </p>
          </div>
          <div class="flex justify-center lg:justify-end">
            <Link 
              href="/bureau/stats/leaderboards" 
              class="inline-flex items-center gap-2 px-4 sm:px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-semibold rounded-lg shadow-md hover:shadow-lg transition-all duration-200"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              Voir les classements
            </Link>
          </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-8">
          <!-- Card 1 -->
          <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 p-5 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 rounded-lg bg-purple-50">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
              </div>
              <span class="text-xs font-semibold px-2 py-1 rounded-full bg-purple-100 text-purple-600">
                Total
              </span>
            </div>
            <p class="text-sm text-gray-500 mb-2">Nombre d'entrées statistiques</p>
            <p class="text-2xl sm:text-3xl font-bold text-purple-500">{{ props.totalStats }}</p>
            
          </div>

          <!-- Card 2 -->
          <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 p-5 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 rounded-lg bg-blue-50">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-8.581a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0z"/>
                </svg>
              </div>
              <span class="text-xs font-semibold px-2 py-1 rounded-full bg-blue-100 text-blue-600">
                Actifs
              </span>
            </div>
            <p class="text-sm text-gray-500 mb-2">Joueurs avec statistiques</p>
            <p class="text-2xl sm:text-3xl font-bold text-blue-600">{{ props.activePlayers }}</p>
           
          </div>

          <!-- Card 3 -->
          <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 p-5 sm:p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
              <div class="p-3 rounded-lg bg-red-50">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
              </div>
              <span class="text-xs font-semibold px-2 py-1 rounded-full bg-red-100 text-red-600">
                Récent
              </span>
            </div>
            <p class="text-sm text-gray-500 mb-2">Historique récent</p>
            <p class="text-2xl sm:text-3xl font-bold text-red-500">{{ props.latestStats.length }}</p>
           
          </div>
        </div>

        <!-- Latest Stats Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
          <!-- Section Header -->
          <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
              <div class="flex items-center gap-3">
                <div class="p-2 rounded-lg bg-indigo-50">
                  <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
                <h3 class="text-lg sm:text-xl font-bold text-gray-900">Dernières statistiques</h3>
              </div>
              
            </div>
          </div>

          <!-- Stats List -->
          <div class="divide-y divide-gray-100">
            <div v-if="props.latestStats.length === 0" class="py-12 text-center">
              <div class="mx-auto w-16 h-16 mb-4 rounded-full bg-gray-100 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
              </div>
              <p class="text-gray-500 font-medium">Aucune statistique enregistrée</p>
              <p class="text-gray-400 text-sm mt-1">Les statistiques apparaîtront ici</p>
            </div>

            <!-- Stats Items -->
            <div v-for="s in props.latestStats" :key="s.id" 
                 class="px-6 py-5 hover:bg-gray-50/50 transition-colors duration-200">
              <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <!-- Avatar and Name -->
                <div class="flex items-center gap-4 flex-1">
                  <div class="relative">
                    <img
                      :src="s.user?.avatar ? '/storage/' + s.user.avatar : `https://ui-avatars.com/api/?name=${encodeURIComponent(s.user?.name || 'User')}&color=7F9CF5&background=EBF4FF`"
                      class="w-14 h-14 rounded-xl object-cover border-2 border-white shadow-sm"
                      :alt="s.user.name"
                      @error="(e) => { e.target.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(s.user?.name || 'User')}&color=7F9CF5&background=EBF4FF` }"
                    />
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-gradient-to-r from-green-400 to-emerald-500 border-2 border-white flex items-center justify-center">
                      <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                      </svg>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-bold text-gray-900 truncate">{{ s.user.name }}</p>
                    <div class="flex flex-wrap items-center gap-3 mt-2">
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        {{ s.goals }} buts
                      </span>
                      <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ s.assists }} passes
                      </span>
                      <span class="text-xs text-gray-500">
                        {{ new Date(s.date).toLocaleDateString('fr-FR', { weekday: 'short', day: 'numeric', month: 'short' }) }}
                      </span>
                    </div>
                  </div>
                </div>

                <!-- Action Button -->
                <div class="sm:flex-shrink-0">
                  <Link 
                    :href="`/bureau/stats/members/${s.user.id}/stats`"
                    class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-gray-900 to-gray-700 hover:from-gray-800 hover:to-gray-600 text-white font-medium rounded-lg shadow-sm hover:shadow transition-all duration-200 text-sm"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Voir le profil
                  </Link>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div v-if="props.latestStats.length > 0" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <div class="flex justify-between items-center text-sm text-gray-500">
              <p>Dernière mise à jour : {{ new Date().toLocaleDateString('fr-FR') }}</p>
             
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
