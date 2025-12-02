<script setup>
import { ref } from 'vue';
import { useForm, Link } from '@inertiajs/vue3';

const props = defineProps({
  member: Object,
});

const form = useForm({
  pseudo: props.member.pseudo,
  name: props.member.name,
  email: props.member.email,
  password: '',
  password_confirmation: '',
  role: props.member.role,
  position: props.member.position,
  is_active: props.member.is_active,
});

const showPassword = ref(false);
const showPasswordConfirm = ref(false);

const submit = () => {
  form.patch(route('admin.members.update', props.member.id));
};
</script>

<template>
  <div class="max-w-2xl mx-auto p-4">
    <!-- Header -->
    <div class="mb-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 border border-gray-200 dark:border-gray-700">
      <div class="flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">
            Editer le membre {{ member.pseudo }}
          </h2>
          <p class="text-gray-600 dark:text-gray-400 mt-1">
            Modifiez les informations de ce membre
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
          <label for="pseudo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Pseudo <span class="text-red-500">*</span>
          </label>
          <input
            id="pseudo"
            v-model="form.pseudo"
            type="text"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all"
          />
          <p v-if="form.errors.pseudo" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.pseudo }}</p>
        </div>

        <!-- Name -->
        <div>
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Nom <span class="text-red-500">*</span>
          </label>
          <input
            id="name"
            v-model="form.name"
            type="text"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all"
          />
          <p v-if="form.errors.name" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.name }}</p>
        </div>

        <!-- Email -->
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Email <span class="text-red-500">*</span>
          </label>
          <input
            id="email"
            v-model="form.email"
            type="email"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all"
          />
          <p v-if="form.errors.email" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.email }}</p>
        </div>

        <!-- Position -->
        <div>
          <label for="position" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Position</label>
          <input
            id="position"
            v-model="form.position"
            type="text"
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all"
          />
          <p v-if="form.errors.position" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.position }}</p>
        </div>

        <!-- Role -->
        <div>
          <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
            Role <span class="text-red-500">*</span>
          </label>
          <select
            id="role"
            v-model="form.role"
            required
            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all"
          >
            <option value="simple">Simple</option>
            <option value="bureau">Bureau</option>
            <option value="admin">Administrateur</option>
          </select>
          <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
            Simple: Membre ordinaire | Bureau: Membre du bureau | Admin: Administrateur complet
          </p>
          <p v-if="form.errors.role" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.role }}</p>
        </div>

        <!-- Password -->
        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
          <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-4">Changer le Mot de Passe (Optionnel)</h3>

          <div class="space-y-4">
            <div>
              <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Nouveau Mot de Passe
              </label>
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all pr-10"
                  placeholder="Laissez vide pour ne pas modifier"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <i :class="showPassword ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <p v-if="form.errors.password" class="mt-2 text-sm text-red-500 dark:text-red-400">{{ form.errors.password }}</p>
            </div>

            <div>
              <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Confirmer le Mot de Passe
              </label>
              <div class="relative">
                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="showPasswordConfirm ? 'text' : 'password'"
                  class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-citron-500 focus:border-citron-500 transition-all pr-10"
                  placeholder="Laissez vide pour ne pas modifier"
                />
                <button
                  type="button"
                  @click="showPasswordConfirm = !showPasswordConfirm"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 dark:hover:text-gray-300"
                >
                  <i :class="showPasswordConfirm ? 'fas fa-eye-slash' : 'fas fa-eye'"></i>
                </button>
              </div>
              <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-500 dark:text-red-400">
                {{ form.errors.password_confirmation }}
              </p>
            </div>
          </div>
        </div>

        <!-- Status -->
        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
          <input
            id="is_active"
            :checked="form.is_active"
            @change="form.is_active = $event.target.checked"
            type="checkbox"
            class="h-5 w-5 rounded border-gray-300 dark:border-gray-600 text-citron-500 focus:ring-2 focus:ring-citron-500 transition-colors"
          />
          <label for="is_active" class="text-sm font-medium text-gray-700 dark:text-gray-300">
            Compte Actif
          </label>
        </div>
        <p v-if="form.errors.is_active" class="text-sm text-red-500 dark:text-red-400">{{ form.errors.is_active }}</p>

        <!-- Buttons -->
        <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
          <a
            href="admin/members"
            class="flex-1 rounded-lg border border-gray-300 dark:border-gray-600 px-4 py-3 text-center font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors"
          >
            Annuler
          </a>
          <button
            type="submit"
            :disabled="form.processing"
            class="flex-1 rounded-lg bg-citron-500 hover:bg-citron-600 px-4 py-3 font-medium text-white transition-colors disabled:opacity-50 flex items-center justify-center gap-2"
          >
            <span v-if="form.processing" class="h-4 w-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            {{ form.processing ? 'Traitement...' : 'Modifier' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>