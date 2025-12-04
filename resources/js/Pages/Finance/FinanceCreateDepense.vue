<script setup>
import { ref, computed } from 'vue'
import { router, usePage, Link } from '@inertiajs/vue3'
import ConfirmModalFinance from '@/Components/ConfirmModalFinance.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
    soldeTotal: {
        type: Number,
        required: true
    }
})

const montant = ref(0)
const description = ref('')
const showConfirm = ref(false)
const showConfirmLoading = ref(false)

const isSoldeInsuffisant = computed(() => props.soldeTotal <= 0)




function openConfirm() {
  if (isSoldeInsuffisant.value) {
    return
  }
  if (!montant.value || !description.value) {
    return
  }
  showConfirm.value = true
}


function sendDepense() {
  showConfirmLoading.value = true
  router.post(route('finances.storeDepense'), {
    montant: montant.value,
    description: description.value,
  }, {
    onSuccess: (page) => {
      showConfirm.value = false
      montant.value = 0
      description.value = ''
      setTimeout(() => {
        router.visit(route('finances.index'))
      }, 1500)
    },
    onFinish: () => {
      showConfirmLoading.value = false
    },
    onError: (errors) => {
      showConfirm.value = false
      // Display backend validation error first if it exists
      if(page.props.flash && page.props.flash.error) {
        toastMessage.value = page.props.flash.error;
      } else {
        toastMessage.value = 'Une erreur est survenue lors de l\enregistrement.'
      }
      toastType.value = 'error'
      toastVisible.value = true
      showConfirmLoading.value = false
    }
  })
}


function cancelDepense() {
  showConfirm.value = false
}
</script>


<template>
    <AuthenticatedLayout>
        <div class="relative min-h-screen bg-gradient-to-br from-slate-50 via-lime-50 to-emerald-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iIzExMTExMSIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjMTExMTExIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')] opacity-20 dark:bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNjAiIGhlaWdodD0iNjAiIHZpZXdCb3g9IjAgMCA2MCA2MCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMwIDBWMzAiIHN0cm9rZT0iI0ZGRkZGRiIgc3Ryb2tlLW9wYWNpdHk9IjAuMDMiIHN0cm9rZS13aWR0aD0iMSIvPgo8cGF0aCBkPSJNMCAzMEgzMCIgc3Ryb2tlPSIjRkZGRkZGIiBzdHJva2Utb3BhY2l0eT0iMC4wMyIgc3Ryb2tlLXdpZHRoPSIxIi8+Cjwvc3ZnPgo=')]"></div>
            
            <div class="relative max-w-2xl mx-auto px-4 py-8">
                <!-- Header -->
                <div class="mb-8">
                    <Link :href="route('finances.index')"
                          class="inline-flex items-center gap-2 text-lime-600 dark:text-emerald-400 hover:text-lime-700 dark:hover:text-emerald-300 mb-6 transition-colors">
                        <i class="fas fa-arrow-left"></i>
                        Retour à la caisse
                    </Link>
                    
                    <h1 class="text-3xl font-black text-gray-900 dark:text-white mb-2">
                        💸 NOUVELLE DÉPENSE
                    </h1>
                    <p class="text-gray-600 dark:text-gray-400">
                        Enregistre une sortie d'argent avec responsabilité
                    </p>
                </div>

                <!-- Form Card -->
                <div class="relative overflow-hidden rounded-2xl bg-white/95 dark:bg-gray-900/95 backdrop-blur-sm border-2 border-red-200 dark:border-red-900/50 p-6 shadow-xl">
                    <div class="absolute top-0 right-0 w-24 h-24 bg-gradient-to-bl from-red-400/10 to-red-600/10 rounded-full -translate-y-12 translate-x-12"></div>
                    
                    <div class="space-y-6 relative z-10">
                        <!-- Amount -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">
                                MONTANT DE LA DÉPENSE
                            </label>
                            <div class="relative">
                                <input
                                    v-model.number="montant"
                                    type="number"
                                    min="100"
                                    class="w-full px-4 py-3 pl-12 bg-gradient-to-r from-white to-red-50/50 dark:from-gray-800 dark:to-red-900/10 border-2 border-red-200 dark:border-red-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-red-400 dark:focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900/30 outline-none transition-all"
                                    placeholder="Ex: 5000"
                                />
                                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-red-500 dark:text-red-400">
                                    <i class="fas fa-coins"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-bold text-gray-900 dark:text-white mb-2">
                                DESCRIPTION
                            </label>
                            <input
                                v-model="description"
                                type="text"
                                maxlength="150"
                                class="w-full px-4 py-3 bg-gradient-to-r from-white to-red-50/50 dark:from-gray-800 dark:to-red-900/10 border-2 border-red-200 dark:border-red-800/50 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:border-red-400 dark:focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-900/30 outline-none transition-all"
                                placeholder="Pourquoi cette dépense ?"
                            />
                        </div>
                        
                        <!-- Warning Message -->
                        <div v-if="isSoldeInsuffisant" class="!mt-8 p-4 rounded-xl bg-red-100 dark:bg-red-900/30 border border-red-300 dark:border-red-800">
                            <p class="text-center text-red-800 dark:text-red-200 font-bold">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Le solde total est insuffisant pour enregistrer une nouvelle dépense.
                            </p>
                        </div>


                        <!-- Action Buttons -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-4">
                            <Link
                                :href="route('finances.index')"
                                class="flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-800 dark:to-gray-900 border-2 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-300 rounded-xl font-bold hover:bg-gray-200 dark:hover:bg-gray-800 transition-all"
                            >
                                <i class="fas fa-times"></i>
                                ANNULER
                            </Link>

                            <button
                                @click="openConfirm"
                                :disabled="isSoldeInsuffisant"
                                class="flex items-center justify-center gap-2 py-3 px-4 bg-gradient-to-r from-red-600 to-red-700 dark:from-red-500 dark:to-red-600 text-white rounded-xl font-bold shadow-lg hover:shadow-red-500/30 transform hover:scale-[1.02] active:scale-95 transition-all"
                                :class="{ 'opacity-50 cursor-not-allowed': isSoldeInsuffisant }"
                            >
                                <i class="fas fa-check-circle"></i>
                                ENREGISTRER
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <ConfirmModalFinance
                :show="showConfirm"
                :loading="showConfirmLoading"
                title="⚠️ CONFIRMER LA DÉPENSE"
                message="Cette dépense sera soustraite du solde. Confirmes-tu ?"
                @confirm="sendDepense"
                @cancel="cancelDepense"
            />


        </div>
    </AuthenticatedLayout>
</template>
