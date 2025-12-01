<script setup>
import { ref } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import Toast from '@/Shared/Toast.vue'
import ConfirmModalFinance from '@/Components/ConfirmModalFinance.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const montant = ref(0)
const description = ref('')
const showConfirm = ref(false)
const showConfirmLoading = ref(false)


// Toast
const toastVisible = ref(false)
const toastMessage = ref('')
const toastType = ref('success')


function openConfirm() {
  if (!montant.value || !description.value) {
    toastMessage.value = 'Veuillez renseigner le montant et la description.'
    toastType.value = 'error'
    toastVisible.value = true
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


      if (page.props.flash && page.props.flash.success) {
        toastMessage.value = page.props.flash.success
        toastType.value = 'success'
        toastVisible.value = true
        // Delay navigation briefly so the toast is visible to the user
        setTimeout(() => {
          router.visit(route('finances.index'))
        }, 800)
      }
    },
    onFinish: () => {
      showConfirmLoading.value = false
    },
    onError: () => {
      showConfirm.value = false
      toastMessage.value = 'Une erreur est survenue lors de l\enregistrement.'
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
  <div class="flex flex-col gap-4 p-6 bg-white dark:bg-slate-900 border border-neutral-200 dark:border-slate-700 rounded-lg">
    <h2 class="text-xl font-bold text-neutral-900 dark:text-white">Créer une dépense</h2>


    <div class="flex flex-col gap-3">
      <input
        v-model.number="montant"
        type="number"
        min="100"
        class="w-full px-4 py-2 border border-neutral-200 dark:border-slate-700 rounded-lg text-sm bg-white dark:bg-slate-800 text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-slate-400"
        placeholder="Montant"
      />


      <input
        v-model="description"
        type="text"
        class="w-full px-4 py-2 border border-neutral-200 dark:border-slate-700 rounded-lg text-sm bg-white dark:bg-slate-800 text-neutral-900 dark:text-white placeholder-neutral-400 dark:placeholder-slate-400"
        placeholder="Description de la dépense"
      />


      <button
        @click="openConfirm"
        class="w-full h-12 px-4 bg-red-600 dark:bg-red-700 text-white rounded-lg font-bold mt-4 hover:bg-red-700 dark:hover:bg-red-800"
      >
        Enregistrer la dépense
      </button>
    </div>


    <ConfirmModalFinance
      :show="showConfirm"
      :loading="showConfirmLoading"
      title="Confirmer la dépense"
      message="Voulez-vous vraiment enregistrer cette dépense ?"
      @confirm="sendDepense"
      @cancel="cancelDepense"
    />


    <Toast v-model="toastVisible" :message="toastMessage" :type="toastType" />
  </div>
  </AuthenticatedLayout>
</template>
