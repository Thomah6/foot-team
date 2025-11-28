<script setup>
const props = defineProps({
    form: Object,
    errors: Object,
    processing: Boolean
});
</script>

<template>
  <div class="space-y-4">

    <div>
      <label class="block font-medium">Titre</label>
      <input
        v-model="form.title"
        type="text"
        class="w-full border rounded p-2"
      />
      <div class="text-red-500 text-sm">{{ errors.title }}</div>
    </div>

    <div>
      <label class="block font-medium">Contenu</label>
      <textarea
        v-model="form.content"
        class="w-full border rounded p-2 min-h-[150px]"
      ></textarea>
      <div class="text-red-500 text-sm">{{ errors.content }}</div>
    </div>

    <div class="flex items-center gap-2">
      <input
        id="published"
        type="checkbox"
        v-model="form.is_published"
      />
      <label for="published">Publier cette actualité</label>
    </div>

  </div>
</template>
