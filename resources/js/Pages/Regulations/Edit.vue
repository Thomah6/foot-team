<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { useForm } from '@inertiajs/vue3'

const props = defineProps({
  content: {
    type: Object,
    required: true
  },
  titles: {
    type: Array,
    required: true
  }
})

const form = useForm({
  regulation_id: props.content.regulation_id,
  sub_number: props.content.sub_number || '',
  content: props.content.content || ''
})

function submit() {
  form.put(`/regulations/content/${props.content.id}`, {
    onSuccess: () => {
      $inertia.visit('/regulations')
    }
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
      <div class="w-full max-w-xl bg-white rounded-xl shadow-lg p-6 sm:p-8">
        <h1 class="text-2xl font-bold mb-6 text-green-600 text-center">Modifier un sous-point</h1>

        <form @submit.prevent="submit" class="space-y-4">

          <!-- Sélection du titre -->
          <!-- <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre parent</label>
            <select v-model="form.regulation_id" class="w-full border rounded-lg p-2">
              <option disabled value="">Choisir un titre</option>
              <option v-for="t in props.titles" :key="t.id" :value="t.id">{{ t.title }}</option>
            </select>
            <div v-if="form.errors.regulation_id" class="text-red-500 text-sm mt-1">{{ form.errors.regulation_id }}</div>
          </div> -->

          <!-- Numéro du sous-point -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Numéro du sous-point</label>
            <input type="text" v-model="form.sub_number" placeholder="Ex: 1.1"
                   class="w-full border rounded-lg p-2" />
            <div v-if="form.errors.sub_number" class="text-red-500 text-sm mt-1">{{ form.errors.sub_number }}</div>
          </div>

          <!-- Contenu -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Contenu</label>
            <textarea v-model="form.content" rows="5"
                      class="w-full border rounded-lg p-2 resize-none"></textarea>
            <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">{{ form.errors.content }}</div>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-green-500 text-white py-2 rounded-lg"
                    :disabled="form.processing">
              <span v-if="form.processing">Sauvegarde...</span>
              <span v-else>Mettre à jour</span>
            </button>

            <button type="button" class="flex-1 bg-red-500 text-white py-2 rounded-lg"
                    @click="$inertia.visit('/regulations')">
              Annuler
            </button>
          </div>

        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
