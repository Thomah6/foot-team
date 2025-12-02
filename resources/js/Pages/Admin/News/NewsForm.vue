<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  form: Object,
  errors: Object,
  processing: Boolean,
  news: Object, // On passe la news actuelle pour l'aperçu
});

// Pour la prévisualisation de l'image uploadée
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

// Si la news change dynamiquement, on met à jour l'aperçu
watch(() => props.news, (newVal) => {
  imagePreview.value = newVal?.image ? `/storage/${newVal.image}` : null;
});
</script>

<template>
  <div class="space-y-6">

    <!-- IMAGE UPLOAD -->
    <div class="space-y-2">
      <label class="flex items-center gap-2 text-sm font-semibold text-gray-900 dark:text-white">
        <i class="fas fa-image text-emerald-600"></i>
        Image
      </label>

      <!-- Preview -->
      <div v-if="imagePreview" class="mt-3 w-full rounded-xl overflow-hidden shadow-lg border-2 border-emerald-200 dark:border-emerald-800 bg-gradient-to-br from-emerald-50 to-lime-50 dark:from-gray-800 dark:to-gray-700">
        <img :src="imagePreview" class="w-full h-48 sm:h-60 object-cover rounded-lg" alt="Aperçu">
      </div>

      <input type="file" accept="image/*" @change="handleFileChange" class="block w-full text-xs sm:text-sm text-gray-700 dark:text-gray-300
             bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer
             file:bg-gradient-to-r file:from-emerald-600 file:to-lime-600 file:text-white file:border-none file:px-4 file:py-2.5 file:rounded-lg 
             hover:file:from-emerald-700 hover:file:to-lime-700 transition file:font-medium file:cursor-pointer">

      <div v-if="errors.image" class="text-red-500 text-xs sm:text-sm mt-1 flex items-center gap-1">
        <i class="fas fa-exclamation-circle"></i>
        {{ errors.image }}
      </div>
    </div>

    <!-- Title -->
    <div class="space-y-1">
      <label class="text-sm font-semibold text-gray-900 dark:text-white">
        Titre
      </label>
      <input v-model="form.title" type="text" name="title"
        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               placeholder:text-gray-400 dark:placeholder:text-gray-500
               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 transition text-sm sm:text-base" 
        placeholder="Titre de l'actualité" />

      <p v-if="errors.title" class="text-xs sm:text-sm text-red-500 mt-1 flex items-center gap-1">
        <i class="fas fa-exclamation-circle"></i>
        {{ errors.title }}
      </p>
    </div>

    <!-- Content -->
    <div class="space-y-1">
      <label class="text-sm font-semibold text-gray-900 dark:text-white">
        Contenu
      </label>
      <textarea v-model="form.content" name="content"
        class="w-full px-3 sm:px-4 py-2.5 sm:py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600
               bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100
               placeholder:text-gray-400 dark:placeholder:text-gray-500
               min-h-32 sm:min-h-40
               focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 dark:focus:border-emerald-400 transition text-sm sm:text-base">
      </textarea>
      <p v-if="errors.content" class="text-xs sm:text-sm text-red-500 mt-1 flex items-center gap-1">
        <i class="fas fa-exclamation-circle"></i>
        {{ errors.content }}
      </p>
    </div>

    <!-- Published toggle -->
    <div class="flex items-center gap-3 p-3 sm:p-4 bg-emerald-50 dark:bg-emerald-900/20 rounded-lg border border-emerald-200 dark:border-emerald-800">
      <input id="published" type="checkbox" v-model="form.is_published"
        class="h-4 w-4 sm:h-5 sm:w-5 rounded border-2 border-gray-300 dark:border-gray-600
               text-emerald-600 focus:ring-emerald-500 cursor-pointer" />
      <label for="published" class="text-sm sm:text-base text-gray-900 dark:text-gray-100 cursor-pointer font-medium flex items-center gap-2">
        <i class="fas fa-check-circle text-emerald-600"></i>
        Publier cette actualité
      </label>
    </div>

  </div>
</template>
