<script setup>
defineProps({
  rule: Object
})
</script>
<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 p-4 md:p-6">
      <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
          <a href="/regulations" class="text-citron-600 dark:text-citron-400 hover:text-citron-800 dark:hover:text-citron-300 text-sm font-semibold flex items-center gap-1 mb-4 transition-colors">
            <i class="fas fa-arrow-left"></i> Retour aux règlements
          </a>
          
          <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-citron-100 dark:border-citron-900 p-6">
            <div class="flex justify-between items-start mb-6">
              <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-citron-50 mb-2">📋 Détail du règlement</h1>
                <div class="flex items-center gap-2">
                  <span class="bg-citron-100 dark:bg-citron-900/50 text-citron-700 dark:text-citron-300 text-xs font-bold px-3 py-1 rounded-full">
                    ID: {{ rule.id }}
                  </span>
                  <span class="bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-bold px-3 py-1 rounded-full">
                    Ordre: {{ rule.order }}
                  </span>
                </div>
              </div>
              
              <div class="flex flex-col sm:flex-row gap-2">
                <button 
                  v-if="$page.props.auth.user.role === 'admin'" 
                  class="bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white px-4 py-2 rounded-lg transition font-semibold flex items-center gap-2 text-sm"
                  @click="$inertia.visit(`/regulations/${rule.id}/edit`)"
                >
                  <i class="fas fa-edit"></i>
                  Modifier
                </button>

                <button 
                  v-if="$page.props.auth.user.role === 'admin'" 
                  class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-lg transition font-semibold flex items-center gap-2 text-sm"
                  @click="$inertia.delete(`/regulations/${rule.id}`)"
                >
                  <i class="fas fa-trash"></i>
                  Supprimer
                </button>
              </div>
            </div>

            <!-- Détails du règlement -->
            <div class="space-y-6">
              <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-citron-100 mb-3 flex items-center gap-2">
                  <i class="fas fa-tag text-citron-500"></i>
                  Titre
                </h2>
                <p class="text-gray-900 dark:text-citron-50 text-xl font-bold bg-citron-50 dark:bg-citron-900/30 p-4 rounded-lg border border-citron-200 dark:border-citron-800">
                  {{ rule.title }}
                </p>
              </div>

              <div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-citron-100 mb-3 flex items-center gap-2">
                  <i class="fas fa-align-left text-citron-500"></i>
                  Contenu
                </h2>
                <div class="bg-white dark:bg-gray-800 p-4 sm:p-5 rounded-lg border border-gray-300 dark:border-gray-700 shadow-sm">
                  <p class="text-gray-700 dark:text-citron-200 leading-relaxed whitespace-pre-line">
                    {{ rule.content || 'Aucun contenu spécifié' }}
                  </p>
                </div>
              </div>

              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <h2 class="text-lg font-semibold text-gray-800 dark:text-citron-100 mb-3 flex items-center gap-2">
                    <i class="fas fa-sitemap text-citron-500"></i>
                    Relation parent
                  </h2>
                  <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-300 dark:border-gray-700">
                    <p class="text-gray-700 dark:text-citron-200">
                      <span class="font-semibold">Parent :</span> {{ rule.parent_id ? `Règlement #${rule.parent_id}` : 'Aucun (racine)' }}
                    </p>
                  </div>
                </div>

                <div>
                  <h2 class="text-lg font-semibold text-gray-800 dark:text-citron-100 mb-3 flex items-center gap-2">
                    <i class="fas fa-sort-numeric-down text-citron-500"></i>
                    Ordre d'affichage
                  </h2>
                  <div class="bg-gray-50 dark:bg-gray-800 p-3 rounded-lg border border-gray-300 dark:border-gray-700">
                    <p class="text-gray-700 dark:text-citron-200">
                      <span class="font-semibold">Position :</span> {{ rule.order || 'Non spécifié' }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Informations supplémentaires -->
        <div class="bg-citron-50 dark:bg-citron-900/20 rounded-xl border border-citron-200 dark:border-citron-800 p-4">
          <h3 class="font-semibold text-gray-800 dark:text-citron-100 mb-2 flex items-center gap-2">
            <i class="fas fa-info-circle text-citron-500"></i>
            Informations
          </h3>
          <p class="text-sm text-gray-600 dark:text-citron-300">
            Ce règlement fait partie du système de régulation de l'organisation. Seuls les administrateurs peuvent le modifier ou le supprimer.
          </p>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>