<template>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- PageHeading -->
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex flex-col gap-2">
          <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight tracking-tight">
            Historique des Présences
          </p>
          <p class="text-[#636f88] dark:text-slate-400 text-base font-normal leading-normal">
            {{ isAdmin ? 'Consultez les présences mensuelles de tous les membres' : 'Consultez votre historique de présences' }}
          </p>
        </div>
        <Link
          :href="route('presence.index')"
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary text-white text-sm font-bold leading-normal tracking-[0.015em] border border-primary shadow-sm hover:opacity-90"
        >
          <span class="truncate">Retour au calendrier</span>
        </Link>
      </div>

      <!-- Filters -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-3 bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-xl">
        <!-- Month Selector -->
        <div class="flex w-full md:w-auto items-center gap-2">
          <label class="text-sm font-medium text-[#111318] dark:text-white">Mois:</label>
          <input
            v-model="selectedMonth"
            @change="loadMonth"
            type="month"
            class="px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary"
          />
        </div>

        <!-- User Selector (Admin only) -->
        <select
          v-if="isAdmin"
          v-model="selectedUserId"
          @change="loadHistory"
          class="px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-primary"
        >
          <option value="">Tous les membres</option>
          <option v-for="user in users" :key="user.id" :value="user.id">
            {{ user.name }}
          </option>
        </select>
      </div>

      <!-- History Table -->
      <div class="w-full @container">
        <div class="flex overflow-hidden rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-sm">
          <table class="w-full">
            <thead class="border-b border-slate-200 dark:border-white/10">
              <tr class="bg-white dark:bg-background-dark">
                <th class="px-4 py-3 text-left text-[#111318] dark:text-white text-sm font-semibold">
                  {{ isAdmin ? 'Membre' : 'Date' }}
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium">
                  {{ isAdmin ? 'Date' : 'Statut' }}
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium">
                  Statut
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium">
                  Validation
                </th>
              </tr>
            </thead>
            <tbody>
              <template v-if="isAdmin">
                <template v-for="(items, memberName) in presenceHistory" :key="memberName">
                  <tr class="border-t border-slate-200/80 dark:border-white/10 bg-slate-50 dark:bg-slate-800">
                    <td class="px-4 py-3 text-[#111318] dark:text-white font-semibold text-sm">
                      {{ memberName }}
                    </td>
                    <td colspan="3" class="px-4 py-3 text-right text-sm">
                      <span class="text-[#636f88] dark:text-slate-400">
                        {{ items.length }} présence(s)
                      </span>
                    </td>
                  </tr>
                  <tr
                    v-for="(presence, index) in items"
                    :key="`${memberName}-${index}`"
                    class="border-t border-slate-200/80 dark:border-white/10"
                  >
                    <td class="px-4 py-3 text-[#111318] dark:text-white text-sm"></td>
                    <td class="px-4 py-3 text-center text-sm">
                      {{ formatDate(presence.date) }}
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span
                        v-if="presence.present"
                        class="material-symbols-outlined fill text-green-500 text-sm"
                      >
                        check_circle
                      </span>
                      <span v-else class="material-symbols-outlined fill text-red-500 text-sm">
                        cancel
                      </span>
                    </td>
                    <td class="px-4 py-3 text-center">
                      <span
                        v-if="presence.validated_by_admin"
                        class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium"
                      >
                        Validée
                      </span>
                      <span
                        v-else
                        class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium"
                      >
                        En attente
                      </span>
                    </td>
                  </tr>
                </template>
              </template>
              <template v-else>
                <tr v-for="(presence, index) in presenceHistory" :key="presence.id" :class="[
                  'border-t border-slate-200/80 dark:border-white/10',
                  index % 2 === 1 ? 'bg-black/5 dark:bg-white/5' : '',
                ]">
                  <td class="px-4 py-3 text-[#111318] dark:text-white text-sm font-medium">
                    {{ formatDate(presence.date) }}
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span
                      v-if="presence.present"
                      class="material-symbols-outlined fill text-green-500"
                    >
                      check_circle
                    </span>
                    <span v-else class="material-symbols-outlined fill text-red-500">
                      cancel
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span
                      v-if="presence.present"
                      class="text-sm text-[#111318] dark:text-white"
                    >
                      Présent
                    </span>
                    <span v-else class="text-sm text-[#111318] dark:text-white">
                      Absent
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center">
                    <span
                      v-if="presence.validated_by_admin"
                      class="inline-block px-2 py-1 bg-green-100 text-green-700 rounded text-xs font-medium"
                    >
                      Validée
                    </span>
                    <span
                      v-else
                      class="inline-block px-2 py-1 bg-yellow-100 text-yellow-700 rounded text-xs font-medium"
                    >
                      En attente
                    </span>
                  </td>
                </tr>
              </template>

              <tr v-if="!presenceHistory || (isAdmin && Object.keys(presenceHistory).length === 0) || (!isAdmin && presenceHistory.length === 0)">
                <td colspan="4" class="px-4 py-8 text-center text-[#636f88] dark:text-slate-400">
                  Aucune présence enregistrée pour cette période
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Monthly Statistics (Admin only) -->
      <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-lg p-4">
          <p class="text-sm text-[#636f88] dark:text-slate-400 mb-2">Total des absences</p>
          <p class="text-3xl font-bold text-red-500">{{ calculateTotalAbsences() }}</p>
        </div>
        <div class="bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-lg p-4">
          <p class="text-sm text-[#636f88] dark:text-slate-400 mb-2">Total des présences</p>
          <p class="text-3xl font-bold text-green-500">{{ calculateTotalPresences() }}</p>
        </div>
        <div class="bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-lg p-4">
          <p class="text-sm text-[#636f88] dark:text-slate-400 mb-2">En attente de validation</p>
          <p class="text-3xl font-bold text-yellow-500">{{ calculatePendingValidations() }}</p>
        </div>
        <div class="bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-lg p-4">
          <p class="text-sm text-[#636f88] dark:text-slate-400 mb-2">Validées</p>
          <p class="text-3xl font-bold text-blue-500">{{ calculateValidated() }}</p>
        </div>
      </div>
    </div>
  </main>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  presenceHistory: [Object, Array],
  month: String,
  isAdmin: Boolean,
  users: Array,
})

