<script setup>
import { useForm } from '@inertiajs/vue3'

// 1. On définit les props de manière standard
const props = defineProps({
  rule: {
    type: Object,
    required: true
  },
})



// 2. On initialise le formulaire avec les données reçues
const form = useForm({
  title: props.rule.title || '',
  content: props.rule.content || '',
  parent_id: props.rule.parent_id || null,
  order: props.rule.order || 0,
})

function submit() {
  // 3. On utilise l'ID qui vient des props pour l'URL
  form.put(`/regulations/${props.rule.id}`, {
    onSuccess: () => {
        // Optionnel : actions après succès
    }
  })
}
</script>

<template>

  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Modifier un règlement</h1>
    <h2 class="text-xl font-bold mb-4">Modifier : {{ form.title }}</h2>
    <form @submit.prevent="submit" class="space-y-4">
      <!-- Champ titre -->
      <div>
        <label class="block text-sm font-medium">Titre</label>
        <input v-model="form.title" type="text" class="border p-2 w-full rounded" />
        <div v-if="form.errors.title" class="text-red-500 text-sm mt-1">{{ form.errors.title }}</div>
      </div>

      <!-- Champ contenu -->
      <div>
        <label class="block text-sm font-medium">Contenu</label>
        <textarea v-model="form.content" rows="4" class="border p-2 w-full rounded"></textarea>
        <div v-if="form.errors.content" class="text-red-500 text-sm mt-1">{{ form.errors.content }}</div>
      </div>

      <!-- Champ parent_id -->
      <div>
        <label class="block text-sm font-medium">ID Parent (Optionnel)</label>
        <input v-model="form.parent_id" type="number" class="border p-2 w-full rounded" />
         <div v-if="form.errors.parent_id" class="text-red-500 text-sm mt-1">{{ form.errors.parent_id }}</div>
      </div>

      <!-- Champ ordre -->
      <div>
        <label class="block text-sm font-medium">Ordre</label>
        <input v-model="form.order" type="number" class="border p-2 w-full rounded" />
         <div v-if="form.errors.order" class="text-red-500 text-sm mt-1">{{ form.errors.order }}</div>
      </div>

      <!-- Bouton -->
      <button 
        type="submit" 
        class="bg-yellow-500 text-black px-4 py-2 rounded hover:bg-yellow-400" 
        :disabled="form.processing"
      >
        <span v-if="form.processing">Sauvegarde...</span>
        <span v-else>Mettre à jour</span>
      </button>
    </form>
  </div>
</template>
