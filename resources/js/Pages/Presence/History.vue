<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

const props = defineProps({
    presenceHistory: [Object, Array],
    month: String,
    isAdmin: Boolean,
    users: Array,
});

const selectedMonth = ref(props.month ? props.month.slice(0, 7) : new Date().toISOString().slice(0, 7));
const selectedUserId = ref('');

// Fonction pour formater le mois en format lisible (ex: "novembre 2025")
const formatMonthDisplay = (monthStr) => {
    if (!monthStr) return '';
    try {
        const [year, month] = monthStr.split('-');
        const d = new Date(year, parseInt(month) - 1, 1);
        return d.toLocaleDateString('fr-FR', {
            month: 'long',
            year: 'numeric',
        });
    } catch (e) {
        return monthStr;
    }
};

// Synchroniser selectedUserId au chargement de la page si un user_id est dans l'URL
onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user_id');
    if (userId) {
        selectedUserId.value = userId;
    }
});

// Détecter si presenceHistory est un tableau (utilisateur filtré) ou un objet (tous)
const isFilteredByUser = computed(() => {
    const isArray = Array.isArray(props.presenceHistory)
    console.log('presenceHistory type:', isArray ? 'Array' : 'Object', props.presenceHistory)
    return isArray
});

const formatDate = (date) => {
    const d = new Date(date)
    return d.toLocaleDateString('fr-FR', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    })
}

const loadMonth = () => {
    window.location.href = route('presence.history', { month: selectedMonth.value })
}

const loadHistory = () => {
    window.location.href = route('presence.history', {
        month: selectedMonth.value,
        user_id: selectedUserId.value,
    })
}

const calculateTotalAbsences = () => {
    if (!props.isAdmin || !props.presenceHistory) return 0
    let count = 0
    if (Array.isArray(props.presenceHistory)) {
        props.presenceHistory.forEach((item) => {
            if (!item.present) count++
        })
    } else {
        Object.values(props.presenceHistory).forEach((items) => {
            items.forEach((item) => {
                if (!item.present) count++
            })
        })
    }
    return count
}

const calculateTotalPresences = () => {
    if (!props.isAdmin || !props.presenceHistory) return 0
    let count = 0
    if (Array.isArray(props.presenceHistory)) {
        // Quand filtré par utilisateur : compter TOUTES les déclarations (présences + absences)
        count = props.presenceHistory.length
    } else {
        Object.values(props.presenceHistory).forEach((items) => {
            items.forEach((item) => {
                if (item.present) count++
            })
        })
    }
    return count
}

const calculatePendingValidations = () => {
    if (!props.isAdmin || !props.presenceHistory) return 0
    let count = 0
    if (Array.isArray(props.presenceHistory)) {
        props.presenceHistory.forEach((item) => {
            if (!item.validated_by_admin) count++
        })
    } else {
        Object.values(props.presenceHistory).forEach((items) => {
            items.forEach((item) => {
                if (!item.validated_by_admin) count++
            })
        })
    }
    return count
}

const calculateValidated = () => {
    if (!props.isAdmin || !props.presenceHistory) return 0
    let count = 0
    if (Array.isArray(props.presenceHistory)) {
        props.presenceHistory.forEach((item) => {
            if (item.validated_by_admin) count++
        })
    } else {
        Object.values(props.presenceHistory).forEach((items) => {
            items.forEach((item) => {
                if (item.validated_by_admin) count++
            })
        })
    }
    return count
}
</script>

