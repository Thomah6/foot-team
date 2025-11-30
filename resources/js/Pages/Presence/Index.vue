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

// Month navigation replaced by native month input (monthInput/loadMonthFromInput)

// Month picker (format YYYY-MM) for native month input like in History.vue
const monthInput = ref(props.month ? props.month.slice(0, 7) : new Date().toISOString().slice(0, 7))
const loadMonthFromInput = () => {
  if (monthInput.value) {
    window.location.href = route('presence.index', { month: monthInput.value })
  }
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
              // Mettre à jour les propriétés de la présence
              presence.present = data.present
              presence.validated_by_admin = data.validated_by_admin
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
  <div class="page-container">
    <div class="page-wrapper">
      <!-- Page Header -->
      <div class="page-header">
        <div>
          <h1 class="page-title">
            <i class="fas fa-calendar-check text-primary-600 mr-3"></i>
            Calendrier des Présences
          </h1>
          <p class="page-subtitle">
            Gérez la présence des membres aux événements footballistiques
          </p>
        </div>
        <Link
          :href="route('presence.history')"
          class="inline-flex items-center gap-2 px-6 py-3 rounded-button bg-accent-600 text-white font-semibold hover:bg-accent-700 transition-colors shadow-button"
        >
          <i class="fas fa-history"></i>
          Historique
        </Link>
      </div>

      <!-- Toolbar -->
      <div class="card mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
          <!-- Month Picker -->
          <div class="flex items-center gap-3 w-full md:w-auto">
            <label for="month" class="text-sm font-semibold text-gray-700 dark:text-gray-300">Mois:</label>
            <input
              id="month"
              v-model="monthInput"
              @change="loadMonthFromInput"
              type="month"
              class="form-input w-full md:w-48"
            />
          </div>

          <!-- Search -->
          <div class="flex-1 md:flex-initial">
            <div class="relative">
              <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
              <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Rechercher un membre..."
                class="form-input pl-10 w-full"
              />
            </div>
          </div>

          <!-- Declare Presence Button -->
          <button
            v-if="!isAdmin"
            @click="showDeclarePresenceModal = true"
            class="inline-flex items-center gap-2 px-6 py-3 rounded-button bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors shadow-button whitespace-nowrap"
          >
            <i class="fas fa-plus"></i>
            Déclarer ma présence
          </button>
        </div>
      </div>

      <!-- Table Container -->
      <div class="table-container">
        <table class="table-responsive">
          <thead>
            <tr>
              <th class="w-[250px] sticky left-0 z-10 bg-gray-50 dark:bg-gray-800">
                <div class="flex items-center gap-2">
                  <i class="fas fa-users text-primary-600"></i>
                  <span>Membre</span>
                </div>
              </th>
              <th
                v-for="date in monthDates"
                :key="date"
                class="text-center whitespace-nowrap text-sm"
                :title="new Date(date).toLocaleDateString('fr-FR', { weekday: 'long', month: 'long', day: 'numeric' })"
              >
                {{ formatDateHeader(date) }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="filteredPresenceData.length === 0">
              <td :colspan="monthDates.length + 1" class="text-center py-8 text-gray-500">
                <i class="fas fa-inbox text-3xl mb-2 block text-gray-300"></i>
                Aucun membre trouvé
              </td>
            </tr>
            <tr
              v-for="row in filteredPresenceData"
              :key="row.id"
            >
              <td class="sticky left-0 z-10 bg-white dark:bg-gray-800 w-[250px] font-semibold">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 text-xs font-bold">
                    {{ row.name.charAt(0) }}
                  </div>
                  <span>{{ row.name }}</span>
                </div>
              </td>
              <td
                v-for="date in monthDates"
                :key="`${row.id}-${date}`"
                class="text-center"
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

    <!-- Modal: Déclarer sa présence -->
    <DeclarePresenceModal
      v-if="showDeclarePresenceModal"
      @close="showDeclarePresenceModal = false"
      @submit="declarPresence"
    />
  </div>
</AuthenticatedLayout>
</template>
