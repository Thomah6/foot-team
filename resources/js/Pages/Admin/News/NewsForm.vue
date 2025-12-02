<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  form: Object,
  errors: Object,
  processing: Boolean,
  news: Object,
});

const imagePreview = ref(props.news?.image ? `/storage/${props.news.image}` : null);

const handleFileChange = (event) => {
  const file = event.target.files[0];
  props.form.image = file;

  if (file) {
    const reader = new FileReader();
    reader.onload = (e) => {
      imagePreview.value = e.target.result;
    };
    reader.readAsDataURL(file);
  } else {
    imagePreview.value = props.news?.image ? `/storage/${props.news.image}` : null;
  }
};

watch(() => props.news, (newVal) => {
  imagePreview.value = newVal?.image ? `/storage/${newVal.image}` : null;
});
</script>

<template>
  <div class="space-y-6">

    <!-- IMAGE UPLOAD -->
    <div class="space-y-3">
      
      <div class="space-y-2">
        <label class="font-semibold text-gray-700 dark:text-gray-300">Image</label>

        <!-- Preview -->
        <div v-if="imagePreview"
          class="mt-3 w-full rounded-lg overflow-hidden shadow-md border border-gray-300 dark:border-gray-600">
          <img :src="imagePreview" class="w-full h-64 sm:h-72 object-cover rounded-lg" alt="Aperçu">
        </div>

        <input type="file" accept="image/*" @change="handleFileChange" class="block w-full text-sm text-gray-700 bg-white dark:bg-gray-800 
                     border border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer
                     file:bg-green-600 file:text-white file:border-none file:px-4 file:py-2 file:rounded-lg 
                     hover:file:bg-green-700 transition">

        <div v-if="errors.image" class="text-red-500 dark:text-red-400 text-sm">
          {{ errors.image }}
        </div>

        <p class="text-xs text-gray-500 dark:text-gray-400">
          Taille maximale : 5MB. Formats : JPG, PNG, GIF.
        </p>
      </div>

      <!-- Title -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
          Titre *
        </label>
        <input v-model="form.title" type="text" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               focus:ring-2 focus:ring-citron-500 focus:border-citron-500 dark:focus:ring-citron-600 
               transition-all duration-200" placeholder="Titre de l'actualité" />
        <p v-if="errors.title" class="text-sm text-red-500 dark:text-red-400">{{ errors.title }}</p>
       
      </div>

      <!-- Content -->
      <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
          Contenu *
        </label>
        <textarea v-model="form.content" required class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               min-h-[200px]
               focus:ring-2 focus:ring-citron-500 focus:border-citron-500 dark:focus:ring-citron-600
               transition-all duration-200" placeholder="Contenu de l'actualité..."></textarea>
        <p v-if="errors.content" class="text-sm text-red-500 dark:text-red-400">{{ errors.content }}</p>
        
      </div>

      <!-- Published toggle -->
      <div
        class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
        <input id="published" type="checkbox" v-model="form.is_published" class="h-5 w-5 rounded border-gray-300 dark:border-gray-600
               text-citron-500 focus:ring-citron-500 dark:focus:ring-citron-600
               transition-colors duration-200" />
        <label for="published" class="text-gray-700 dark:text-gray-300 font-medium">
          Publier cette actualité
        </label>
      </div>

    </div>
  </div>
</template>