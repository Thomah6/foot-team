<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

const props = defineProps({
  titles: {       // On passe maintenant directement les titres depuis le backend
    type: Array,
    default: () => []
  },
  can: Object
})

// Recherche
const search = ref('')

// Filtrer les titres selon la recherche
const filteredTitles = computed(() =>
  (props.titles || []).filter(t =>
    t.title.toLowerCase().includes(search.value.toLowerCase())
  )
)

// Découper le contenu en lignes
function contentLines(content) {
  if (!content) return []
  return content.split('\n').map(line => line.trim()).filter(line => line.length > 0)
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
  if (filteredTitles.value.length === 0) return
  current.value = i % filteredTitles.value.length
}

function start() {
  stop()
  timer = setInterval(next, intervalMs)
}

function stop() {
  if (timer) clearInterval(timer)
  timer = null
}

// Swipe / Drag
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
    if (diff > 0) next()
    else prev()
  }
  startX = 0
  endX = 0
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
            <button
              v-if="props.can.create"
              class="bg-green-500 text-black px-3 py-2 rounded cursor-pointer"
              @click="$inertia.visit('/regulations/create')"
            >
              Créer un contenu
            </button>
          </div>

          <!-- Search -->
          <input
            v-model="search"
            type="text"
            placeholder="Rechercher un règlement..."
            class="border w-full mb-6 rounded p-2 text-base
                   max-sm:p-1 max-sm:text-sm
                   focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500
                   autofill:bg-yellow-100 dark:bg-gray-700 dark:text-gray-100 dark:border-gray-600"
          />

          <!-- Empty state -->
          <div v-if="filteredTitles.length === 0" class="text-red-500 font-semibold mb-6">
            Aucun règlement trouvé pour "{{ search }}"
          </div>

          <!-- Carousel -->
          <div
            v-else
            class="relative bg-gray-50 dark:bg-gray-800 shadow-md border dark:border-gray-600 rounded-lg p-2 cursor-grab active:cursor-grabbing overflow-hidden"
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
              class="flex transition-transform duration-500 ease-in-out w-full"
              :style="{ transform: `translateX(-${current * 100}%)` }"
            >
              <div
                v-for="t in filteredTitles"
                :key="t.id"
                class="w-full flex-shrink-0 px-2 box-border"
              >
                <h2 class="font-bold text-green-600 text-lg mb-4">{{ t.title }}</h2>
                <span>Voici : {{ t.sub_number }}</span>

                <!-- Contenus -->
                <ul class="space-y-2">
                  <li
                    v-for="c in t.contents || []"
                    :key="c.id"
                    class="border p-3 rounded bg-white dark:bg-gray-700"
                  >
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                      <ul class="flex-1 space-y-1">
                         <li
                          v-for="line in contentLines(c.content)"
                          :key="line"
                          class="text-gray-700 dark:text-gray-200 list-disc list-inside"
                        >
                           {{ line }}
                        </li>
                      </ul>

                      <div class="flex gap-2 mt-2 sm:mt-0">
                        <button
                          v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-green-500 text-gray-700 px-3 py-1 rounded"
                          @click="$inertia.visit(`/regulations/content/${c.id}/edit`)"
                        >
                          Modifier
                        </button>
                        <button
                          v-if="$page.props.auth.user.role === 'admin'"
                          class="bg-red-600 text-gray-700 px-3 py-1 rounded"
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
                class="w-2.5 h-2.5 rounded-full"
                :class="i === current ? 'bg-green-600' : 'bg-gray-300 hover:bg-gray-400 dark:bg-gray-500 dark:hover:bg-gray-400'"
                @click="goTo(i)"
                aria-label="Indicateur de slide"
              />
            </div>

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
