<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  titles: Array,
  can: Object
})

// Recherche
const search = ref('')

const filteredTitles = computed(() =>
  (props.titles || []).filter(t =>
    t.title.toLowerCase().includes(search.value.toLowerCase())
  )
)

// Découpage du contenu
function contentLines(content) {
  if (!content) return []
  return content.split('\n').map(line => line.trim()).filter(l => l.length)
}

// Carousel
const current = ref(0)
let timer = null
const intervalMs = 5000

function next() {
  if (filteredTitles.value.length === 0) return
  current.value = (current.value + 1) % filteredTitles.value.length
}

function prev() {
  if (filteredTitles.value.length === 0) return
  current.value = (current.value - 1 + filteredTitles.value.length) % filteredTitles.value.length
}

function goTo(i) {
  current.value = i
}

function start() {
  stop()
  timer = setInterval(next, intervalMs)
}

function stop() {
  if (timer) clearInterval(timer)
  timer = null
}

// Swipe (mobile)
let startX = 0
let endX = 0

function onTouchStart(e) {
  startX = e.touches ? e.touches[0].clientX : e.clientX
}

function onTouchMove(e) {
  endX = e.touches ? e.touches[0].clientX : e.clientX
}

function onTouchEnd() {
  const diff = startX - endX
  if (Math.abs(diff) > 50) {
    diff > 0 ? next() : prev()
  }
}

// --- Modales de suppression ---
const showDeleteModal = ref(false)               // pour supprimer la rubrique (titre + contenus)
const showContentDeleteModal = ref(false)        // pour supprimer un contenu précis
const selectedRegulationId = ref(null)           // id de la rubrique sélectionnée
const selectedContentId = ref(null)              // id du contenu sélectionné
const selectedContentLabel = ref('')             // affichage dans la modale

function openDeleteModal() {
  const sel = filteredTitles.value[current.value]
  if (!sel) return
  selectedRegulationId.value = sel.id
  showDeleteModal.value = true
}

function closeDeleteModal() {
  selectedRegulationId.value = null
  showDeleteModal.value = false
}

function deleteRegulationConfirm() {
  if (!selectedRegulationId.value) return
  router.delete(`/regulations/${selectedRegulationId.value}`, {
    onSuccess: () => {
      closeDeleteModal()
      // si on supprime l'élément courant, on remet l'index à 0 ou au précédent
      if (current.value >= filteredTitles.value.length - 1) current.value = Math.max(0, filteredTitles.value.length - 2)
    }
  })
}

// Contenu
function openContentDeleteModal(content) {
  selectedContentId.value = content.id
  selectedContentLabel.value = content.sub_number ?? `Contenu #${content.id}`
  showContentDeleteModal.value = true
}

function closeContentDeleteModal() {
  selectedContentId.value = null
  selectedContentLabel.value = ''
  showContentDeleteModal.value = false
}

function deleteContentConfirm() {
  if (!selectedContentId.value) return
  router.delete(`/regulations/content/${selectedContentId.value}`, {
    onSuccess: () => {
      closeContentDeleteModal()
    }
  })
}

// Clavier : echap ferme les modales
function onKeydown(e) {
  if (e.key === 'Escape') {
    if (showContentDeleteModal.value) closeContentDeleteModal()
    else if (showDeleteModal.value) closeDeleteModal()
  }
}

onMounted(() => {
  start()
  window.addEventListener('keydown', onKeydown)
})
onUnmounted(() => {
  stop()
  window.removeEventListener('keydown', onKeydown)
})
</script>