const selectedMonth = ref(props.month)
const selectedUserId = ref('')

const formatDate = (date) => {
  const d = new Date(date)
  return d.toLocaleDateString('fr-FR', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric',
  })
}

const loadMonth = () => {
  window.location.href = route('presence.history', { month: selectedMonth.value })
}

const loadHistory = () => {
  window.location.href = route('presence.history', {
    month: selectedMonth.value,
    user_id: selectedUserId.value,
  })
}

const calculateTotalAbsences = () => {
  if (!props.isAdmin || !props.presenceHistory) return 0
  let count = 0
  Object.values(props.presenceHistory).forEach((items) => {
    items.forEach((item) => {
      if (!item.present) count++
    })
  })
  return count
}

const calculateTotalPresences = () => {
  if (!props.isAdmin || !props.presenceHistory) return 0
  let count = 0
  Object.values(props.presenceHistory).forEach((items) => {
    items.forEach((item) => {
      if (item.present) count++
    })
  })
  return count
}

const calculatePendingValidations = () => {
  if (!props.isAdmin || !props.presenceHistory) return 0
  let count = 0
  Object.values(props.presenceHistory).forEach((items) => {
    items.forEach((item) => {
      if (!item.validated_by_admin) count++
    })
  })
  return count
}

const calculateValidated = () => {
  if (!props.isAdmin || !props.presenceHistory) return 0
  let count = 0
  Object.values(props.presenceHistory).forEach((items) => {
    items.forEach((item) => {
      if (item.validated_by_admin) count++
    })
  })
  return count
}
</script>
