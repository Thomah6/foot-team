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
    <div 
      class="min-h-screen flex items-center justify-center px-4 bg-cover bg-center"
      style="background-image: url('https://static.vecteezy.com/system/resources/thumbnails/046/408/525/small/football-soccer-grass-field-stadium-with-light-for-outdoor-sport-photo.jpg');"
    >
      <!-- Bloc bleu translucide avec texte blanc -->
      <div class="w-full max-w-xl bg-blue-600/40 backdrop-blur-md border border-blue-300/40 rounded-xl shadow-lg p-6 sm:p-8 text-white">
        <h1 class="text-2xl font-bold mb-6 text-center">Modifier un sous-point</h1>

        <form @submit.prevent="submit" class="space-y-4">
          <!-- Numéro du sous-point -->
          <div>
            <label class="block text-sm font-medium mb-1">Numéro du sous-point</label>
            <input type="text" v-model="form.sub_number" placeholder="Ex: 1.1"
                   class="w-full border rounded-lg p-2 text-black" />
            <div v-if="form.errors.sub_number" class="text-red-300 text-sm mt-1">{{ form.errors.sub_number }}</div>
          </div>

          <!-- Contenu -->
          <div>
            <label class="block text-sm font-medium mb-1">Contenu</label>
            <textarea v-model="form.content" rows="5"
                      class="w-full border rounded-lg p-2 resize-none text-black"></textarea>
            <div v-if="form.errors.content" class="text-red-300 text-sm mt-1">{{ form.errors.content }}</div>
          </div>

          <!-- Boutons -->
          <div class="flex gap-4">
            <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg"
                    :disabled="form.processing">
              <span v-if="form.processing">Sauvegarde...</span>
              <span v-else>Mettre à jour</span>
            </button>

            <button type="button" class="bg-red-800 flex-1 text-white py-2 rounded-lg"
                    @click="$inertia.visit('/regulations')">
              Annuler
            </button>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>


