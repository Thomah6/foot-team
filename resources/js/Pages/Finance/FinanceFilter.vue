<script setup>
import { ref } from 'vue'


const props = defineProps({
  users: Array
})
const emit = defineEmits(['filter'])
const selectedUser = ref('')
const dateFrom = ref('')
const dateTo = ref('')
const selectedType = ref('')
function applyFilter() {
  emit('filter', { selectedUser: selectedUser.value, dateFrom: dateFrom.value, dateTo: dateTo.value, selectedType: selectedType.value })
}
</script>
<template>
  <div class="flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[200px]">
      <label class="block text-sm font-medium mb-1 text-neutral-900 dark:text-white">Filtrer par membre</label>
      <select v-model="selectedUser" class="block w-full rounded border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-neutral-900 dark:text-white">
        <option value="">Tous les membres</option>
        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
      </select>
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="block text-sm font-medium mb-1 text-neutral-900 dark:text-white">Du</label>
      <input type="date" v-model="dateFrom" class="block w-full rounded border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-neutral-900 dark:text-white" />
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="block text-sm font-medium mb-1 text-neutral-900 dark:text-white">Au</label>
      <input type="date" v-model="dateTo" class="block w-full rounded border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-neutral-900 dark:text-white" />
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="block text-sm font-medium mb-1 text-neutral-900 dark:text-white">Type</label>
      <select v-model="selectedType" class="block w-full rounded border border-neutral-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-neutral-900 dark:text-white">
        <option value="">Tous les types</option>
        <option value="cotisation">Cotisation</option>
        <option value="dépense">Dépense</option>
      </select>
    </div>
    <button @click="applyFilter" class="h-10 px-4 border border-primary dark:border-blue-500 text-primary dark:text-blue-400 font-bold rounded-lg ml-1 hover:bg-blue-50 dark:hover:bg-slate-800">Filtrer</button>
  </div>
</template>
