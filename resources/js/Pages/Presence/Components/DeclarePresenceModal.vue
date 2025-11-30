<template>
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden">
      <!-- Header with gradient -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 px-6 py-5">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined text-3xl text-white">sports_soccer</span>
          <div>
            <h2 class="text-2xl font-black text-white">Déclarer présence</h2>
            <p v-if="isBureau" class="text-xs text-blue-100">Bureau • Pour un membre</p>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-5">
        <!-- Sélecteur de joueur (Bureau uniquement) -->
        <div v-if="isBureau">
          <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
            Pour quel joueur ? *
          </label>
          <select
            v-model="selectedUserId"
            class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 font-medium transition"
          >
            <option v-for="user in users" :key="user.id" :value="user.id.toString()">
              ⚽ {{ user.name }}{{ currentUserId === user.id ? ' (Moi)' : '' }}
            </option>
          </select>
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
            Sélectionnez le membre pour lequel vous déclarez la présence. Vous êtes pré-sélectionné.
          </p>
        </div>

        <!-- Date input -->
        <div>
          <label class="block text-sm font-semibold text-[#111318] dark:text-white mb-2">
            Date de présence *
          </label>
          <input
            v-model="selectedDate"
            type="date"
            class="w-full px-4 py-3 border-2 border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:border-blue-500 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-500/20 dark:focus:ring-blue-400/20 font-medium transition"
          />
          <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">
            Sélectionnez la date d'une présence aux événements.
          </p>
        </div>

        <!-- Info déclarée par (Bureau) -->
        <div v-if="isBureau" class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
          <div class="flex items-start gap-3">
            <span class="material-symbols-outlined text-lg text-blue-600 dark:text-blue-400">info</span>
            <div class="text-sm text-blue-700 dark:text-blue-300">
              <p class="font-semibold mb-1">Déclaration par le bureau</p>
              <p class="text-xs opacity-90">Votre nom sera enregistré comme déclarant ({{ currentUserName }})</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex gap-3 justify-end p-6 bg-slate-50 dark:bg-slate-700/50 border-t border-slate-200 dark:border-slate-600">
        <button
          @click="$emit('close')"
          class="px-4 py-2 border-2 border-slate-300 dark:border-slate-600 rounded-lg text-[#111318] dark:text-white hover:bg-slate-100 dark:hover:bg-slate-600/50 font-semibold text-sm transition"
        >
          Annuler
        </button>
        <button
          @click="submit"
          :disabled="isBureau && !selectedUserId"
          class="px-6 py-2 bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700 text-white rounded-lg hover:from-green-700 hover:to-emerald-700 dark:hover:from-green-600 dark:hover:to-emerald-600 font-semibold text-sm shadow-md hover:shadow-lg transition-all active:scale-95 disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span class="flex items-center gap-2">
            <span class="material-symbols-outlined text-base">check_circle</span>
            Confirmer
          </span>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  isBureau: Boolean,
  users: Array,
  currentUserName: String,
  currentUserId: [String, Number],
})

const selectedDate = ref(new Date().toISOString().split('T')[0])
// Pré-sélectionner le bureau déclarant par défaut
const selectedUserId = ref(props.isBureau && props.currentUserId ? props.currentUserId.toString() : '')

const emit = defineEmits(['close', 'submit'])

const submit = () => {
  if (props.isBureau) {
    if (selectedDate.value && selectedUserId.value) {
      emit('submit', selectedDate.value, selectedUserId.value)
    }
  } else {
    if (selectedDate.value) {
      emit('submit', selectedDate.value)
    }
  }
}
</script>
