<script setup>
const props = defineProps({
  form: Object,
  errors: Object,
  processing: Boolean,
});
</script>

<template>
  <div class="space-y-6">

    <!-- Title -->
    <div class="space-y-1">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Titre
      </label>

      <input
        v-model="form.title"
        type="text"
        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      />

      <p v-if="errors.title" class="text-sm text-red-500">
        {{ errors.title }}
      </p>
    </div>

    <!-- Content -->
    <div class="space-y-1">
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
        Contenu
      </label>

      <textarea
        v-model="form.content"
        class="w-full px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-700
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               min-h-[150px]
               focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition"
      ></textarea>

      <p v-if="errors.content" class="text-sm text-red-500">
        {{ errors.content }}
      </p>
    </div>

    <!-- Published toggle -->
    <div class="flex items-center gap-3">
      <input
        id="published"
        type="checkbox"
        v-model="form.is_published"
        class="h-4 w-4 rounded border-gray-300 dark:border-gray-600
               text-blue-600 focus:ring-blue-500"
      />
      <label for="published" class="text-gray-700 dark:text-gray-300">
        Publier cette actualité
      </label>
    </div>

  </div>
</template>
