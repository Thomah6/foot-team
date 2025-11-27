<script setup>
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import DeclarePresenceModal from './Components/DeclarePresenceModal.vue'
import PresenceStatusCell from './Components/PresenceStatusCell.vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'


const props = defineProps({
  presenceData: Array,
  dayPresences: Array,
  selectedDate: String,
  month: String,
  searchQuery: String,
  isAdmin: Boolean,
  monthDates: Array,
})

const searchQuery = ref(props.searchQuery)
const showDeclarePresenceModal = ref(false)
const filteredPresenceData = ref(props.presenceData)
const month = ref(new Date(props.month))

const formatMonth = (date) => {
  const formatter = new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  })
  return formatter.format(date).charAt(0).toUpperCase() + formatter.format(date).slice(1)
}

const formatDateHeader = (date) => {
  const d = new Date(date)
  const dayName = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][d.getDay()]
  const dayNum = d.getDate()
  const month = (d.getMonth() + 1).toString().padStart(2, '0')
  return `${dayName} ${dayNum}/${month}`
}

const handleSearch = () => {
  if (!searchQuery.value) {
    filteredPresenceData.value = props.presenceData
  } else {
    const query = searchQuery.value.toLowerCase()
    filteredPresenceData.value = props.presenceData.filter((row) =>
      row.name.toLowerCase().includes(query)
    )
  }
}

const previousMonth = () => {
  const newMonth = new Date(month.value)
  newMonth.setMonth(newMonth.getMonth() - 1)
  month.value = newMonth
  // Charger les données du mois précédent
  loadMonthData()
}

const nextMonth = () => {
  const newMonth = new Date(month.value)
  newMonth.setMonth(newMonth.getMonth() + 1)
  month.value = newMonth
  // Charger les données du mois suivant
  loadMonthData()
}

const goToToday = () => {
  month.value = new Date()
  loadMonthData()
}

const loadMonthData = () => {
  const monthStr = month.value.toISOString().split('T')[0].slice(0, 7)
  window.location.href = route('presence.index', { month: monthStr })
}

const declarPresence = (date) => {
  const form = useForm({
    date: date,
  })
  form.post(route('presence.store'), {
    onSuccess: () => {
      showDeclarePresenceModal.value = false
      // Recharger la page
      window.location.reload()
    },
  })
}

const updatePresence = (presenceId, data) => {
  if (props.isAdmin) {
    const form = useForm(data)
    form.patch(route('presence.update', { presence: presenceId }))
  }
}

handleSearch()
</script>

