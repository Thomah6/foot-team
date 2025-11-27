<script setup>
import { router } from "@inertiajs/vue3";

defineProps({
  news: Array
});

const deleteNews = (id) => {
  if (confirm("Supprimer cette actualité ?")) {
    router.delete(`/admin/news/${id}`);
  }
};

const togglePublish = (id) => {
  router.patch(`/admin/news/${id}/toggle-banner`);
};
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold mb-4">Actualités</h1>

    <div class="mb-4">
      <a
        href="/admin/news/create"
        class="bg-green-600 text-white px-4 py-2 rounded"
      >
        Nouvelle actualité
      </a>
    </div>

    <table class="w-full border-collapse">
      <thead>
        <tr class="bg-gray-100 text-left">
          <th class="p-2 border">Titre</th>
          <th class="p-2 border">Auteur</th>
          <th class="p-2 border">Publié</th>
          <th class="p-2 border">Actions</th>
        </tr>
      </thead>

      <tbody>
        <tr
          v-for="item in news"
          :key="item.id"
          class="border-b"
        >
          <td class="p-2 border">{{ item.title }}</td>
          <td class="p-2 border">{{ item.user?.name }}</td>
          <td class="p-2 border">
            <span
              :class="item.is_published ? 'text-green-600' : 'text-red-600'"
            >
              {{ item.is_published ? "Oui" : "Non" }}
            </span>
          </td>

          <td class="p-2 border space-x-2">
            <a
              :href="`/admin/news/${item.id}/edit`"
              class="text-blue-600 hover:underline"
            >
              Modifier
            </a>

            <button
              @click="togglePublish(item.id)"
              class="text-yellow-600"
            >
              {{ item.is_published ? "Dépublier" : "Publier" }}
            </button>

            <button
              @click="deleteNews(item.id)"
              class="text-red-600"
            >
              Supprimer
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
