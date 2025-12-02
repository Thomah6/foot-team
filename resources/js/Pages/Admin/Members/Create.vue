<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

// Aucun props ici, car on ne reçoit rien pour la création

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
  <AuthenticatedLayout>

    <!-- Header + Bouton retour -->
    <div class="max-w-2xl mx-auto p-4 sm:p-6">
      <!-- Header -->
      <div class="mb-6 bg-white rounded-lg shadow p-3 sm:p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
          <h2 class="text-xl sm:text-2xl font-extrabold text-sky-900">
            Créer un Nouveau Membre
          </h2>

          <Link :href="route('admin.members.index')"
            class="inline-flex items-center gap-2 px-3 py-2 mt-2 text-xs sm:text-sm bg-sky-50 text-sky-800 rounded hover:bg-sky-100">
          ← Retour
          </Link>
        </div>
      </div>
    </div>

    <div class="py-4 sm:py-6">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-white p-4 sm:p-8 shadow-sm">
          <form @submit.prevent="submit" class="space-y-4 sm:space-y-6">

            <!-- Pseudo -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Pseudo *</label>
              <input v-model="form.pseudo" type="text" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
              <p v-if="form.errors.pseudo" class="text-red-600 text-xs mt-1">{{ form.errors.pseudo }}</p>
            </div>

            <!-- Name -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Nom *</label>
              <input v-model="form.name" type="text" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
              <p v-if="form.errors.name" class="text-red-600 text-xs mt-1">{{ form.errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input v-model="form.email" type="email" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
              <p v-if="form.errors.email" class="text-red-600 text-xs mt-1">{{ form.errors.email }}</p>
            </div>

            <!-- Position -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Position</label>
              <input v-model="form.position" type="text" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
              <p v-if="form.errors.position" class="text-red-600 text-xs mt-1">{{ form.errors.position }}</p>
            </div>

            <!-- Role -->
            <div>
              <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Role *</label>
              <select v-model="form.role" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                <option value="simple">Simple</option>
                <option value="bureau">Bureau</option>
                <option value="admin">Administrateur</option>
              </select>
              <p v-if="form.errors.role" class="text-red-600 text-xs mt-1">{{ form.errors.role }}</p>
            </div>

            <!-- Password -->
            <div class="border-t pt-4 sm:pt-6">
              <h3 class="font-semibold mb-4 text-sm sm:text-base">Mot de Passe *</h3>

              <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Mot de Passe</label>
                <input v-model="form.password" type="password" class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                <p v-if="form.errors.password" class="text-red-600 text-xs mt-1">{{ form.errors.password }}</p>
              </div>

              <div class="mt-3 sm:mt-4">
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-2">Confirmation</label>
                <input v-model="form.password_confirmation" type="password"
                  class="w-full rounded-lg border border-gray-300 px-3 sm:px-4 py-2 sm:py-2.5 text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                <p v-if="form.errors.password_confirmation" class="text-red-600 text-xs mt-1">
                  {{ form.errors.password_confirmation }}
                </p>
              </div>
            </div>

            <!-- Active Status -->
            <div class="flex items-center gap-3">
              <input :checked="form.is_active" @change="form.is_active = $event.target.checked" type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-emerald-600 focus:ring-2 focus:ring-emerald-500" />
              <label class="text-xs sm:text-sm font-medium text-gray-700">Compte Actif</label>
            </div>

            <!-- Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 pt-4 sm:pt-6 border-t">
             <Link
                :href="route('admin.members.index')"
                class="flex-1 rounded-lg border border-gray-300 px-4 py-2 text-center text-xs sm:text-sm font-medium text-gray-700 hover:bg-gray-50 transition"
              >
                Annuler
            </Link>

              <button type="submit" :disabled="form.processing"
                class="flex-1 bg-blue-600 text-white rounded-lg py-2 hover:bg-blue-700">
                {{ form.processing ? 'Création...' : 'Créer' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

  </AuthenticatedLayout>
</template>
