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

      <!-- Overlay blanc translucide -->
      <div class="absolute inset-0 bg-white/40 backdrop-blur-sm ">

     

      <!-- Contenu -->
      <div class="relative flex-1 min-w-0 z-10 bg-white/30  min-h-full">
        <div class="p-6 sm:p-3 max-w-5xl mx-auto text-gray-900">
          
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-xl font-bold">Liste des règlements</h1>

            <div class="flex gap-2">
              <button
                v-if="props.can.create"
                class="bg-cyan-600 text-white px-3 py-2 rounded cursor-pointer hover:bg-cyan-700 transition"
                @click="$inertia.visit('/regulations/create')"
              >
                Créer un contenu
              </button>

              <button
                v-if="$page.props.auth.user.role === 'admin'"
                class="bg-gray-700 text-white px-3 py-2 rounded cursor-pointer hover:bg-gray-800 transition"
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
            class="border w-full mb-6 rounded p-2 text-black focus:outline-none focus:ring-2 focus:ring-cyan-500"
          />

          <!-- Résultats -->
          <div v-if="filteredTitles.length === 0" class="text-gray-700 font-semibold mb-6">
            Aucun règlement trouvé pour "{{ search }}"
          </div>

          <!-- Carousel -->
          <div v-else class="relative bg-white/20 backdrop-blur-md shadow-md border rounded-lg p-3 cursor-grab active:cursor-grabbing overflow-hidden">
            <!-- Slides -->
            <div class="flex transition-transform duration-700 ease-in-out"
                 :style="{ transform: `translateX(-${current * 100}%)` }">
              <div v-for="t in filteredTitles" :key="t.id" class="w-full flex-shrink-0 px-2 box-border">
                <h2 class="font-bold text-cyan-700 text-lg mb-4 animate-fadeIn">
                  {{ t.title }}
                </h2>

                <!-- Contenus -->
                <ul class="space-y-2">
                  <li v-for="c in t.contents" :key="c.id" 
                      class="border p-3 rounded bg-white/40 backdrop-blur-sm animate-slideUp">
                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-2">
                      <ul class="flex-1 space-y-1">
                        <li v-for="line in contentLines(c.content)" :key="line" class="text-gray-900 list-disc list-inside">
                          {{ line }}
                        </li>
                      </ul>

                      <div class="flex gap-2 mt-2 sm:mt-0">
                        <button v-if="$page.props.auth.user.role === 'admin'"
                                class="bg-cyan-600 text-white px-3 py-1 rounded hover:bg-cyan-700 transition"
                                @click="$inertia.visit(`/regulations/content/${c.id}/edit`)">
                          Modifier
                        </button>
                        <button v-if="$page.props.auth.user.role === 'admin'"
                                class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800 transition"
                                @click="$inertia.delete(`/regulations/content/${c.id}`)">
                          Supprimer
                        </button>
                      </div>
                    </div>
                  </li>
                </ul>
              </div>
            </div>
 </div>
            <!-- Indicators -->
            <div class="flex gap-2 justify-center mt-4">
              <button v-for="(t, i) in filteredTitles" :key="t.id"
                      class="w-3 h-3 rounded-full transition"
                      :class="i === current ? 'bg-cyan-600' : 'bg-gray-400'"
                      @click="goTo(i)" />
            </div>
          </div>
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
