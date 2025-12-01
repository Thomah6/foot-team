<script setup>
import { ref } from 'vue'
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
  isBureau: Boolean,
  users: Array,
  currentUserId: [String, Number],
  monthDates: Array,
})

const searchQuery = ref(props.searchQuery)
const showDeclarePresenceModal = ref(false)
const filteredPresenceData = ref(props.presenceData)

const formatDateHeader = (date) => {
  const d = new Date(date)
  const dayName = ['Dim', 'Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam'][d.getDay()]
  const dayNum = d.getDate()
  const month = (d.getMonth() + 1).toString().padStart(2, '0')
  return `${dayName} ${dayNum}/${month}`
}

const formatMonthDisplay = (dateStr) => {
  const d = new Date(dateStr + '-01')
  return d.toLocaleDateString('fr-FR', {
    month: 'long',
    year: 'numeric',
  })
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

// Month navigation replaced by native month input (monthInput/loadMonthFromInput)

// Month picker (format YYYY-MM) for native month input like in History.vue
const monthInput = ref(props.month ? props.month.slice(0, 7) : new Date().toISOString().slice(0, 7))
const loadMonthFromInput = () => {
  if (monthInput.value) {
    window.location.href = route('presence.index', { month: monthInput.value })
  }
}

const declarPresence = async (date, userId = null) => {
  // Si bureau, post via fetch avec user_id
  if (props.isBureau && userId) {
    try {
      const res = await fetch(route('presence.store'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ date, user_id: parseInt(userId) }),
        credentials: 'include',
      })

      if (!res.ok) {
        const text = await res.text()
        console.error('Presence (bureau) failed', res.status, text)
        alert('Erreur lors de la déclaration (bureau): ' + res.status)
        showDeclarePresenceModal.value = false
        return
      }

      const data = await res.json()

      // Mettre à jour la table locale
      const presenceObj = data.presence
      const row = filteredPresenceData.value.find((r) => r.id === presenceObj.user_id)
      if (row) {
        const newPresences = {
          ...row.presences,
          [date]: {
            id: presenceObj.id,
            present: presenceObj.present,
            validated: presenceObj.validated,
            declared_by_user_id: presenceObj.declared_by_user_id || null,
            declared_by_user_name: presenceObj.declared_by_user_name || null,
          },
        }
        const newRow = { ...row, presences: newPresences }
        filteredPresenceData.value = filteredPresenceData.value.map((r) => (r.id === newRow.id ? newRow : r))
      }

      showDeclarePresenceModal.value = false
      return
    } catch (e) {
      showDeclarePresenceModal.value = false
      window.location.reload()
    }
  }

  // Si admin, post via fetch to get JSON response and update UI immediately
  if (props.isAdmin) {
    try {
      const res = await fetch(route('presence.store'), {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        body: JSON.stringify({ date }),
        credentials: 'include',
      })

      if (!res.ok) {
        const text = await res.text()
        console.error('Presence (admin) failed', res.status, text)
        alert('Erreur lors de la déclaration (admin): ' + res.status)
        showDeclarePresenceModal.value = false
        return
      }

      const data = await res.json()

      // Mettre à jour la table locale
      const presenceObj = data.presence
      const row = filteredPresenceData.value.find((r) => r.id === presenceObj.user_id)
      if (row) {
        // Build new row object with updated presences so Vue detects change
        const newPresences = {
          ...row.presences,
          [date]: {
            id: presenceObj.id,
            present: presenceObj.present,
            validated: presenceObj.validated,
            declared_by_user_id: presenceObj.declared_by_user_id || null,
            declared_by_user_name: presenceObj.declared_by_user_name || null,
          },
        }
        const newRow = { ...row, presences: newPresences }
        filteredPresenceData.value = filteredPresenceData.value.map((r) => (r.id === newRow.id ? newRow : r))
      }

      showDeclarePresenceModal.value = false
      return
    } catch (e) {
      // Fallback: reload to reflect server state
      showDeclarePresenceModal.value = false
      window.location.reload()
    }
  }

  // Non-admin: keep existing Inertia flow (reload on success)
  const form = useForm({ date: date })
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
    form.patch(route('presence.update', { presence: presenceId }), {
      onSuccess: () => {
        // Trouver et mettre à jour l'objet présence dans les données
        const presenceToUpdate = filteredPresenceData.value.find((row) => {
          return Object.values(row.presences).some((p) => p && p.id === presenceId)
        })

        if (presenceToUpdate) {
          // Trouver la date de cette présence et la mettre à jour
          for (const [date, presence] of Object.entries(presenceToUpdate.presences)) {
            if (presence && presence.id === presenceId) {
              const updatedEntry = {
                ...presence,
                present: data.present,
                validated: data.validated_by_admin,
              }
              const newPresences = {
                ...presenceToUpdate.presences,
                [date]: updatedEntry,
              }
              const newRow = { ...presenceToUpdate, presences: newPresences }
              filteredPresenceData.value = filteredPresenceData.value.map((r) => (r.id === newRow.id ? newRow : r))
              break
            }
          }
        }
      },
    })
  }
}

