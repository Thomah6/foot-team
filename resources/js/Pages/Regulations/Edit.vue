<script setup>
import { useForm } from '@inertiajs/vue3'

defineProps({
  rule: Object
})

const form = useForm({
  title: rule.title,
  content: rule.content,
  parent_id: rule.parent_id,
  order: rule.order,
})

function submit() {
  form.put(`/regulations/${rule.id}`)
}
</script>

<template>
  <div class="p-6">
    <h1 class="text-xl font-bold mb-4">Modifier un règlement</h1>

    <form @submit.prevent="submit" class="space-y-4">
      <input v-model="form.title" type="text" class="border p-2 w-full" />
      <textarea v-model="form.content" class="border p-2 w-full"></textarea>
      <input v-model="form.order" type="number" class="border p-2 w-full" />

      <button class="bg-yellow-500 text-white px-4 py-2 rounded">Mettre à jour</button>
    </form>
  </div>
</template>
