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
    <div class="relative flex flex-col md:flex-row overflow-x-hidden min-h-screen">
      
      <!-- Vidéo en background -->
      <video autoplay muted loop playsinline 
             class="absolute inset-0 w-full h-full object-cover">
        <source src="https://dm0qx8t0i9gc9.cloudfront.net/watermarks/video/HZHnL4R6xj1hrqjd5/videoblocks-63277d369467e60a6bd204a6_b6lw8rr2ms__2e614f57e25a88afa5e7613d4f09343d__P360.mp4" type="video/mp4" />
        Votre navigateur ne supporte pas la vidéo.
      </video>

      <!-- Overlay translucide -->
      <div class="absolute inset-0 bg-white/40 dark:bg-black/40 backdrop-blur-sm"></div>

      <!-- Contenu -->
      <div class="relative flex-1 min-w-0 z-10 bg-white/30 dark:bg-gray-900/30 min-h-full">
        <div class="p-6 sm:p-8 max-w-5xl mx-auto text-gray-900 dark:text-gray-100">
          
          <!-- Header -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
              <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-citron-50">📋 Liste des règlements</h1>
              <p class="text-gray-600 dark:text-citron-300 text-sm mt-1">Consultez et gérez les règlements de l'organisation</p>
            </div>

            <div class="flex flex-wrap gap-2">
              <button
                v-if="props.can.create"
                class="bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white px-4 py-2 rounded-lg cursor-pointer transition font-semibold flex items-center gap-2"
                @click="$inertia.visit('/regulations/create')"
              >
                <i class="fas fa-plus"></i>
                Créer un règlement
              </button>

              <button
                v-if="$page.props.auth.user.role === 'admin' && filteredTitles.length > 0"
                class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-4 py-2 rounded-lg cursor-pointer transition font-semibold flex items-center gap-2"
                @click="openDeleteModal"
              >
                <i class="fas fa-trash"></i>
                Supprimer la rubrique
              </button>
            </div>
          </div>

          <!-- Search -->
          <div class="relative mb-8">
            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 dark:text-citron-500"></i>
            <input
              v-model="search"
              type="text"
              placeholder="Rechercher un règlement..."
              class="w-full pl-12 pr-4 py-3 rounded-lg 
                     text-black dark:text-white 
                     bg-white dark:bg-gray-800 
                     border border-gray-300 dark:border-gray-600 
                     focus:outline-none focus:ring-2 focus:ring-citron-500 focus:border-transparent transition"
            />
          </div>

          <!-- Résultats -->
          <div v-if="filteredTitles.length === 0" class="text-center py-12 bg-white/20 dark:bg-gray-800/40 backdrop-blur-md rounded-lg border border-gray-200 dark:border-gray-700">
            <i class="fas fa-search text-4xl text-gray-400 dark:text-citron-700 mb-4"></i>
            <p class="text-gray-700 dark:text-citron-300 font-semibold text-lg mb-2">Aucun règlement trouvé</p>
            <p class="text-gray-600 dark:text-citron-400 text-sm">"{{ search }}" ne correspond à aucun règlement</p>
          </div>

          <!-- Carousel -->
          <div v-else 
               class="relative bg-white/20 dark:bg-gray-800/40 backdrop-blur-md shadow-lg border border-gray-200 dark:border-gray-700 rounded-xl p-4 sm:p-6 cursor-grab active:cursor-grabbing overflow-hidden"
               @touchstart="onTouchStart"
               @touchmove="onTouchMove"
               @touchend="onTouchEnd"
               @mousedown="onTouchStart"
               @mousemove="onTouchMove"
               @mouseup="onTouchEnd"
               @mouseleave="onTouchEnd">
            
            <!-- Slides -->
            <div class="flex transition-transform duration-700 ease-in-out"
                 :style="{ transform: `translateX(-${current * 100}%)` }">
              <div v-for="t in filteredTitles" :key="t.id" class="w-full flex-shrink-0 px-2 sm:px-4 box-border">
                <div class="mb-6">
                  <h2 class="font-bold text-citron-700 dark:text-citron-400 text-xl sm:text-2xl mb-2 animate-fadeIn">
                    {{ t.title }}
                  </h2>
                  <div class="h-1 w-24 bg-citron-500 dark:bg-citron-400 rounded-full"></div>
                </div>

                <!-- Contenus -->
                <ul class="space-y-4">
                  <li v-for="c in t.contents" :key="c.id" 
                      class="border border-gray-300 dark:border-gray-700 p-4 sm:p-5 rounded-xl bg-white/40 dark:bg-gray-700/40 backdrop-blur-sm animate-slideUp hover:shadow-lg transition-shadow">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4">
                      <div class="flex-1">
                        <div class="flex items-center gap-2 mb-3">
                          <span class="bg-citron-100 dark:bg-citron-900/50 text-citron-700 dark:text-citron-300 text-xs font-bold px-3 py-1 rounded-full border border-citron-200 dark:border-citron-800">
                            {{ c.sub_number || 'Sans numéro' }}
                          </span>
                        </div>
                        
                        <ul class="space-y-2">
                          <li v-for="line in contentLines(c.content)" :key="line" 
                              class="text-gray-900 dark:text-citron-100 flex items-start gap-2">
                            <span class="text-citron-500 dark:text-citron-400 mt-1">•</span>
                            <span>{{ line }}</span>
                          </li>
                        </ul>
                      </div>

                      <div class="flex gap-2 mt-4 sm:mt-0">
                        <button v-if="$page.props.auth.user.role === 'admin'"
                                class="bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white px-3 py-2 rounded-lg transition font-semibold text-sm flex items-center gap-2"
                                @click="$inertia.visit(`/regulations/content/${c.id}/edit`)">
                          <i class="fas fa-edit"></i>
                          Modifier
                        </button>
                        <button v-if="$page.props.auth.user.role === 'admin'"
                                class="bg-red-600 hover:bg-red-700 dark:bg-red-500 dark:hover:bg-red-600 text-white px-3 py-2 rounded-lg transition font-semibold text-sm flex items-center gap-2"
                                @click="$inertia.delete(`/regulations/content/${c.id}`)">
                          <i class="fas fa-trash"></i>
                          Supprimer
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>

            <!-- Navigation buttons -->
            <button @click="prev" class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 text-citron-700 dark:text-citron-400 p-2 rounded-full shadow-lg hover:bg-white dark:hover:bg-gray-700 transition">
              <i class="fas fa-chevron-left text-lg"></i>
            </button>
            <button @click="next" class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-white/80 dark:bg-gray-800/80 text-citron-700 dark:text-citron-400 p-2 rounded-full shadow-lg hover:bg-white dark:hover:bg-gray-700 transition">
              <i class="fas fa-chevron-right text-lg"></i>
            </button>

            <!-- Indicators -->
            <div class="flex gap-2 justify-center mt-6 sm:mt-8">
              <button v-for="(t, i) in filteredTitles" :key="t.id"
                      class="w-3 h-3 rounded-full transition-all duration-300"
                      :class="i === current ? 'bg-citron-600 dark:bg-citron-400 scale-110' : 'bg-gray-400 dark:bg-gray-600 hover:bg-gray-500 dark:hover:bg-gray-500'"
                      @click="goTo(i)"
                      :aria-label="`Aller au règlement ${i + 1}: ${t.title}`" />
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>