<template>
    <AuthenticatedLayout>
        <main class="flex-1 p-8">
            <div class="w-full max-w-7xl mx-auto flex flex-col gap-6">
                <!-- PageHeading -->
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3">
                            <span class="material-symbols-outlined text-4xl text-amber-600 dark:text-amber-400">history</span>
                            <h1 class="text-3xl md:text-4xl font-black leading-tight tracking-tight text-[#111318] dark:text-white">
                                Historique
                            </h1>
                        </div>
                        <p class="text-[#636f88] dark:text-slate-400 text-sm md:text-base font-normal leading-normal">
                            {{ isAdmin ? 'Consultez l\'historique de tous les membres' : 'Votre historique de présences' }}
                        </p>
                    </div>
                    <Link :href="route('presence.index')"
                        class="flex w-full md:w-auto min-w-[160px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-11 px-4 bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-700 dark:to-blue-800 text-white text-sm font-bold leading-normal tracking-[0.015em] border border-blue-600 dark:border-blue-700 shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 dark:hover:from-blue-600 dark:hover:to-blue-700 transition-all gap-2">
                        <span class="material-symbols-outlined text-base">calendar_month</span>
                        <span class="truncate">Calendrier</span>
                    </Link>
                </div>

                <!-- Filters -->
                <div
                    class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 p-4 md:p-5 bg-gradient-to-br from-white to-gray-50 dark:from-background-dark dark:to-slate-800 border border-slate-200 dark:border-white/10 rounded-xl shadow-sm">
                    <!-- Month Selector -->
                    <div class="flex w-full md:w-auto items-center gap-3">
                        <span class="material-symbols-outlined text-slate-600 dark:text-slate-400 text-xl">calendar_month</span>
                        <input v-model="selectedMonth" @change="loadMonth" type="month"
                            class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 font-medium" />
                        <!-- Display formatted month/year -->
                        <span class="text-sm font-semibold text-[#111318] dark:text-white hidden sm:inline">
                            {{ formatMonthDisplay(selectedMonth) }}
                        </span>
                    </div>

                    <!-- User Selector (Admin only) -->
                    <select v-if="isAdmin" v-model="selectedUserId" @change="loadHistory"
                        class="w-full md:w-auto px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-[#111318] dark:text-white focus:outline-0 focus:ring-2 focus:ring-blue-500 dark:focus:ring-blue-400 font-medium">
                        <option value="">👥 Tous les membres</option>
                        <option v-for="user in users" :key="user.id" :value="user.id">
                            ⚽ {{ user.name }}
                        </option>
                    </select>
                </div>

                <!-- History Table -->
                <div class="w-full @container">
                    <div
                        class="flex overflow-hidden rounded-xl border border-slate-200 dark:border-white/10 bg-white dark:bg-background-dark shadow-md">
                        <table class="w-full border-collapse">
                            <thead class="border-b-2 border-slate-300 dark:border-slate-600 bg-gradient-to-r from-slate-50 to-slate-100 dark:from-slate-800 dark:to-slate-700">
                                <tr>
                                    <th
                                        class="px-4 py-4 text-left text-[#111318] dark:text-white font-black text-sm">
                                        {{ isAdmin ? '⚽ Membre' : '📅 Date' }}
                                    </th>
                                    <th
                                        class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm">
                                        {{ isAdmin ? '📅 Date' : '✓ Statut' }}
                                    </th>
                                    <th
                                        class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm">
                                        Présence
                                    </th>
                                    <th
                                        class="px-4 py-4 text-center text-[#636f88] dark:text-slate-300 font-semibold text-sm">
                                        Validation
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Si admin ET utilisateur filtré : afficher comme tableau simple -->
                                <template v-if="isAdmin && isFilteredByUser">
                                    <tr v-for="(presence, index) in presenceHistory" :key="presence.id" :class="[
                                        'border-t border-slate-200/80 dark:border-white/10 transition hover:bg-amber-50/50 dark:hover:bg-amber-900/20',
                                        index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : '',
                                    ]">
                                        <td class="px-4 py-3 text-[#111318] dark:text-white text-sm font-semibold">
                                            <div class="flex items-center gap-2">
                                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white text-xs font-bold">
                                                    {{ presence.user.name.charAt(0).toUpperCase() }}
                                                </div>
                                                {{ presence.user.name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-400">
                                            {{ formatDate(presence.date) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="presence.present && presence.validated_by_admin"
                                                class="material-symbols-outlined fill text-green-500 text-lg">
                                                check_circle
                                            </span>
                                            <span v-else-if="presence.present && !presence.validated_by_admin"
                                                class="material-symbols-outlined fill text-amber-500 text-lg">
                                                schedule
                                            </span>
                                            <span v-else
                                                class="material-symbols-outlined fill text-red-500 text-lg">
                                                cancel
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="!presence.present"
                                                class="inline-flex px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">
                                                Absent
                                            </span>
                                            <span v-else-if="presence.validated_by_admin"
                                                class="inline-flex px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                                ✓ Validée
                                            </span>
                                            <span v-else
                                                class="inline-flex px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-xs font-semibold">
                                                ⏳ Attente
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <!-- Si admin ET tous les utilisateurs : afficher groupé par membre -->
                                <template v-else-if="isAdmin && !isFilteredByUser">
                                    <template v-for="(items, memberName) in presenceHistory" :key="memberName">
                                        <tr
                                            class="border-t-2 border-slate-300 dark:border-slate-600 bg-gradient-to-r from-slate-100 to-slate-50 dark:from-slate-700 dark:to-slate-800 hover:from-blue-50 hover:to-slate-50 dark:hover:from-blue-900/30 dark:hover:to-slate-800 transition">
                                            <td class="px-4 py-3 text-[#111318] dark:text-white font-black text-sm">
                                                <div class="flex items-center gap-2">
                                                    <span>⚽</span>
                                                    {{ memberName }}
                                                </div>
                                            </td>
                                            <td colspan="3" class="px-4 py-3 text-right text-sm">
                                                <span class="inline-flex px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-full text-xs font-semibold">
                                                    {{ items.length }} déclaration{{ items.length > 1 ? 's' : '' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-for="(presence, index) in items" :key="`${memberName}-${index}`"
                                            :class="['border-t border-slate-200/80 dark:border-white/10 transition hover:bg-slate-50/50 dark:hover:bg-white/2.5',
                                            index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : '']">
                                            <td class="px-4 py-3 text-[#111318] dark:text-white text-sm"></td>
                                            <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-400">
                                                {{ formatDate(presence.date) }}
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span v-if="presence.present && presence.validated_by_admin"
                                                    class="material-symbols-outlined fill text-green-500 text-lg">
                                                    check_circle
                                                </span>
                                                <span v-else-if="presence.present && !presence.validated_by_admin"
                                                    class="material-symbols-outlined fill text-amber-500 text-lg">
                                                    schedule
                                                </span>
                                                <span v-else
                                                    class="material-symbols-outlined fill text-red-500 text-lg">
                                                    cancel
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-center">
                                                <span v-if="!presence.present"
                                                    class="inline-flex px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">
                                                    Absent
                                                </span>
                                                <span v-else-if="presence.validated_by_admin"
                                                    class="inline-flex px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                                    ✓ Validée
                                                </span>
                                                <span v-else
                                                    class="inline-flex px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-xs font-semibold">
                                                    ⏳ Attente
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <!-- Si non-admin : afficher le tableau personnel -->
                                <template v-else>
                                    <tr v-for="(presence, index) in presenceHistory" :key="presence.id" :class="[
                                        'border-t border-slate-200/80 dark:border-white/10 transition hover:bg-green-50/50 dark:hover:bg-green-900/20',
                                        index % 2 === 1 ? 'bg-slate-50/50 dark:bg-white/2.5' : '',
                                    ]">
                                        <td class="px-4 py-3 text-[#111318] dark:text-white text-sm font-semibold">
                                            {{ formatDate(presence.date) }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="presence.present && presence.validated_by_admin"
                                                class="material-symbols-outlined fill text-green-500 text-lg">
                                                check_circle
                                            </span>
                                            <span v-else-if="presence.present && !presence.validated_by_admin"
                                                class="material-symbols-outlined fill text-amber-500 text-lg">
                                                schedule
                                            </span>
                                            <span v-else class="material-symbols-outlined fill text-red-500 text-lg">
                                                cancel
                                            </span>
                                        </td>
                                        <td class="px-4 py-3 text-center text-sm text-slate-600 dark:text-slate-400">
                                            {{ presence.present ? '✓ Présent' : '✗ Absent' }}
                                        </td>
                                        <td class="px-4 py-3 text-center">
                                            <span v-if="!presence.present"
                                                class="inline-flex px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded-full text-xs font-semibold">
                                                Absent
                                            </span>
                                            <span v-else-if="presence.validated_by_admin"
                                                class="inline-flex px-3 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-full text-xs font-semibold">
                                                ✓ Validée
                                            </span>
                                            <span v-else
                                                class="inline-flex px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 rounded-full text-xs font-semibold">
                                                ⏳ Attente
                                            </span>
                                        </td>
                                    </tr>
                                </template>

                                <tr
                                    v-if="!presenceHistory || (Array.isArray(presenceHistory) && presenceHistory.length === 0) || (!Array.isArray(presenceHistory) && Object.keys(presenceHistory).length === 0)">
                                    <td colspan="4" class="px-4 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <span class="material-symbols-outlined text-4xl text-slate-400 dark:text-slate-500">event_note</span>
                                            <p class="text-[#636f88] dark:text-slate-400 font-medium">
                                                Aucune présence enregistrée
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Monthly Statistics (Admin only) -->
                <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div
                        class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 border border-red-200 dark:border-red-800 rounded-xl p-5 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-red-700 dark:text-red-300">Total absences</p>
                            <span class="material-symbols-outlined text-2xl text-red-500">cancel</span>
                        </div>
                        <p class="text-4xl font-black text-red-600 dark:text-red-300">{{ calculateTotalAbsences() }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 border border-green-200 dark:border-green-800 rounded-xl p-5 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-green-700 dark:text-green-300">Présences déclarées</p>
                            <span class="material-symbols-outlined text-2xl text-green-500">check_circle</span>
                        </div>
                        <p class="text-4xl font-black text-green-600 dark:text-green-300">{{ calculateTotalPresences() }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-amber-900/20 dark:to-amber-800/20 border border-amber-200 dark:border-amber-800 rounded-xl p-5 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-amber-700 dark:text-amber-300">Attente validation</p>
                            <span class="material-symbols-outlined text-2xl text-amber-500">schedule</span>
                        </div>
                        <p class="text-4xl font-black text-amber-600 dark:text-amber-300">{{ calculatePendingValidations() }}</p>
                    </div>
                    <div
                        class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 border border-blue-200 dark:border-blue-800 rounded-xl p-5 hover:shadow-lg transition">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-sm font-semibold text-blue-700 dark:text-blue-300">Validées</p>
                            <span class="material-symbols-outlined text-2xl text-blue-500">verified</span>
                        </div>
                        <p class="text-4xl font-black text-blue-600 dark:text-blue-300">{{ calculateValidated() }}</p>
                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>
