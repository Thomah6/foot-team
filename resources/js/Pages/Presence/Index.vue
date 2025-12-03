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

const monthInput = ref(props.month ? props.month.slice(0, 7) : new Date().toISOString().slice(0, 7))
const loadMonthFromInput = () => {
  if (monthInput.value) {
    window.location.href = route('presence.index', { month: monthInput.value })
  }
}

const declarPresence = async (date, userId = null) => {
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

  const form = useForm({ date: date })
  form.post(route('presence.store'), {
    onSuccess: () => {
      showDeclarePresenceModal.value = false
      window.location.reload()
    },
  })
}

const updatePresence = (presenceId, data) => {
  if (props.isAdmin) {
    const form = useForm(data)
    form.patch(route('presence.update', { presence: presenceId }), {
      onSuccess: () => {
        const presenceToUpdate = filteredPresenceData.value.find((row) => {
          return Object.values(row.presences).some((p) => p && p.id === presenceId)
        })

        if (presenceToUpdate) {
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
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-3 mb-1">
            <div class="w-16 h-16 bg-gradient-to-br from-lime-100 to-emerald-100 rounded-2xl flex items-center justify-center border-2 border-lime-300">
              <i class="fas fa-calendar-check text-lime-600 text-2xl"></i>
            </div>
            <div>
              <h1 class="text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                Calendrier des Présences
              </h1>
              <p class="text-gray-600 text-lg font-medium tracking-wide mt-2">
                Gestion des présences dans l'arène • Saison 2024
              </p>
            </div>
          </div>
        </div>
        <Link
          :href="route('presence.history')"
          class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white px-8 py-4 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30"
        >
          <i class="fas fa-history"></i>
          Historique des Batailles
        </Link>
      </div>

      <!-- Toolbar -->
      <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-6 shadow-lg">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <!-- Calendar Navigation -->
          <div class="flex items-center gap-4">
            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-700 mb-2">Mois</label>
              <input
                v-model="monthInput"
                @change="loadMonthFromInput"
                type="month"
                class="px-4 py-3 border-2 border-lime-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-lime-500/30 focus:border-lime-500 font-medium"
                title="Sélectionner le mois"
              />
            </div>
          </div>

          <!-- Search and Actions -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
            <!-- SearchBar -->
            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-700 mb-2">Rechercher un combattant</label>
              <div class="flex items-center h-12 rounded-xl overflow-hidden border-2 border-lime-300 bg-white focus-within:ring-2 focus-within:ring-lime-500/30 focus-within:border-lime-500 transition">
                <span class="text-lime-600 px-4">
                  <i class="fas fa-search"></i>
                </span>
                <input
                  v-model="searchQuery"
                  @input="handleSearch"
                  class="flex-1 bg-transparent text-gray-900 focus:outline-none placeholder:text-gray-400 pr-4"
                  placeholder="Nom du combattant..."
                />
              </div>
            </div>

            <!-- Declare Presence Button -->
            <div class="flex flex-col">
              <label class="text-sm font-semibold text-gray-700 mb-2 invisible">Action</label>
              <button
                @click="showDeclarePresenceModal = true"
                class="flex items-center justify-center gap-3 h-12 px-6 bg-gradient-to-r from-lime-500 to-emerald-500 text-white rounded-xl font-bold shadow-lg hover:shadow-xl hover:from-lime-600 hover:to-emerald-600 hover:shadow-emerald-500/30 transition-all duration-300"
              >
                <i class="fas fa-plus-circle"></i>
                Déclarer une présence
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="w-full @container">
        <div class="bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 shadow-xl overflow-x-auto">
          <table class="w-full border-collapse">
            <thead class="border-b-2 border-lime-200 bg-gradient-to-r from-lime-50 to-emerald-50">
              <tr>
                <th class="px-4 sm:px-6 py-3 sm:py-4 text-left text-gray-900 font-bold text-xs sm:text-sm sticky left-0 bg-gradient-to-r from-lime-50 to-emerald-50 z-10">
                  <div class="flex items-center gap-1 sm:gap-2">
                    <i class="fas fa-users text-lime-600 text-xs sm:text-sm"></i>
                    <span class="hidden sm:inline">COMBATTANT</span>
                    <span class="sm:hidden">NOM</span>
                  </div>
                </th>
                <th
                  v-for="date in monthDates"
                  :key="date"
                  class="px-2 sm:px-4 py-3 sm:py-4 text-center text-gray-700 text-xs font-semibold whitespace-nowrap hover:bg-lime-100/50 transition min-w-fit sm:min-w-[80px]"
                >
                  <div class="flex flex-col items-center gap-0.5">
                    <span class="font-bold text-xs">{{ formatDateHeader(date).split(' ')[0] }}</span>
                    <span class="text-xs opacity-75 font-medium">{{ formatDateHeader(date).split(' ')[1] }}</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in filteredPresenceData"
                :key="row.id"
                :class="[
                  'border-t border-lime-100 transition hover:bg-lime-50/50',
                  index % 2 === 0 ? 'bg-lime-50/30' : '',
                ]"
              >
                <td class="px-4 sm:px-6 py-4 w-20 sm:w-[250px] text-gray-900 font-bold text-xs sm:text-sm sticky left-0 z-10" :class="[index % 2 === 0 ? 'bg-lime-50/30' : 'bg-white']">
                  <div class="flex items-center gap-2 sm:gap-3">
                    <div class="w-8 sm:w-10 h-8 sm:h-10 rounded-full bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-white text-xs sm:text-sm font-bold flex-shrink-0">
                      {{ row.name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="truncate hidden sm:inline">{{ row.name }}</span>
                    <span class="truncate sm:hidden text-xs">{{ row.name.substring(0, 8) }}</span>
                  </div>
                </td>
                <td
                  v-for="date in monthDates"
                  :key="`${row.id}-${date}`"
                  class="px-2 sm:px-3 py-3 sm:py-4 text-center min-w-fit sm:min-w-[80px] text-xs sm:text-sm"
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

      <!-- Legend -->
      <div class="bg-gradient-to-br from-lime-50 to-emerald-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-6 shadow-lg">
        <div class="flex flex-wrap items-center gap-6">
          <div class="flex items-center gap-2">
            <i class="fas fa-circle-check text-emerald-500 text-xl"></i>
            <span class="text-gray-700 font-medium">Présent validé</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-clock text-amber-500 text-xl"></i>
            <span class="text-gray-700 font-medium">En attente de validation</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-circle-xmark text-red-500 text-xl"></i>
            <span class="text-gray-700 font-medium">Absent</span>
          </div>
          <div class="flex items-center gap-2">
            <i class="fas fa-minus text-gray-300 text-xl"></i>
            <span class="text-gray-700 font-medium">Non déclaré</span>
          </div>
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