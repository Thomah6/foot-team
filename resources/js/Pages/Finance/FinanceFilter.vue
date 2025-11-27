<script setup>
const props = defineProps({
  users: Array
})
const emit = defineEmits(['filter'])
const selectedUser = ref('')
const dateFrom = ref('')
const dateTo = ref('')
function applyFilter() {
  emit('filter', { selectedUser: selectedUser.value, dateFrom: dateFrom.value, dateTo: dateTo.value })
}
</script>
<template>
  <div class="flex flex-wrap gap-4 items-end">
    <div class="flex-1 min-w-[200px]">
      <label class="block text-sm font-medium mb-1">Filtrer par membre</label>
      <select v-model="selectedUser" class="block w-full rounded border-neutral-200">
        <option value="">Tous les membres</option>
        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
      </select>
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="block text-sm font-medium mb-1">Du</label>
      <input type="date" v-model="dateFrom" class="block w-full rounded border-neutral-200" />
    </div>
    <div class="flex-1 min-w-[150px]">
      <label class="block text-sm font-medium mb-1">Au</label>
      <input type="date" v-model="dateTo" class="block w-full rounded border-neutral-200" />
    </div>
    <button @click="applyFilter" class="h-10 px-4 border border-primary text-primary font-bold rounded-lg ml-1">Filtrer</button>
  </div>
</template>
