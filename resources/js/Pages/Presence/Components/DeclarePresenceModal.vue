<template>
  <div class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full mx-4 overflow-hidden border-2 border-lime-200/50">
      <!-- Header with gradient -->
      <div class="bg-gradient-to-r from-lime-500 to-emerald-500 px-6 py-5">
        <div class="flex items-center gap-3">
          <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
            <i class="fas fa-calendar-plus text-white text-xl"></i>
          </div>
          <div>
            <h2 class="text-2xl font-bold text-white">Déclarer présence</h2>
            <p v-if="isBureau" class="text-xs text-white/80">Bureau • Pour un combattant</p>
          </div>
        </div>
      </div>

      <!-- Content -->
      <div class="p-6 space-y-6">
        <!-- Sélecteur de joueur (Bureau uniquement) -->
        <div v-if="isBureau">
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Pour quel combattant ? *
          </label>
          <select
            v-model="selectedUserId"
            class="w-full px-4 py-3 border-2 border-lime-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-lime-500/30 focus:border-lime-500 font-medium transition"
          >
            <option v-for="user in users" :key="user.id" :value="user.id.toString()">
              ⚽ {{ user.name }}{{ currentUserId === user.id ? ' (Moi)' : '' }}
            </option>
          </select>
          <p class="text-xs text-gray-500 mt-2">
            Sélectionnez le combattant pour lequel vous déclarez la présence.
          </p>
        </div>

        <!-- Date input -->
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-2">
            Date de bataille *
          </label>
          <input
            v-model="selectedDate"
            type="date"
            class="w-full px-4 py-3 border-2 border-lime-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-lime-500/30 focus:border-lime-500 font-medium transition"
          />
          <p class="text-xs text-gray-500 mt-2">
            Sélectionnez la date de présence aux événements.
          </p>
        </div>

        <!-- Info déclarée par (Bureau) -->
        <div v-if="isBureau" class="bg-lime-50 border border-lime-300 rounded-xl p-4">
          <div class="flex items-start gap-3">
            <i class="fas fa-info-circle text-lime-600 text-lg mt-0.5"></i>
            <div class="text-sm text-gray-700">
              <p class="font-bold mb-1">Déclaration par le bureau</p>
              <p class="text-xs opacity-90">Votre nom sera enregistré comme déclarant ({{ currentUserName }})</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="flex gap-3 justify-end p-6 bg-lime-50/50 border-t border-lime-200">
        <button
          @click="$emit('close')"
          class="px-5 py-3 border-2 border-lime-300 rounded-xl text-gray-700 hover:bg-white font-bold text-sm transition hover:shadow-md"
        >
          Annuler
        </button>
        <button
          @click="submit"
          :disabled="isBureau && !selectedUserId"
          class="px-6 py-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white rounded-xl hover:from-lime-600 hover:to-emerald-600 font-bold text-sm shadow-lg hover:shadow-xl hover:shadow-emerald-500/30 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
        >
          <span class="flex items-center gap-2">
            <i class="fas fa-check-circle"></i>
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