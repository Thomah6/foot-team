<template>
  <div class="flex justify-center items-center">
    <div v-if="!presence" class="text-slate-300 dark:text-slate-600">
      <span class="material-symbols-outlined text-lg opacity-50">remove</span>
    </div>
    <div v-else-if="isAdmin" class="relative group">
      <!-- Status Icon Button -->
      <button
        @click="toggleMenu($event)"
        class="flex items-center justify-center cursor-pointer hover:scale-110 transition-transform duration-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-700"
        :title="`${presence.present ? 'Présent' : 'Absent'} ${presence.validated ? '✓' : '⏳'}`"
      >
        <span
          v-if="presence.present && presence.validated"
          class="material-symbols-outlined fill text-green-500 text-2xl"
        >
          check_circle
        </span>
        <span
          v-else-if="presence.present && !presence.validated"
          class="material-symbols-outlined fill text-amber-500 text-2xl"
        >
          schedule
        </span>
        <span v-else class="material-symbols-outlined fill text-red-500 text-2xl">
          cancel
        </span>
      </button>

      <!-- Dropdown Menu -->
      <div
        v-if="showMenu"
        class="fixed bg-white dark:bg-slate-800 border-2 border-slate-200 dark:border-slate-600 rounded-xl shadow-2xl z-50 min-w-[180px] overflow-hidden"
        :style="{ top: menuTop + 'px', left: menuLeft + 'px' }"
      >
        <button
          @click="updateStatus(true, true)"
          class="w-full text-left px-4 py-3 hover:bg-green-50 dark:hover:bg-green-900/30 flex items-center gap-3 text-green-600 dark:text-green-300 text-sm font-semibold border-b border-slate-200 dark:border-slate-600 transition"
        >
          <span class="material-symbols-outlined fill text-lg">check_circle</span>
          <span>✓ Présent validé</span>
        </button>
        <button
          @click="updateStatus(true, false)"
          class="w-full text-left px-4 py-3 hover:bg-amber-50 dark:hover:bg-amber-900/30 flex items-center gap-3 text-amber-600 dark:text-amber-300 text-sm font-semibold border-b border-slate-200 dark:border-slate-600 transition"
        >
          <span class="material-symbols-outlined fill text-lg">schedule</span>
          <span>⏳ Attente</span>
        </button>
        <button
          @click="updateStatus(false, false)"
          class="w-full text-left px-4 py-3 hover:bg-red-50 dark:hover:bg-red-900/30 flex items-center gap-3 text-red-600 dark:text-red-300 text-sm font-semibold transition"
        >
          <span class="material-symbols-outlined fill text-lg">cancel</span>
          <span>✗ Absent</span>
        </button>
      </div>
      <!-- Declarant badge -->
      <div v-if="presence && presence.declared_by_user_name" class="mt-1 text-[0.65rem] text-slate-500 dark:text-slate-400 text-center">
        Déclaré par <span class="font-semibold text-slate-700 dark:text-white">{{ presence.declared_by_user_name }}</span>
      </div>
    </div>
    <div v-else class="flex items-center justify-center">
      <!-- Non-admin view -->
      <span
        v-if="presence.present && presence.validated"
        class="material-symbols-outlined fill text-green-500 text-2xl"
      >
        check_circle
      </span>
      <span
        v-else-if="presence.present && !presence.validated"
        class="material-symbols-outlined fill text-amber-500 text-2xl"
      >
        schedule
      </span>
      <span v-else class="material-symbols-outlined fill text-red-500 text-2xl">
        cancel
      </span>
      <div v-if="presence && presence.declared_by_user_name" class="ml-2 text-[0.65rem] text-slate-500 dark:text-slate-400">
        Déclaré par <span class="font-semibold text-slate-700 dark:text-white">{{ presence.declared_by_user_name }}</span>
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

const toggleMenu = (e) => {
  if (!showMenu.value) {
    // Calculate position
    const button = e.target.closest('button')
    if (button) {
      const rect = button.getBoundingClientRect()
      menuTop.value = rect.bottom + 12 // 12px below button
      menuLeft.value = rect.left + rect.width / 2 - 90 // Center (180/2 = 90)
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
