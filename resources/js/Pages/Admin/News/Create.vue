<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";
import Button from '@/Components/Button.vue';
import { ref } from "vue";

const imagePreview = ref(null);

const form = useForm({
  title: "",
  content: "",
  is_published: false,
  image: null
});

const handleFile = (e) => {
  const file = e.target.files[0];
  form.image = file;

  if (file) {
    imagePreview.value = URL.createObjectURL(file);
  } else {
    imagePreview.value = null;
  }
};
</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-100 dark:bg-gray-900 p-4">
    <div class="flex-1 flex justify-center items-start">
      <div class="w-full max-w-3xl bg-white dark:bg-gray-900 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 flex flex-col flex-1">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fas fa-pen-fancy text-emerald-600"></i>
            Créer une actualité
          </h1>
          <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Ajoutez une nouvelle actualité au club</p>
        </div>

        <!-- Formulaire -->
        <form
          @submit.prevent="form.post('/admin/news', { forceFormData: true })"
          class="space-y-6 flex flex-col flex-1 overflow-auto"
        >
          <NewsForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
          />

          <!-- Submit -->
          <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-auto pt-6 border-t border-gray-200 dark:border-gray-700">
            <Button :href="route('admin.news.index')" variant="secondary" size="md">
              Annuler
            </Button>
            <Button type="submit" :disabled="form.processing" variant="primary" size="md">
              <template #icon>
                <i class="fas fa-save"></i>
              </template>
              {{ form.processing ? 'Enregistrement...' : 'Enregistrer' }}
            </Button>
          </div>

        </form>
      </div>
    </div>
  </div>
</template>
