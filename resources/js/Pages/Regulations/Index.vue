<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import AdminsideBar from '@/Components/AdminsideBar.vue'

const props = defineProps({
  rules: Array,
  can: Object
})

// Recherche
const search = ref('')

// Titres filtrés
const titles = computed(() =>
  props.rules
    .filter(r => r.content === null)
    .filter(r => r.title.toLowerCase().includes(search.value.toLowerCase()))
)

// Contenus liés
function contentsForTitle(title) {
  return props.rules.filter(r => r.title === title && r.content)
}

// Carousel state
const current = ref(0)
let timer = null
const intervalMs = 5000

function next() {
  if (titles.value.length === 0) return
  current.value = (current.value + 1) % titles.value.length
}

function prev() {
  if (titles.value.length === 0) return
  current.value = (current.value - 1 + titles.value.length) % titles.value.length
}

function goTo(i) {
  if (titles.value.length === 0) return
  current.value = i % titles.value.length
}

function start() {
  stop()
  timer = setInterval(next, intervalMs)
}

function stop() {
  if (timer) clearInterval(timer)
  timer = null
}

// --- Swipe / Drag ---
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
    if (diff > 0) {
      next()
    } else {
      prev()
    }
  }
  startX = 0
  endX = 0
}

onMounted(start)
onUnmounted(stop)
</script>

<template>
  <div class="flex flex-col md:flex-row overflow-x-hidden">
    <div>
      <AdminsideBar />
    </div>

    <div class=" flex-1 min-w-0">
      <div class="p-6  sm:p-3 max-w-5xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6 ">
          <h1 class="text-xl font-bold ">Liste des règlements</h1>
          <button
            v-if="props.can.create"
            class="bg-green-500 text-black px-3 py-2 rounded"
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
              autofill:bg-yellow-100"
      />
        <!-- Empty state -->
        <div v-if="titles.length === 0" class="text-red-500 font-semibold mb-6">
          Aucun règlement trouvé pour "{{ search }}"
        </div>

        <!-- Carousel -->
        <div
          v-else
          class="relative bg-gray-50 shadow-md border rounded-lg p-2  cursor-grab active:cursor-grabbing overflow-hidden"
          @mouseenter="stop"
          @mouseleave="start"
          @mousedown="onTouchStart"
          @mousemove="onTouchMove"
          @mouseup="onTouchEnd"
          @touchstart="onTouchStart"
          @touchmove="onTouchMove"
          @touchend="onTouchEnd"
        >
          <!-- Slides container -->
          <div
            class="flex transition-transform duration-500 ease-in-out w-full"
            :style="{ transform: `translateX(-${current * 100}%)` }"
          >
            <div
              v-for="t in titles"
              :key="t.id"
              class="w-full flex-shrink-0   px-2  box-border"
            >
              <h2 class="font-bold text-green-600 text-lg mb-4">
                {{ t.title }}
              </h2>

              <ul class="space-y-4">
                <li
                  v-for="c in contentsForTitle(t.title)"
                  :key="c.id"
                  class="border p-3 rounded bg-white w-full overflow-hidden"
                >
                  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-2 ">
                    <p class="text-gray-700 flex-1 md:text-1">- {{ c.content }}</p>

                    <div class="flex gap-2">
                      <button
                        v-if="$page.props.auth.user.role === 'admin'"
                        class="bg-green-500 text-gray-700 px-3 py-1 rounded"
                        @click="$inertia.visit(`/regulations/${c.id}/edit`)"
                      >
                        Modifier
                      </button>
                      <button
                        v-if="$page.props.auth.user.role === 'admin'"
                        class="bg-red-600 text-gray-700 px-3 py-1 rounded"
                        @click="$inertia.delete(`/regulations/${c.id}`)"
                      >
                        Supprimer
                      </button>
                    </div>
                  </div>
                </li>
              </ul>
            </div>
          </div>

          <!-- Indicateurs -->
          <div class="flex gap-2 justify-center mt-4">
            <button
              v-for="(t, i) in titles"
              :key="t.id"
              class="w-2.5 h-2.5 rounded-full"
              :class="i === current ? 'bg-green-600' : 'bg-gray-300 hover:bg-gray-400'"
              @click="goTo(i)"
              aria-label="Indicateur de slide"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
