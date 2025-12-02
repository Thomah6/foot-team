<template>
  <main class="flex-1 p-8">
    <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
        <div class="flex flex-col gap-2">
          <div class="flex items-center gap-3 mb-1">
            <div class="w-16 h-16 bg-gradient-to-br from-lime-100 to-emerald-100 rounded-2xl flex items-center justify-center border-2 border-lime-300">
              <i class="fas fa-chart-bar text-lime-600 text-2xl"></i>
            </div>
            <div>
              <h1 class="text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                Rapport Mensuel
              </h1>
              <p class="text-gray-600 text-lg font-medium tracking-wide mt-2">
                Statistiques d'assiduité • Analyse des combattants
              </p>
            </div>
          </div>
        </div>
        <Link
          :href="route('presence.index')"
          class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white px-8 py-4 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30"
        >
          <i class="fas fa-calendar"></i>
          Retour au Calendrier
        </Link>
      </div>

      <!-- Month Info -->
      <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-6 shadow-lg">
        <div class="flex items-center gap-4">
          <i class="fas fa-calendar-alt text-lime-600 text-2xl"></i>
          <div>
            <p class="text-sm font-semibold text-gray-700 uppercase tracking-wide">MOIS ANALYSÉ</p>
            <p class="text-2xl font-bold text-gray-900">
              {{ formatMonth(month) }}
            </p>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-white to-emerald-50 backdrop-blur-sm rounded-2xl border-2 border-emerald-200/50 p-6 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-700">Présences totales</p>
            <i class="fas fa-circle-check text-emerald-500 text-2xl"></i>
          </div>
          <p class="text-4xl font-bold text-emerald-600">
            {{ calculateTotalPresent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-white to-red-50 backdrop-blur-sm rounded-2xl border-2 border-red-200/50 p-6 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-700">Absences totales</p>
            <i class="fas fa-circle-xmark text-red-500 text-2xl"></i>
          </div>
          <p class="text-4xl font-bold text-red-600">
            {{ calculateTotalAbsent() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-white to-amber-50 backdrop-blur-sm rounded-2xl border-2 border-amber-200/50 p-6 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-700">En attente</p>
            <i class="fas fa-clock text-amber-500 text-2xl"></i>
          </div>
          <p class="text-4xl font-bold text-amber-600">
            {{ calculateTotalPending() }}
          </p>
        </div>
        <div class="bg-gradient-to-br from-white to-blue-50 backdrop-blur-sm rounded-2xl border-2 border-blue-200/50 p-6 shadow-lg hover:shadow-xl transition">
          <div class="flex items-center justify-between mb-4">
            <p class="text-sm font-semibold text-gray-700">Taux moyen</p>
            <i class="fas fa-chart-line text-blue-500 text-2xl"></i>
          </div>
          <p class="text-4xl font-bold text-blue-600">
            {{ calculateAveragePercentage() }}<span class="text-lg">%</span>
          </p>
        </div>
      </div>

      <!-- Report Table -->
      <div class="w-full @container">
        <div class="bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 shadow-xl overflow-hidden">
          <table class="w-full border-collapse">
            <thead class="border-b-2 border-lime-200 bg-gradient-to-r from-lime-50 to-emerald-50">
              <tr>
                <th class="px-6 py-4 text-left text-gray-900 font-bold text-sm sticky left-0 bg-gradient-to-r from-lime-50 to-emerald-50 z-10">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-users text-lime-600"></i>
                    <span>COMBATTANT</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <i class="fas fa-circle-check text-emerald-500"></i>
                    <span class="text-xs">PRÉSENT</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <i class="fas fa-circle-xmark text-red-500"></i>
                    <span class="text-xs">ABSENT</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <i class="fas fa-shield-check text-lime-500"></i>
                    <span class="text-xs">VALIDÉ</span>
                  </div>
                </th>
                <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm whitespace-nowrap">
                  <div class="flex flex-col items-center gap-1">
                    <i class="fas fa-chart-line text-blue-500"></i>
                    <span class="text-xs">TAUX</span>
                  </div>
                </th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="(row, index) in report"
                :key="row.user_id"
                :class="[
                  'border-t border-lime-100 transition hover:bg-lime-50/50',
                  index % 2 === 0 ? 'bg-lime-50/30' : '',
                ]"
              >
                <td class="px-6 py-4 text-gray-900 text-sm font-bold sticky left-0 z-10" :class="[index % 2 === 0 ? 'bg-lime-50/30' : 'bg-white']">
                  <div class="flex items-center gap-3 truncate">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                      {{ row.user_name.charAt(0).toUpperCase() }}
                    </div>
                    <span class="truncate">{{ row.user_name }}</span>
                  </div>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-sm font-bold">
                    {{ row.present }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-red-100 text-red-700 rounded-lg text-sm font-bold">
                    {{ row.absent }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <span class="inline-flex px-3 py-2 bg-lime-100 text-lime-700 rounded-lg text-sm font-bold">
                    {{ row.validated }}
                  </span>
                </td>
                <td class="px-4 py-4 text-center">
                  <div class="flex items-center justify-center gap-4">
                    <div class="w-24 h-3 bg-gray-200 rounded-full overflow-hidden shadow-inner">
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
                    <span class="text-sm font-bold text-gray-900 min-w-[45px] text-right">
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
          class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white px-8 py-4 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30"
        >
          <i class="fas fa-download"></i>
          Télécharger le Rapport
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
  const headers = ['Combattant', 'Présent', 'Absent', 'Validé', 'Taux (%)']
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