<template>
  <div class="flex justify-center items-center">
    <div v-if="!presence" class="text-gray-300">
      <i class="fas fa-minus text-xl opacity-50"></i>
    </div>
    <div v-else-if="isAdmin" class="relative group">
      <!-- Status Icon Button -->
      <button
        ref="buttonRef"
        @click="toggleMenu($event)"
        class="flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-200 p-1 rounded-lg hover:bg-lime-100"
        :title="`${presence.present ? 'Présent' : 'Absent'} ${presence.validated ? '✓' : '⏳'}`"
      >
        <span
          v-if="presence.present && presence.validated"
          class="text-emerald-500 text-2xl"
        >
          <i class="fas fa-circle-check"></i>
        </span>
        <span
          v-else-if="presence.present && !presence.validated"
          class="text-amber-500 text-2xl"
        >
          <i class="fas fa-clock"></i>
        </span>
        <span v-else class="text-red-500 text-2xl">
          <i class="fas fa-circle-xmark"></i>
        </span>
      </button>

      <!-- Dropdown Menu -->
      <div
        ref="menuRef"
        v-if="showMenu"
        class="fixed bg-white border-2 border-lime-300 rounded-xl shadow-2xl z-50 min-w-[180px] overflow-hidden"
        :style="{ top: menuTop + 'px', left: menuLeft + 'px' }"
      >
        <button
          @click="updateStatus(true, true)"
          class="w-full text-left px-4 py-3 hover:bg-emerald-50 flex items-center gap-3 text-emerald-600 text-sm font-bold border-b border-lime-200 transition"
        >
          <i class="fas fa-circle-check text-emerald-500"></i>
          <span>✓ Présent validé</span>
        </button>
        <button
          @click="updateStatus(true, false)"
          class="w-full text-left px-4 py-3 hover:bg-amber-50 flex items-center gap-3 text-amber-600 text-sm font-bold border-b border-lime-200 transition"
        >
          <i class="fas fa-clock text-amber-500"></i>
          <span>⏳ En attente</span>
        </button>
        <button
          @click="updateStatus(false, false)"
          class="w-full text-left px-4 py-3 hover:bg-red-50 flex items-center gap-3 text-red-600 text-sm font-bold transition"
        >
          <i class="fas fa-circle-xmark text-red-500"></i>
          <span>✗ Absent</span>
        </button>
      </div>
      <!-- Declarant badge -->
      <div v-if="presence && presence.declared_by_user_name" class="mt-1 text-[0.65rem] text-gray-500 text-center">
        Déclaré par <span class="font-bold text-gray-700">{{ presence.declared_by_user_name }}</span>
      </div>
    </div>
    <div v-else class="flex flex-col items-center justify-center">
      <!-- Non-admin view -->
      <span
        v-if="presence.present && presence.validated"
        class="text-emerald-500 text-2xl"
      >
        <i class="fas fa-circle-check"></i>
      </span>
      <span
        v-else-if="presence.present && !presence.validated"
        class="text-amber-500 text-2xl"
      >
        <i class="fas fa-clock"></i>
      </span>
      <span v-else class="text-red-500 text-2xl">
        <i class="fas fa-circle-xmark"></i>
      </span>
      <div v-if="presence && presence.declared_by_user_name" class="mt-1 text-[0.65rem] text-gray-500">
        Déclaré par <span class="font-bold text-gray-700">{{ presence.declared_by_user_name }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  presence: Object,
  isAdmin: Boolean,
})

const emit = defineEmits(['update'])

const showMenu = ref(false)
const menuTop = ref(0)
const menuLeft = ref(0)
const menuRef = ref(null)
const buttonRef = ref(null)

const toggleMenu = (e) => {
  if (!showMenu.value) {
    const button = e.target.closest('button')
    if (button) {
      const rect = button.getBoundingClientRect()
      menuTop.value = rect.bottom + 12
      menuLeft.value = rect.left + rect.width / 2 - 90
    }
  }
  showMenu.value = !showMenu.value
}

const updateStatus = (present, validated) => {
  emit('update', props.presence.id, {
    present: present,
    validated_by_admin: validated,
  })
  showMenu.value = false
}

onMounted(() => {
  document.addEventListener('click', (event) => {
    if (menuRef.value && buttonRef.value && !menuRef.value.contains(event.target) && !buttonRef.value.contains(event.target)) {
      showMenu.value = false
    }
  })
})
</script>