<template>
<AuthenticatedLayout>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- PageHeading -->
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex flex-col gap-2">
          <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight tracking-tight">
            Calendrier des Présences
          </p>
          <p class="text-[#636f88] dark:text-slate-400 text-base font-normal leading-normal">
            Suivez et gérez la présence des membres aux événements.
          </p>
        </div>
        <Link
          v-if="isAdmin"
          :href="route('presence.monthlyReport')"
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-white dark:bg-slate-700 text-[#111318] dark:text-white text-sm font-bold leading-normal tracking-[0.015em] border border-slate-200 dark:border-slate-600 shadow-sm hover:bg-gray-50 dark:hover:bg-slate-600"
        >
          <span class="truncate">Voir l'historique</span>
        </Link>
      </div>

      <!-- Toolbar -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 p-3 bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-xl">
        <!-- Calendar Navigation -->
        <div class="flex w-full md:w-auto items-center gap-2">
          <button
            @click="previousMonth"
            class="p-2 text-[#111318] dark:text-white rounded-lg hover:bg-black/5 dark:hover:bg-white/5"
          >
            <span class="material-symbols-outlined">chevron_left</span>
          </button>
          <span class="text-[#111318] dark:text-white font-semibold text-lg min-w-[120px] text-center">
            {{ formatMonth(month) }}
          </span>
          <button
            @click="nextMonth"
            class="p-2 text-[#111318] dark:text-white rounded-lg hover:bg-black/5 dark:hover:bg-white/5"
          >
            <span class="material-symbols-outlined">chevron_right</span>
          </button>
          <button
            @click="goToToday"
            class="p-2 ml-2 text-[#111318] dark:text-white rounded-lg hover:bg-black/5 dark:hover:bg-white/5"
            title="Aller à aujourd'hui"
          >
            <span class="material-symbols-outlined">calendar_today</span>
          </button>
        </div>

        <!-- Search and Actions -->
        <div class="flex w-full md:w-auto items-center gap-4">
          <!-- SearchBar -->
          <label class="flex flex-col h-10 w-full md:w-64">
            <div class="flex w-full flex-1 items-stretch rounded-lg h-full">
              <div class="text-[#636f88] dark:text-slate-400 flex bg-background-light dark:bg-slate-700 items-center justify-center pl-3 rounded-l-lg border-y border-l border-slate-200 dark:border-slate-600">
                <span class="material-symbols-outlined text-base">search</span>
              </div>
              <input
                v-model="searchQuery"
                @input="handleSearch"
                class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-r-lg text-[#111318] dark:text-white focus:outline-0 focus:ring-0 border-y border-r border-slate-200 dark:border-slate-600 bg-background-light dark:bg-slate-700 h-full placeholder:text-[#636f88] dark:placeholder:text-slate-400 px-2 text-sm font-normal leading-normal"
                placeholder="Filtrer les membres..."
              />
            </div>
          </label>

          <!-- Declare Presence Button -->
          <button
            v-if="!isAdmin"
            @click="showDeclarePresenceModal = true"
            class="flex w-full md:w-auto max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 bg-primary text-white gap-2 text-sm font-bold leading-normal tracking-[0.015em] min-w-0 px-4 shadow-sm hover:opacity-90"
          >
            <span class="material-symbols-outlined fill text-base">add_circle</span>
            <span class="truncate">Déclarer ma présence</span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full @container">
        <div class="flex overflow-hidden rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-sm overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-slate-200 dark:border-white/10">
              <tr class="bg-white dark:bg-background-dark">
                <th class="table-col-1 px-4 py-3 text-left text-[#111318] dark:text-white w-[250px] text-sm font-semibold sticky left-0 bg-white dark:bg-background-dark z-10">
                  Membre
                </th>
                <th
                  v-for="date in monthDates"
                  :key="date"
                  class="table-col px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium whitespace-nowrap"
                >
                  {{ formatDateHeader(date) }}
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in filteredPresenceData"
                :key="row.id"
                :class="[
                  'border-t border-slate-200/80 dark:border-white/10',
                  index % 2 === 1 ? 'bg-black/5 dark:bg-white/5' : '',
                ]"
              >
                <td class="table-col-1 px-4 py-3 w-[250px] text-[#111318] dark:text-white text-sm font-medium sticky left-0 z-10" :class="[index % 2 === 1 ? 'bg-black/5 dark:bg-white/5' : 'bg-white dark:bg-background-dark']">
                  {{ row.name }}
                </td>
                <td
                  v-for="date in monthDates"
                  :key="`${row.id}-${date}`"
                  class="table-col px-4 py-3 text-center"
                >
                  <PresenceStatusCell
                    :presence="row.presences[date]"
                    :is-admin="isAdmin"
                    @update="updatePresence"
                  />
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Modal: Déclarer sa présence -->
    <DeclarePresenceModal
      v-if="showDeclarePresenceModal"
      @close="showDeclarePresenceModal = false"
      @submit="declarPresence"
    />
  </main>
</AuthenticatedLayout>
</template>

<style scoped>
.table-col-1 {
  position: sticky;
  left: 0;
  z-index: 10;
}
@container (max-width: 840px) {
  .table-col:nth-child(n + 8) {
    display: none;
  }
}
@container (max-width: 720px) {
  .table-col:nth-child(n + 7) {
    display: none;
  }
}
@container (max-width: 600px) {
  .table-col:nth-child(n + 6) {
    display: none;
  }
}
@container (max-width: 480px) {
  .table-col:nth-child(n + 5) {
    display: none;
  }
}
@container (max-width: 360px) {
  .table-col:nth-child(n + 4) {
    display: none;
  }
}
</style>
