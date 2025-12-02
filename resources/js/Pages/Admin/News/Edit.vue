<script setup>
import { useForm } from "@inertiajs/vue3";
import NewsForm from "./NewsForm.vue";
import Button from '@/Components/Button.vue';

const props = defineProps({
  news: Object,
});

const form = useForm({
  title: props.news?.title || '',
  content: props.news?.content || '',
  is_published: props.news?.is_published ?? false,
  image: null,
});


const submit = () => {
  form.transform((data) => ({
    ...data,
  })).put(`/admin/news/${props.news.id}`, {
    forceFormData: true,
  });
};


</script>

<template>
  <div class="min-h-screen flex flex-col bg-gray-50 dark:bg-gray-900 p-4">
    <div class="flex-1 flex justify-center items-start w-full">
      <div class="w-full max-w-3xl bg-gray-50 dark:bg-gray-900 p-6 rounded-xl shadow-md flex flex-col flex-1">

        <!-- Header -->
        <div class="mb-6 sm:mb-8">
          <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
            <i class="fas fa-edit text-emerald-600"></i>
            Modifier l'actualité
          </h1>
          <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-2">Mettez à jour les informations de l'actualité</p>
        </div>

        <!-- Formulaire -->
        <form @submit.prevent="submit" class="space-y-6 flex flex-col flex-1 overflow-auto">
          <NewsForm
            :form="form"
            :errors="form.errors"
            :processing="form.processing"
            :news="props.news"
          />

          <!-- Submit -->
          <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 mt-auto pt-6 border-t border-gray-200 dark:border-gray-700">
            <Button :href="route('admin.news.index')" variant="secondary" size="md">
              Annuler
            </Button>
            <Button type="submit" :disabled="form.processing" variant="primary" size="md">
              <template #icon>
                <i class="fas fa-check"></i>
              </template>
              {{ form.processing ? 'Mise à jour...' : 'Mettre à jour' }}
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>
