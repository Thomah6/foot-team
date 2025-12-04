<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
    users: Array,
    filters: Object,
})

const emit = defineEmits(['filter-change'])

const selectedUser = ref(props.filters.member_id || '')
const dateFrom = ref(props.filters.date_from || '')
const dateTo = ref(props.filters.date_to || '')
const selectedType = ref(props.filters.type || '')

watch([selectedUser, dateFrom, dateTo, selectedType], () => {
    emit('filter-change', { 
        selectedUser: selectedUser.value, 
        dateFrom: dateFrom.value, 
        dateTo: dateTo.value, 
        selectedType: selectedType.value 
    })
})

</script>
<template>
    <div class="relative overflow-hidden rounded-2xl bg-slate-50 dark:bg-gray-900/95 backdrop-blur-sm border-2 border-lime-300 dark:border-emerald-800/60 p-6 shadow-lg">
        <!-- Header -->
        <div class="mb-6">
            <h3 class="text-lg font-black text-slate-950 dark:text-white flex items-center gap-2">
                <i class="fas fa-filter text-lime-700 dark:text-emerald-400"></i>
                FILTRES AVANCÉS
            </h3>
            <p class="text-sm text-slate-700 dark:text-gray-400 mt-1">
                Affinez les résultats selon vos besoins
            </p>
        </div>

        <!-- Filters Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Membre Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-lime-800 dark:text-emerald-400 mb-2">
                    <i class="fas fa-user mr-1"></i>
                    MEMBRE
                </label>
                <div class="relative">
                    <select v-model="selectedUser" 
                            class="w-full px-4 py-3 pl-10 bg-white dark:bg-gray-800 border-2 border-lime-300 dark:border-emerald-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 appearance-none focus:border-lime-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-lime-200 dark:focus:ring-emerald-900/40 outline-none transition-all cursor-pointer">
                        <option value="" class="text-slate-950 dark:text-white">Tous les membres</option>
                        <option v-for="user in users" :key="user.id" :value="user.id" class="text-slate-950 dark:text-white">{{ user.name }}</option>
                    </select>
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-700 dark:text-emerald-400 pointer-events-none">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-lime-800 dark:text-emerald-400 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    DATE DEBUT
                </label>
                <div class="relative">
                    <input type="date" v-model="dateFrom" 
                           class="w-full px-4 py-3 pl-10 bg-white dark:bg-gray-800 border-2 border-lime-300 dark:border-emerald-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 focus:border-lime-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-lime-200 dark:focus:ring-emerald-900/40 outline-none transition-all">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-700 dark:text-emerald-400 pointer-events-none">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-lime-800 dark:text-emerald-400 mb-2">
                    <i class="fas fa-calendar-day mr-1"></i>
                    DATE FIN
                </label>
                <div class="relative">
                    <input type="date" v-model="dateTo" 
                           class="w-full px-4 py-3 pl-10 bg-white dark:bg-gray-800 border-2 border-lime-300 dark:border-emerald-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 focus:border-lime-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-lime-200 dark:focus:ring-emerald-900/40 outline-none transition-all">
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-700 dark:text-emerald-400 pointer-events-none">
                        <i class="fas fa-stop"></i>
                    </div>
                </div>
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-lime-800 dark:text-emerald-400 mb-2">
                    <i class="fas fa-tag mr-1"></i>
                    TYPE
                </label>
                <div class="relative">
                    <select v-model="selectedType" 
                            class="w-full px-4 py-3 pl-10 bg-white dark:bg-gray-800 border-2 border-lime-300 dark:border-emerald-800/60 rounded-xl text-slate-950 dark:text-white placeholder-slate-500 dark:placeholder-gray-400 appearance-none focus:border-lime-500 dark:focus:border-emerald-500 focus:ring-2 focus:ring-lime-200 dark:focus:ring-emerald-900/40 outline-none transition-all cursor-pointer">
                        <option value="" class="text-slate-950 dark:text-white">Tous les types</option>
                        <option value="cotisation" class="text-slate-950 dark:text-white">Cotisation</option>
                        <option value="dépense" class="text-slate-950 dark:text-white">Dépense</option>
                    </select>
                    <div class="absolute left-3 top-1/2 -translate-y-1/2 text-lime-700 dark:text-emerald-400 pointer-events-none">
                        <i class="fas fa-filter"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Custom date input styling */
input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    position: absolute;
    width: 100%;
    height: 100%;
    left: 0;
    cursor: pointer;
}

/* Custom select styling */
select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%23991b1b' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.75rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
}

/* Dark mode select icon */
@media (prefers-color-scheme: dark) {
    select {
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%2310b981' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .grid-cols-1 {
        gap: 1rem;
    }
    
    .p-6 {
        padding: 1.25rem;
    }
    
    input, select {
        font-size: 0.875rem;
        min-height: 48px;
    }
}
</style>
