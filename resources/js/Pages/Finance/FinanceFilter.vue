<script setup>
import { ref } from 'vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

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
      <InputLabel>Filtrer par membre</InputLabel>
      <select v-model="selectedUser" class="block w-full rounded border border-neutral-200 form-input">
        <option value="">Tous les membres</option>
        <option v-for="user in users" :value="user.id">{{ user.name }}</option>
      </select>
    </div>
    <div class="flex-1 min-w-[150px]">
      <InputLabel>Du</InputLabel>
      <TextInput type="date" v-model="dateFrom" />
    </div>
    <div class="flex-1 min-w-[150px]">
      <InputLabel>Au</InputLabel>
      <TextInput type="date" v-model="dateTo" />
    </div>
    <div class="flex-1 min-w-[150px]">
      <InputLabel>Type</InputLabel>
      <select v-model="selectedType" class="block w-full rounded border border-neutral-200 form-input">
        <option value="">Tous les types</option>
        <option value="cotisation">Cotisation</option>
        <option value="dépense">Dépense</option>
      </select>
    </div>
    <PrimaryButton @click="applyFilter" class="ml-1">Filtrer</PrimaryButton>
  </div>
</template>
