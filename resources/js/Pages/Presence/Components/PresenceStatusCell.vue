<template>
  <div class="flex justify-center items-center">
    <div v-if="!presence" class="text-slate-400">-</div>
    <div v-else-if="isAdmin" class="relative group">
      <!-- Status Icon with Tooltip -->
      <button
        @click="toggleMenu"
        class="flex items-center justify-center cursor-pointer hover:opacity-75 transition"
      >
        <span
          v-if="presence.present && presence.validated"
          class="material-symbols-outlined fill text-green-500"
        >
          check_circle
        </span>
        <span
          v-else-if="presence.present && !presence.validated"
          class="material-symbols-outlined fill text-yellow-500"
        >
          schedule
        </span>
        <span v-else class="material-symbols-outlined fill text-red-500"> cancel </span>
      </button>

      <!-- Dropdown Menu -->
      <div
        v-if="showMenu"
        class="fixed bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg shadow-lg z-50 min-w-[150px]"
        :style="{ top: menuTop + 'px', left: menuLeft + 'px' }"
      >
        <button
          @click="updateStatus(true, true)"
          class="w-full text-left px-4 py-2 hover:bg-green-50 dark:hover:bg-green-900/30 flex items-center gap-2 text-green-600 text-sm"
        >
          <span class="material-symbols-outlined fill text-sm">check_circle</span>
          Présent validé
        </button>
        <button
          @click="updateStatus(true, false)"
          class="w-full text-left px-4 py-2 hover:bg-yellow-50 dark:hover:bg-yellow-900/30 flex items-center gap-2 text-yellow-600 text-sm border-t border-slate-200 dark:border-slate-600"
        >
          <span class="material-symbols-outlined fill text-sm">schedule</span>
          Présent en attente
        </button>
        <button
          @click="updateStatus(false, false)"
          class="w-full text-left px-4 py-2 hover:bg-red-50 dark:hover:bg-red-900/30 flex items-center gap-2 text-red-600 text-sm border-t border-slate-200 dark:border-slate-600"
        >
          <span class="material-symbols-outlined fill text-sm">cancel</span>
          Absent
        </button>
      </div>
    </div>
    <div v-else>
      <!-- Non-admin view -->
      <span
        v-if="presence.present && presence.validated"
        class="material-symbols-outlined fill text-green-500"
      >
        check_circle
      </span>
      <span
        v-else-if="presence.present && !presence.validated"
        class="material-symbols-outlined fill text-yellow-500"
      >
        schedule
      </span>
      <span v-else class="material-symbols-outlined fill text-red-500"> cancel </span>
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

const toggleMenu = () => {
  if (!showMenu.value) {
    // Calculate position
    const button = event.target.closest('button')
    if (button) {
      const rect = button.getBoundingClientRect()
      menuTop.value = rect.bottom + 8 // 8px below button
      menuLeft.value = rect.left + rect.width / 2 - 75 // Center (150/2 = 75)
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
</script>
