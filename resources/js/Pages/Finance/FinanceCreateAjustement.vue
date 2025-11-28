<script setup>
import { ref, watch, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Toast from '@/Shared/Toast.vue'
import ConfirmModalFinance from '@/Components/ConfirmModalFinance.vue'

const montant = ref(0)
const sens = ref('credit')
const description = ref('')

const showConfirm = ref(false)

// Toast
const page = usePage()
const toastVisible = ref(false)
const toastMessage = ref('')
const toastType = ref('success')

function showToast(message, type = 'success') {
  toastMessage.value = message
  toastType.value = type
  toastVisible.value = true
}

watch(
  () => page.props.flash && page.props.flash.success,
  (val) => {
    if (val) showToast(val, 'success')
  }
)
watch(
  () => page.props.flash && page.props.flash.error,
  (val) => {
    if (val) showToast(val, 'error')
  }
)

onMounted(() => {
  const p = page.props
  if (p.flash && p.flash.success) showToast(p.flash.success, 'success')
  if (p.flash && p.flash.error) showToast(p.flash.error, 'error')
})

// 1er clic: ouvrir le modal
function openConfirm() {
  showConfirm.value = true
}

// ConfirmModal: confirmer => envoyer la requête
function confirmSubmit() {
  router.post(
    route('finances.storeAjustement'),
    {
      montant: montant.value,
      sens: sens.value,
      description: description.value,
    },
    {
      onSuccess: (page) => {
        showConfirm.value = false
        montant.value = 0
        sens.value = 'credit'
        description.value = ''
        // Show toast if flash is provided by the server
        if (page && page.props && page.props.flash && page.props.flash.success) {
          showToast(page.props.flash.success, 'success')
        }
        // Delay navigation briefly so the toast is visible to the user
        setTimeout(() => {
          router.visit(route('finances.index'))
        }, 800)
      },
      onError: () => {
        showConfirm.value = false
      },
    }
  )
}

function cancelSubmit() {
  showConfirm.value = false
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="w-full px-4 py-6 sm:px-6 lg:px-8">
      <div
        class="max-w-xl mx-auto bg-white border border-neutral-200 rounded-lg p-6 space-y-4"
      >
        <h1 class="text-2xl font-bold text-neutral-900">
          Ajustement manuel de la caisse
        </h1>
        <p class="text-sm text-neutral-600">
          Cette opération modifie directement le solde de la caisse. Réservé à
          l’administrateur.
        </p>

        <div class="space-y-3">
          <div>
            <label class="block text-sm font-medium mb-1"
              >Sens de l’ajustement</label
            >
            <div class="flex gap-4">
              <label class="flex items-center gap-2 text-sm">
                <input type="radio" value="credit" v-model="sens" />
                <span>Crédit (+)</span>
              </label>
              <label class="flex items-center gap-2 text-sm">
                <input type="radio" value="debit" v-model="sens" />
                <span>Débit (-)</span>
              </label>
            </div>
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Montant</label>
            <input
              type="number"
              v-model.number="montant"
              class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm"
              placeholder="Ex: 20000"
            />
          </div>

          <div>
            <label class="block text-sm font-medium mb-1">Description</label>
            <input
              type="text"
              v-model="description"
              class="w-full border border-neutral-200 rounded-lg px-3 py-2 text-sm"
              placeholder='Ex: "Ajustement +20 000 F erreur de saisie"'
            />
          </div>

          <button
            @click="openConfirm"
            type="button"
            class="mt-4 w-full h-11 bg-blue-600 text-white text-sm font-semibold rounded-lg hover:bg-blue-700"
          >
            Enregistrer l’ajustement
          </button>
        </div>
      </div>
    </div>

    <ConfirmModalFinance
      :show="showConfirm"
      title="Confirmer l’ajustement"
      :message="`Confirmer cet ajustement de ${montant} F CFA en ${sens === 'credit' ? 'crédit (+)' : 'débit (-)'} ?`"
      @confirm="confirmSubmit"
      @cancel="cancelSubmit"
    />

    <Toast v-model="toastVisible" :message="toastMessage" :type="toastType" />
  </AuthenticatedLayout>
</template>
