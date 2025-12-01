<template>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- PageHeading -->
      <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex flex-col gap-3">
          <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-4xl text-purple-600 dark:text-purple-400">assessment</span>
            <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-tight text-[#111318] dark:text-white">
              Rapport Mensuel
            </h1>
          </div>
          <p class="text-[#636f88] dark:text-slate-400 text-sm md:text-base font-normal leading-normal">
            Statistiques d'assiduité du mois • Analyse détaillée
          </p>
        </div>
        <Link
          :href="route('presence.index')"
          class="flex w-full md:w-auto min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white text-sm font-bold leading-normal tracking-[0.015em] border border-blue-600 dark:border-blue-700 shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700 transition-all gap-2"
        >
          <span class="material-symbols-outlined text-base">calendar_month</span>
          <span class="truncate">Calendrier</span>
        </Link>
      </div>

      <!-- Month Info -->
      <div class="bg-gradient-to-r from-purple-50 to-purple-100/50 dark:from-purple-900/20 dark:to-purple-800/10 border border-purple-200 dark:border-purple-800 rounded-xl p-5 shadow-sm">
        <div class="flex items-center gap-3">
          <span class="material-symbols-outlined text-2xl text-purple-600 dark:text-purple-400">calendar_today</span>
          <div>
            <p class="text-xs font-semibold text-purple-700 dark:text-purple-300 uppercase tracking-wide">Mois analysé</p>
            <p class="text-2xl font-black text-[#111318] dark:text-white">
              {{ formatMonth(month) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-green-700 dark:text-green-300 uppercase tracking-wide">Présences totales</p>
            <span class="material-symbols-outlined text-3xl text-green-500">check_circle</span>
          </div>
          <p class="text-4xl font-black text-green-600 dark:text-green-300">
            {{ calculateTotalPresent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-red-700 dark:text-red-300 uppercase tracking-wide">Absences totales</p>
            <span class="material-symbols-outlined text-3xl text-red-500">cancel</span>
          </div>
          <p class="text-4xl font-black text-red-600 dark:text-red-300">
            {{ calculateTotalAbsent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border border-amber-200 dark:border-amber-800 rounded-xl p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-amber-700 dark:text-amber-300 uppercase tracking-wide">En attente</p>
            <span class="material-symbols-outlined text-3xl text-amber-500">schedule</span>
          </div>
          <p class="text-4xl font-black text-amber-600 dark:text-amber-300">
            {{ calculateTotalPending() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-6 hover:shadow-lg transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300 uppercase tracking-wide">Taux moyen</p>
            <span class="material-symbols-outlined text-3xl text-blue-500">trending_up</span>
          </div>
          <p class="text-4xl font-black text-blue-600 dark:text-blue-300">
            {{ calculateAveragePercentage() }}<span class="text-lg">%</span>
          </p>
        </div>
      </div>

      <!-- Report Table -->
      <div class="w-full @container">
        <div class="flex overflow-hidden rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-md">
          <table class="w-full border-collapse">
            <thead class="border-b-2 border-slate-300 dark:border-slate-600 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700">
              <tr>
                <th class="px-4 py-4 text-left text-[#111318] dark:text-white font-black text-sm sticky left-0 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700 z-10">
                  <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined">group</span>
                    <span>Joueur</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <span class="material-symbols-outlined text-lg">check_circle</span>
                    <span class="text-xs">Présent</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <span class="material-symbols-outlined text-lg">cancel</span>
                    <span class="text-xs">Absent</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <span class="material-symbols-outlined text-lg">verified</span>
                    <span class="text-xs">Validé</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <span class="material-symbols-outlined text-lg">trending_up</span>
                    <span class="text-xs">Taux</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in report"
                :key="row.user_id"
                :class="[
                  'border-t border-slate-200/80 dark:border-white/10 transition hover:bg-purple-50/50 dark:hover:bg-purple-900/20',
                  index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : '',
                ]"
              >
                <td class="px-4 py-4 text-[#111318] dark:text-white text-sm font-semibold sticky left-0 z-10" :class="[index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : 'bg-white dark:bg-background-dark']">
                  <div class="flex items-center gap-2 truncate">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                      {{ row.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="truncate">{{ row.user_name }}</span>
                  </div>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg text-sm font-semibold">
                    {{ row.present }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-lg text-sm font-semibold">
                    {{ row.absent }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg text-sm font-semibold">
                    {{ row.validated }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <div class="flex items-center justify-center gap-3">
                    <div class="w-20 h-3 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden shadow-inner">
                      <div
                        class="h-full transition-all duration-500 rounded-full"
                        :style="[
                          `width: ${row.percentage}%`,
                          row.percentage >= 80 ? 'background: linear-gradient(90deg, #10b981, #059669);' :
                          row.percentage >= 60 ? 'background: linear-gradient(90deg, #f59e0b, #d97706);' :
                          'background: linear-gradient(90deg, #ef4444, #dc2626);'
                        ]"
                      ></div>
                    </div>
                    <span class="text-sm font-black text-[#111318] dark:text-white min-w-[45px] text-right">
                      {{ row.percentage }}<span class="text-xs">%</span>
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Export Button -->
      <div class="flex justify-end">
        <button
          @click="downloadCSV"
          class="flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-purple-600 to-purple-700 dark:from-purple-700 dark:to-purple-800 text-white rounded-lg font-semibold text-sm shadow-md hover:shadow-lg hover:from-purple-700 hover:to-purple-800 dark:hover:from-purple-600 dark:hover:to-purple-700 transition-all active:scale-95"
        >
          <span class="material-symbols-outlined text-lg">download</span>
          Télécharger (CSV)
        </button>
      </div>
    </div>
  </main>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

const props = defineProps({
  report: Array,
  month: String,
})

const formatMonth = (date) => {
  const d = new Date(date)
  const formatter = new Intl.DateTimeFormat('fr-FR', {
    month: 'long',
    year: 'numeric',
  })
  return formatter.format(d).charAt(0).toUpperCase() + formatter.format(d).slice(1)
}

const calculateTotalPresent = () => {
  return props.report.reduce((sum, row) => sum + row.present, 0)
}

const calculateTotalAbsent = () => {
  return props.report.reduce((sum, row) => sum + row.absent, 0)
}

const calculateTotalPending = () => {
  return props.report.reduce((sum, row) => sum + (row.present + row.absent - row.validated), 0)
}

const calculateAveragePercentage = () => {
  if (props.report.length === 0) return 0
  const sum = props.report.reduce((sum, row) => sum + row.percentage, 0)
  return Math.round(sum / props.report.length)
}

const downloadCSV = () => {
  const headers = ['Membre', 'Présent', 'Absent', 'Validé', 'Taux (%)']
  const rows = props.report.map((row) => [
    row.user_name,
    row.present,
    row.absent,
    row.validated,
    row.percentage + '%',
  ])

  const csvContent = [
    headers.join(','),
    ...rows.map((row) => row.join(',')),
  ].join('\n')

  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const link = document.createElement('a')
  link.href = url
  link.download = `presence-rapport-${new Date().toISOString().split('T')[0]}.csv`
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
  window.URL.revokeObjectURL(url)
}
</script>
