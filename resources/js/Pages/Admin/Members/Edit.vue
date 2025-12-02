<script setup>
import { ref } from 'vue';
import { useForm,Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
  <AuthenticatedLayout>
    <div class="max-w-2xl mx-auto p-4">
      <!-- Header -->
      <div class="mb-6 bg-white rounded-lg shadow p-4 flex items-center justify-between">
        <div>
          <h2 class="text-2xl font-extrabold text-sky-900">
            Editer le membre {{ member.pseudo }}
          </h2>

          <Link :href="route('admin.members.index')"
            class="inline-flex items-center gap-2 px-3 py-2 bg-sky-50 text-sky-800 rounded">
          ← Retour
          </Link>
        </div>
      </div>
    </div>

    <div class="py-6">
      <div class="mx-auto max-w-2xl px-4 sm:px-6 lg:px-8">
        <div class="rounded-lg bg-white p-6 shadow-sm sm:p-8">
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Pseudo -->
            <div>
              <label for="pseudo" class="block text-sm font-medium text-gray-700 mb-2">
                Pseudo <span class="text-red-500">*</span>
              </label>
              <input
                id="pseudo"
                v-model="form.pseudo"
                type="text"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.pseudo" class="mt-2 text-sm text-red-600">{{ form.errors.pseudo }}</p>
            </div>

            <!-- Name -->
            <div>
              <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                Nom <span class="text-red-500">*</span>
              </label>
              <input
                id="name"
                v-model="form.name"
                type="text"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.name" class="mt-2 text-sm text-red-600">{{ form.errors.name }}</p>
            </div>

            <!-- Email -->
            <div>
              <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                Email <span class="text-red-500">*</span>
              </label>
              <input
                id="email"
                v-model="form.email"
                type="email"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.email" class="mt-2 text-sm text-red-600">{{ form.errors.email }}</p>
            </div>

            <!-- Position -->
            <div>
              <label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position</label>
              <input
                id="position"
                v-model="form.position"
                type="text"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              />
              <p v-if="form.errors.position" class="mt-2 text-sm text-red-600">{{ form.errors.position }}</p>
            </div>

            <!-- Role -->
            <div>
              <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                Role <span class="text-red-500">*</span>
              </label>
              <select
                id="role"
                v-model="form.role"
                class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="simple">Simple</option>
                <option value="bureau">Bureau</option>
                <option value="admin">Administrateur</option>
              </select>
              <p class="mt-1 text-xs text-gray-500">
                Simple: Membre ordinaire | Bureau: Membre du bureau | Admin: Administrateur complet
              </p>
              <p v-if="form.errors.role" class="mt-2 text-sm text-red-600">{{ form.errors.role }}</p>
            </div>

            <!-- Password -->
            <div class="border-t border-gray-200 pt-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Changer le Mot de Passe (Optionnel)</h3>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                  Nouveau Mot de Passe
                </label>
                <div class="relative">
                  <input
                    id="password"
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Laissez vide pour ne pas modifier"
                  />
                  <button
                    type="button"
                    @click="showPassword = !showPassword"
                    class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700"
                  >
                    {{ showPassword ? 'Masquer' : 'Afficher' }}
                  </button>
                </div>
                <p v-if="form.errors.password" class="mt-2 text-sm text-red-600">{{ form.errors.password }}</p>
              </div>

              <div class="mt-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                  Confirmer le Mot de Passe
                </label>
                <div class="relative">
                  <input
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    :type="showPasswordConfirm ? 'text' : 'password'"
                    class="w-full rounded-lg border border-gray-300 px-4 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Laissez vide pour ne pas modifier"
                  />
                  <button
                    type="button"
                    @click="showPasswordConfirm = !showPasswordConfirm"
                    class="absolute right-3 top-2.5 text-gray-500 hover:text-gray-700"
                  >
                    {{ showPasswordConfirm ? 'Masquer' : 'Afficher' }}
                  </button>
                </div>
                <p v-if="form.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                  {{ form.errors.password_confirmation }}
                </p>
              </div>
            </div>

            <!-- Status -->
            <div class="flex items-center gap-3">
              <input
                id="is_active"
                :checked="form.is_active"
                @change="form.is_active = $event.target.checked"
                type="checkbox"
                class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-2 focus:ring-blue-500"
              />
              <label for="is_active" class="text-sm font-medium text-gray-700">
                Compte Actif
              </label>
            </div>
            <p v-if="form.errors.is_active" class="text-sm text-red-600">{{ form.errors.is_active }}</p>

            <!-- Buttons -->
            <div class="flex gap-3 pt-6 border-t border-gray-200">
             <Link :href="route('admin.members.index')" class="flex-1 border rounded-lg py-2 text-center hover:bg-gray-50">
                Annuler
              </Link>
              <button
                type="submit"
                :disabled="form.processing"
                class="flex-1 rounded-lg bg-blue-600 px-4 py-2 font-medium text-white hover:bg-blue-700 transition disabled:opacity-50"
              >
                {{ form.processing ? 'Traitement...' : 'Modifier' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
