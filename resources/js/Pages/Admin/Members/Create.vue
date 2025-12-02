<script setup>
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
  pseudo: '',
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  role: 'simple',
  position: '',
  is_active: false,
});

const submit = () => {
  form.post(route('admin.members.store'));
};
</script>

<template>
  <div class="max-w-2xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Créer un Nouveau Membre
          </h2>
          <p class="text-gray-600 dark:text-gray-400 mt-1">
            Ajoutez un nouveau membre à votre équipe
          </p>
        </div>

        <Link :href="route('admin.members.index')"
          class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
          <i class="fas fa-arrow-left"></i>
          Retour
        </Link>
      </div>
    </div>

    <div class="rounded-xl bg-white dark:bg-gray-800 p-6 shadow-lg border border-gray-200 dark:border-gray-700">
      <form @submit.prevent="submit" class="space-y-6">

        <!-- Pseudo -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Pseudo *</label>
          <input v-model="form.pseudo" type="text" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
          <p v-if="form.errors.pseudo" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.pseudo }}</p>
        </div>

        <!-- Name -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Nom *</label>
          <input v-model="form.name" type="text" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
          <p v-if="form.errors.name" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email *</label>
          <input v-model="form.email" type="email" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
          <p v-if="form.errors.email" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.email }}</p>
        </div>

        <!-- Position -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
          <input v-model="form.position" type="text"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
          <p v-if="form.errors.position" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.position }}</p>
        </div>

        <!-- Role -->
        <div>
          <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role *</label>
          <select v-model="form.role" required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all">
            <option value="simple">Simple</option>
            <option value="bureau">Bureau</option>
            <option value="admin">Administrateur</option>
          </select>
          <p v-if="form.errors.role" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.role }}</p>
        </div>

        <!-- Password -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-4">Mot de Passe *</h3>

          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Mot de Passe</label>
              <input v-model="form.password" type="password" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
              <p v-if="form.errors.password" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.password }}</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirmation</label>
              <input v-model="form.password_confirmation" type="password" required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all" />
              <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-500 dark:text-red-400">
                {{ form.errors.password_confirmation }}
              </p>
            </div>
          </div>
        </div>

        <!-- Active Status -->
        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
          <input :checked="form.is_active" @change="form.is_active = $event.target.checked" type="checkbox"
            class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 text-citron-500 focus:ring-citron-500 transition-colors" />
          <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Compte Actif</label>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
          <a href="/admin/members" class="flex-1 border border-gray-300 dark:border-gray-600 rounded-lg py-3 text-center text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
            Annuler
          </a>

          <button type="submit" :disabled="form.processing"
            class="flex-1 bg-citron-500 hover:bg-citron-600 text-white rounded-lg py-3 font-medium transition-colors disabled:opacity-50 flex items-center justify-center gap-2">
            <span v-if="form.processing" class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            {{ form.processing ? 'Création...' : 'Créer' }}
          </button>
        </div>

      </form>
    </div>
  </div>
</template>