<template>
  <AuthenticatedLayout>
    <div class="relative flex flex-col md:flex-row overflow-x-hidden min-h-screen">

      <!-- Vidéo en background -->
      <video autoplay muted loop playsinline class="absolute inset-0 w-full h-full object-cover">
        <source
          src="https://dm0qx8t0i9gc9.cloudfront.net/watermarks/video/HZHnL4R6xj1hrqjd5/videoblocks-63277d369467e60a6bd204a6_b6lw8rr2ms__2e614f57e25a88afa5e7613d4f09343d__P360.mp4"
          type="video/mp4" />
        Votre navigateur ne supporte pas la vidéo.
      </video>

      <!-- Overlay translucide -->
      <div class="absolute inset-0 bg-white/40 dark:bg-black/40 backdrop-blur-sm"></div>

      <!-- Contenu -->
      <div class="relative flex-1 min-w-0 z-10 bg-white/30 dark:bg-gray-900/30 min-h-full">
        <div class="p-6 sm:p-3 max-w-5xl mx-auto text-gray-900 dark:text-gray-100">

          <!-- Header -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <h1 class="text-xl font-bold">Liste des règlements</h1>

            <div class="flex gap-2 items-center">
              <input v-model="search" type="text" placeholder="Rechercher un règlement..."
                class="border rounded p-2 mr-2 text-black dark:text-white bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-cyan-500" />

              <button v-if="props.can.create"
                class="bg-cyan-600 dark:bg-cyan-500 text-white px-3 py-2 rounded cursor-pointer hover:bg-cyan-700 dark:hover:bg-cyan-600 transition"
                @click="$inertia.visit('/regulations/create')">
                Créer un contenu
              </button>

              <button v-if="$page.props.auth.user.role === 'admin'"
                class="bg-gray-700 dark:bg-gray-600 text-white px-3 py-2 rounded cursor-pointer hover:bg-gray-800 dark:hover:bg-gray-700 transition"
                @click="openDeleteModal">
                Supprimer la rubrique
              </button>
            </div>
          </div>

          <!-- Empty -->
          <div v-if="filteredTitles.length === 0" class="text-gray-700 dark:text-gray-300 font-semibold mb-6">
            Aucun règlement trouvé pour "{{ search }}"
          </div>

          <!-- Carousel -->
          <div v-else
            class="relative bg-white/20 dark:bg-gray-800/40 backdrop-blur-md shadow-md border border-gray-200 dark:border-gray-700 rounded-lg p-3 cursor-grab active:cursor-grabbing overflow-hidden">
            <!-- Slides -->
            <div class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${current * 100}%)` }" @touchstart="onTouchStart"
              @touchmove="onTouchMove" @touchend="onTouchEnd">
              <div v-for="t in filteredTitles" :key="t.id" class="w-full flex-shrink-0 px-2 box-border">
                <h2 class="font-bold text-cyan-700 dark:text-cyan-400 text-lg mb-4 animate-fadeIn">
                  {{ t.title }}
                </h2>

                <!-- Contenus -->
                <ul class="space-y-2">
                  <li v-for="c in t.contents" :key="c.id"
                    class="border p-3 rounded bg-white/40 dark:bg-gray-700/40 backdrop-blur-sm animate-slideUp">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                      <div class="font-semibold text-cyan-700 dark:text-cyan-300 mb-2">
                        {{ c.sub_number }}
                      </div>
                      <ul class="flex-1 space-y-1">
                        <li v-for="line in contentLines(c.content)" :key="line"
                          class="text-gray-900 dark:text-gray-100 list-disc list-inside">
                          {{ line }}
                        </li>
                      </ul>

                      <div class="flex gap-2 mt-2 sm:mt-0">
                        <button v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-cyan-600 dark:bg-cyan-500 text-white px-3 py-1 rounded hover:bg-cyan-700 dark:hover:bg-cyan-600 transition"
                          @click="$inertia.visit(`/regulations/content/${c.id}/edit`)">
                          Modifier
                        </button>

                        <!-- now opens modal -->
                        <button v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-gray-700 dark:bg-gray-600 text-white px-3 py-1 rounded hover:bg-gray-800 dark:hover:bg-gray-700 transition"
                          @click="openContentDeleteModal(c)">
                          Supprimer
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Indicators -->
            <div class="flex gap-2 justify-center mt-4">
              <button v-for="(t, i) in filteredTitles" :key="t.id" class="w-3 h-3 rounded-full transition"
                :class="i === current ? 'bg-cyan-600 dark:bg-cyan-400' : 'bg-gray-400 dark:bg-gray-600'"
                @click="goTo(i)" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal supprimer rubrique -->
    <transition name="fade">
      <div v-if="showDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="closeDeleteModal"></div>
        <div class="relative bg-white dark:bg-gray-800 max-w-lg w-full rounded-lg p-6 shadow-lg z-10">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Confirmer la suppression</h3>
          <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
            Voulez-vous vraiment supprimer cette rubrique et tous ses contenus ? Cette action est irréversible.
          </p>

          <div class="flex justify-end gap-3">
            <button class="px-4 py-2 rounded border border-gray-300 dark:border-gray-700" @click="closeDeleteModal">
              Annuler
            </button>
            <button class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded" @click="deleteRegulationConfirm">
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </transition>

    <!-- Modal supprimer contenu -->
    <transition name="fade">
      <div v-if="showContentDeleteModal" class="fixed inset-0 z-50 flex items-center justify-center">
        <div class="absolute inset-0 bg-black/50" @click="closeContentDeleteModal"></div>
        <div class="relative bg-white dark:bg-gray-800 max-w-md w-full rounded-lg p-6 shadow-lg z-10">
          <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">Confirmer la suppression</h3>
          <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
            Voulez-vous vraiment supprimer <strong class="text-gray-900 dark:text-gray-100">{{ selectedContentLabel
              }}</strong> ?
          </p>

          <div class="flex justify-end gap-3">
            <button class="px-4 py-2 rounded border border-gray-300 dark:border-gray-700"
              @click="closeContentDeleteModal">
              Annuler
            </button>
            <button class="px-4 py-2 bg-red-600 hover:bg-red-500 text-white rounded" @click="deleteContentConfirm">
              Supprimer
            </button>
          </div>
        </div>
      </div>
    </transition>
  </AuthenticatedLayout>
</template>

<style>
/* Animations douces */
.animate-fadeIn {
  animation: fadeIn .5s ease;
}

.animate-slideUp {
  animation: slideUp .5s ease;
}

@keyframes fadeIn {
  from {
    opacity: 0
  }

  to {
    opacity: 1
  }
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(10px);
  }

  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* transition pour modal */
.fade-enter-active,
.fade-leave-active {
  transition: opacity .2s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
