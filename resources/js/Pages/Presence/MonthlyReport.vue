<template>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- PageHeading -->
      <div class="flex flex-wrap items-start justify-between gap-4">
        <div class="flex flex-col gap-2">
          <p class="text-[#111318] dark:text-white text-3xl font-bold leading-tight tracking-tight">
            Point Mensuel des Présences
          </p>
          <p class="text-[#636f88] dark:text-slate-400 text-base font-normal leading-normal">
            Rapport complet de présences du mois
          </p>
        </div>
        <Link
          :href="route('presence.index')"
          class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-blue-600 dark:bg-blue-700 text-white text-sm font-bold leading-normal tracking-[0.015em] border border-blue-600 dark:border-blue-700 shadow-sm hover:bg-blue-700 dark:hover:bg-blue-600"
        >
          <span class="truncate">Retour</span>
        </Link>
      </div>

      <!-- Month Info -->
      <div class="bg-white dark:bg-background-dark border border-slate-200 dark:border-white/10 rounded-lg p-4">
        <p class="text-lg font-semibold text-[#111318] dark:text-white">
          Mois: {{ formatMonth(month) }}
        </p>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
          <p class="text-sm text-green-600 dark:text-green-300 mb-2">Présences totales</p>
          <p class="text-3xl font-bold text-green-700 dark:text-green-200">
            {{ calculateTotalPresent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
          <p class="text-sm text-red-600 dark:text-red-300 mb-2">Absences totales</p>
          <p class="text-3xl font-bold text-red-700 dark:text-red-200">
            {{ calculateTotalAbsent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 dark:from-yellow-900/20 dark:to-yellow-800/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
          <p class="text-sm text-yellow-600 dark:text-yellow-300 mb-2">En attente</p>
          <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-200">
            {{ calculateTotalPending() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
          <p class="text-sm text-blue-600 dark:text-blue-300 mb-2">Taux moyen</p>
          <p class="text-3xl font-bold text-blue-700 dark:text-blue-200">
            {{ calculateAveragePercentage() }}%
          </p>
        </div>
      </div>

      <!-- Report Table -->
      <div class="w-full @container">
        <div class="flex overflow-hidden rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-sm overflow-x-auto">
          <table class="w-full">
            <thead class="border-b border-slate-200 dark:border-white/10">
              <tr class="bg-white dark:bg-background-dark">
                <th class="px-4 py-3 text-left text-[#111318] dark:text-white text-sm font-semibold sticky left-0 bg-white dark:bg-background-dark z-10 w-[250px]">
                  Membre
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium whitespace-nowrap">
                  Présent
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium whitespace-nowrap">
                  Absent
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium whitespace-nowrap">
                  Validé
                </th>
                <th class="px-4 py-3 text-center text-[#636f88] dark:text-slate-400 text-sm font-medium whitespace-nowrap">
                  Taux (%)
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in report"
                :key="row.user_id"
                :class="[
                  'border-t border-slate-200/80 dark:border-white/10',
                  index % 2 === 1 ? 'bg-black/5 dark:bg-white/5' : '',
                ]"
              >
                <td class="px-4 py-3 text-[#111318] dark:text-white text-sm font-medium sticky left-0 z-10" :class="[index % 2 === 1 ? 'bg-black/5 dark:bg-white/5' : 'bg-white dark:bg-background-dark']">
                  {{ row.user_name }}
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="inline-block px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-sm font-medium">
                    {{ row.present }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="inline-block px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-sm font-medium">
                    {{ row.absent }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <span class="inline-block px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-sm font-medium">
                    {{ row.validated }}
                  </span>
                </td>
                <td class="px-4 py-3 text-center">
                  <div class="flex items-center justify-center gap-2">
                    <div class="w-16 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                      <div
                        class="h-full transition-all duration-300"
                        :style="[
                          `width: ${row.percentage}%`,
                          row.percentage >= 80 ? 'background: linear-gradient(90deg, #10b981, #059669);' :
                          row.percentage >= 60 ? 'background: linear-gradient(90deg, #f59e0b, #d97706);' :
                          'background: linear-gradient(90deg, #ef4444, #dc2626);'
                        ]"
                      ></div>
                    </div>
                    <span class="text-sm font-medium text-[#111318] dark:text-white min-w-[45px] text-right">
                      {{ row.percentage }}%
                    </span>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Export Button -->
      <div class="flex justify-end gap-3">
        <button
          @click="downloadCSV"
          class="flex items-center justify-center gap-2 px-4 py-2 bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-lg text-[#111318] dark:text-white hover:bg-gray-50 dark:hover:bg-slate-600 font-medium"
        >
          <span class="material-symbols-outlined text-sm">download</span>
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