handleSearch()
</script>

<template>
<AuthenticatedLayout>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- PageHeading -->
      <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-3 mb-1">
            <span class="material-symbols-outlined text-4xl text-blue-600 dark:text-blue-400">sports_soccer</span>
            <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-tight text-[#111318] dark:text-white">
              Présences
            </h1>
          </div>
          <p class="text-[#636f88] dark:text-slate-400 text-sm md:text-base font-normal leading-normal">
            Calendrier d'événements footballistiques • Gérez vos déclarations
          </p>
        </div>
        <Link
          :href="route('presence.history')"
          class="flex w-full md:w-auto min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white text-sm font-bold leading-normal tracking-[0.015em] border border-blue-600 dark:border-blue-700 shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700 transition-all gap-2"
        >
          <span class="material-symbols-outlined text-base">history</span>
          <span>Historique</span>
        </Link>
      </div>

      <!-- Toolbar -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:p-5 bg-gradient-to-br from-white to-gray-50 dark:from-background-dark dark:to-slate-800 border border-slate-200 dark:border-white/10 rounded-xl shadow-sm">
        <!-- Calendar Navigation -->
        <div class="flex w-full md:w-auto items-center gap-3">
          
          <input
            v-model="monthInput"
            @change="loadMonthFromInput"
            type="month"
            class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 font-medium"
            title="Sélectionner le mois"
          />
          
        </div>

        <!-- Search and Actions -->
        <div class="flex w-full md:w-auto flex-col sm:flex-row items-stretch sm:items-center gap-3">
          <!-- SearchBar -->
          <label class="flex flex-1 sm:flex-none sm:w-72 h-11 items-center rounded-lg overflow-hidden border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-700 focus-within:ring-2 focus-within:ring-blue-500 dark:focus-within:ring-blue-400 transition">
            <span class="text-slate-600 dark:text-slate-400 px-3">
              <span class="material-symbols-outlined text-lg">search</span>
            </span>
            <input
              v-model="searchQuery"
              @input="handleSearch"
              class="flex-1 bg-transparent text-[#111318] dark:text-white focus:outline-0 text-sm placeholder:text-slate-400 dark:placeholder:text-slate-500 pr-3"
              placeholder="Filtrer les joueurs..."
            />
          </label>

          <!-- Declare Presence Button -->
          <button
            @click="showDeclarePresenceModal = true"
            class="flex items-center justify-center gap-2 h-11 px-4 bg-gradient-to-r from-green-600 to-emerald-600 dark:from-green-700 dark:to-emerald-700 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg hover:from-green-700 hover:to-emerald-700 dark:hover:from-green-600 dark:hover:to-emerald-600 transition-all active:scale-95"
          >
            <span class="material-symbols-outlined text-lg">add_circle</span>
            <span>Déclarer</span>
          </button>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full @container">
        <div class="flex rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-md overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="border-b-2 border-slate-300 dark:border-slate-600 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700">
              <tr>
                <th class="table-col-1 px-4 py-4 text-left text-[#111318] dark:text-white font-black text-sm sticky left-0 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 z-10">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-lg">group</span>
                    <span>Joueur</span>
                  </div>
                </th>
                <th
                  v-for="date in monthDates"
                  :key="date"
                  class="table-col px-4 py-4 text-center text-[#636f88] dark:text-slate-300 text-xs md:text-sm font-semibold whitespace-nowrap hover:bg-slate-100 dark:hover:bg-slate-700 transition"
                >
                  <div class="flex flex-col items-center gap-1">
                    <span>{{ formatDateHeader(date).split(' ')[0] }}</span>
                    <span class="text-[0.65rem] md:text-xs opacity-75">{{ formatDateHeader(date).split(' ')[1] }}</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in filteredPresenceData"
                :key="row.id"
                :class="[
                  'border-t border-slate-200/80 dark:border-white/10 transition hover:bg-blue-50/50 dark:hover:bg-blue-900/20',
                  index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : '',
                ]"
              >
                <td class="table-col-1 px-4 py-4 w-[250px] text-[#111318] dark:text-white font-semibold text-sm sticky left-0 z-10" :class="[index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : 'bg-white dark:bg-background-dark']">
                  <div class="flex items-center gap-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                      {{ row.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="truncate">{{ row.name }}</span>
                  </div>
                </td>
                <td
                  v-for="date in monthDates"
                  :key="`${row.id}-${date}`"
                  class="table-col px-3 py-4 text-center"
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
      :is-bureau="isBureau"
      :users="isBureau ? users : []"
      :current-user-id="currentUserId"
      :current-user-name="$page.props.auth?.user?.name || 'Vous'"
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
