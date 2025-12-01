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

// 🔥 Modal de suppression
const showDeleteModal = ref(false)

function openDeleteModal() {
  showDeleteModal.value = true
}

function closeDeleteModal() {
  showDeleteModal.value = false
}

function deleteRegulation() {
  const selected = filteredTitles.value[current.value]
  if (!selected) return

  router.delete(`/regulations/${selected.id}`, {
    onSuccess: () => {
      showDeleteModal.value = false
    }
  })
}

onMounted(start)
onUnmounted(stop)
</script>

<template>
  <AuthenticatedLayout>
    <div class="flex flex-col md:flex-row overflow-x-hidden">
      <div class="flex-1 min-w-0">
        <div class="p-6 sm:p-3 max-w-5xl mx-auto">

          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold">Liste des règlements</h1>

            <div class="flex gap-2">
              <button
                v-if="props.can.create"
                class="bg-green-500 text-black px-3 py-2 rounded cursor-pointer"
                @click="$inertia.visit('/regulations/create')"
              >
                Créer un contenu
              </button>

              <!-- 🔥 SUPPRESSION -->
              <button
                v-if="$page.props.auth.user.role === 'admin'"
                class="bg-red-600 text-white px-3 py-2 rounded cursor-pointer"
                @click="openDeleteModal"
              >
                Supprimer la rubrique
              </button>
            </div>
          </div>

          <!-- Search -->
          <input
            v-model="search"
            type="text"
            placeholder="Rechercher un règlement..."
            class="border w-full mb-6 rounded p-2 text-base focus:outline-none focus:ring-2 focus:ring-green-500"
          />

          <!-- No search result -->
          <div v-if="filteredTitles.length === 0" class="text-red-500 font-semibold mb-6">
            Aucun règlement trouvé pour "{{ search }}"
          </div>

          <!-- Carousel -->
          <div
            v-else
            class="relative bg-gray-50 shadow-md border rounded-lg p-3 cursor-grab active:cursor-grabbing overflow-hidden"
            @mouseenter="stop"
            @mouseleave="start"
            @mousedown="onTouchStart"
            @mousemove="onTouchMove"
            @mouseup="onTouchEnd"
            @touchstart="onTouchStart"
            @touchmove="onTouchMove"
            @touchend="onTouchEnd"
          >
            <!-- Slides -->
            <div
              class="flex transition-transform duration-700 ease-in-out"
              :style="{ transform: `translateX(-${current * 100}%)` }"
            >
              <div
                v-for="t in filteredTitles"
                :key="t.id"
                class="w-full flex-shrink-0 px-2 box-border"
              >
                <h2 class="font-bold text-green-600 text-lg mb-4 animate-fadeIn">
                  {{ t.title }}
                </h2>

                <!-- Contenus -->
                <ul class="space-y-2">
                  <li
                    v-for="c in t.contents"
                    :key="c.id"
                    class="border p-3 rounded bg-white animate-slideUp"
                  >
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                      <ul class="flex-1 space-y-1">
                        <li
                          v-for="line in contentLines(c.content)"
                          :key="line"
                          class="text-gray-700 list-disc list-inside"
                        >
                          {{ line }}
                        </li>
                      </ul>

                      <div class="flex gap-2 mt-2 sm:mt-0">
                        <button
                          v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-green-500 text-white px-3 py-1 rounded"
                          @click="$inertia.visit(`/regulations/content/${c.id}/edit`)"
                        >
                          Modifier
                        </button>
                        <button
                          v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-red-600 text-white px-3 py-1 rounded"
                          @click="$inertia.delete(`/regulations/content/${c.id}`)"
                        >
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
              <button
                v-for="(t, i) in filteredTitles"
                :key="t.id"
                class="w-3 h-3 rounded-full transition"
                :class="i === current ? 'bg-green-600' : 'bg-gray-300'"
                @click="goTo(i)"
              />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- 🔥 MODAL SUPPRESSION -->
    <div
      v-if="showDeleteModal"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 animate-fadeIn"
    >
      <div class="bg-white p-6 rounded-lg shadow-lg w-96 animate-scaleIn">
        <h2 class="text-lg font-bold mb-4 text-red-600">Supprimer la rubrique ?</h2>
        <p class="mb-6 text-gray-700">
          Cette action supprimera définitivement le règlement et tous ses contenus associés.
          Êtes-vous sûr ?
        </p>

        <div class="flex justify-end gap-3">
          <button
            class="px-4 py-2 rounded bg-gray-300"
            @click="closeDeleteModal"
          >
            Annuler
          </button>

          <button
            class="px-4 py-2 rounded bg-red-600 text-white"
            @click="deleteRegulation"
          >
            Oui, supprimer
          </button>
        </div>
      </div>
    </div>
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

.animate-scaleIn {
  animation: scaleIn .3s ease;
}

@keyframes fadeIn {
  from { opacity: 0 }
  to { opacity: 1 }
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
  from { transform: scale(0.8); opacity: 0; }
  to { transform: scale(1); opacity: 1; }
}
</style>
