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

onMounted(() => {
    const urlParams = new URLSearchParams(window.location.search);
    const userId = urlParams.get('user_id');
    if (userId) {
        selectedUserId.value = userId;
    }
});

const isFilteredByUser = computed(() => {
    const isArray = Array.isArray(props.presenceHistory)
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
                <!-- Header -->
                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <div class="flex flex-col gap-2">
                        <div class="flex items-center gap-3 mb-1">
                            <div class="w-16 h-16 bg-gradient-to-br from-lime-100 to-emerald-100 rounded-2xl flex items-center justify-center border-2 border-lime-300">
                                <i class="fas fa-history text-lime-600 text-2xl"></i>
                            </div>
                            <div>
                                <h1 class="text-4xl font-bold text-gray-900 tracking-tight bg-gradient-to-r from-lime-600 to-emerald-600 bg-clip-text text-transparent">
                                    Historique des Présences
                                </h1>
                                <p class="text-gray-600 text-lg font-medium tracking-wide mt-2">
                                    {{ isAdmin ? 'Suivi de tous les combattants de l\'arène' : 'Votre parcours de combattant' }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <Link :href="route('presence.index')"
                        class="inline-flex items-center gap-3 bg-gradient-to-r from-lime-500 to-emerald-500 text-white px-8 py-4 rounded-2xl font-bold hover:from-lime-600 hover:to-emerald-600 transition-all duration-300 tracking-wider text-lg shadow-lg hover:shadow-xl hover:shadow-emerald-500/30">
                        <i class="fas fa-arrow-left"></i>
                        Retour au Calendrier
                    </Link>
                </div>

                <!-- Filters -->
                <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-6 shadow-lg">
                    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                        <!-- Month Selector -->
                        <div class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-700 mb-2">Mois</label>
                            <input v-model="selectedMonth" @change="loadMonth" type="month"
                                class="px-4 py-3 border-2 border-lime-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-lime-500/30 focus:border-lime-500 font-medium" />
                        </div>

                        <!-- User Selector (Admin only) -->
                        <div v-if="isAdmin" class="flex flex-col">
                            <label class="text-sm font-semibold text-gray-700 mb-2">Filtrer par combattant</label>
                            <select v-model="selectedUserId" @change="loadHistory"
                                class="px-4 py-3 border-2 border-lime-300 rounded-xl bg-white text-gray-900 focus:outline-none focus:ring-2 focus:ring-lime-500/30 focus:border-lime-500 font-medium">
                                <option value="">👥 Tous les combattants</option>
                                <option v-for="user in users" :key="user.id" :value="user.id">
                                    ⚽ {{ user.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- History Table -->
                <div class="w-full @container">
                    <div class="bg-white backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 shadow-xl overflow-hidden">
                        <table class="w-full border-collapse">
                            <thead class="border-b-2 border-lime-200 bg-gradient-to-r from-lime-50 to-emerald-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-gray-900 font-bold text-sm">
                                        {{ isAdmin ? ' COMBATTANT' : ' DATE DE BATAILLE' }}
                                    </th>
                                    <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm">
                                        {{ isAdmin ? ' DATE' : ' STATUT' }}
                                    </th>
                                    <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm">
                                        PRÉSENCE
                                    </th>
                                    <th class="px-4 py-4 text-center text-gray-700 font-semibold text-sm">
                                        VALIDATION
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Admin avec utilisateur filtré -->
                                <template v-if="isAdmin && isFilteredByUser">
                                    <tr v-for="(presence, index) in presenceHistory" :key="presence.id" :class="[
                                        'border-t border-lime-100 transition hover:bg-lime-50/50',
                                        index % 2 === 0 ? 'bg-lime-50/30' : '',
                                    ]">
                                        <td class="px-6 py-4 text-gray-900 text-sm font-bold">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-lime-400 to-emerald-500 flex items-center justify-center text-white text-sm font-bold">
                                                    {{ presence.user.name.charAt(0).toUpperCase() }}
                                                </div>
                                                {{ presence.user.name }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-4 text-center text-sm text-gray-600">
                                            {{ formatDate(presence.date) }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span v-if="presence.present && presence.validated_by_admin"
                                                class="text-emerald-500 text-xl">
                                                <i class="fas fa-circle-check"></i>
                                            </span>
                                            <span v-else-if="presence.present && !presence.validated_by_admin"
                                                class="text-amber-500 text-xl">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span v-else
                                                class="text-red-500 text-xl">
                                                <i class="fas fa-circle-xmark"></i>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span v-if="!presence.present"
                                                class="inline-flex px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                                Absent
                                            </span>
                                            <span v-else-if="presence.validated_by_admin"
                                                class="inline-flex px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">
                                                ✓ Validée
                                            </span>
                                            <span v-else
                                                class="inline-flex px-3 py-2 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">
                                                ⏳ En attente
                                            </span>
                                        </td>
                                    </tr>
                                </template>
                                <!-- Admin avec tous les utilisateurs -->
                                <template v-else-if="isAdmin && !isFilteredByUser">
                                    <template v-for="(items, memberName) in presenceHistory" :key="memberName">
                                        <tr class="border-t-2 border-lime-300 bg-gradient-to-r from-lime-100 to-emerald-100/50">
                                            <td class="px-6 py-4 text-gray-900 font-bold text-sm">
                                                <div class="flex items-center gap-3">
                                                    <i class="fas fa-user text-lime-600"></i>
                                                    {{ memberName }}
                                                </div>
                                            </td>
                                            <td colspan="3" class="px-4 py-4 text-right">
                                                <span class="inline-flex px-3 py-2 bg-lime-100 text-lime-700 rounded-lg text-xs font-bold">
                                                    {{ items.length }} déclaration{{ items.length > 1 ? 's' : '' }}
                                                </span>
                                            </td>
                                        </tr>
                                        <tr v-for="(presence, index) in items" :key="`${memberName}-${index}`"
                                            :class="['border-t border-lime-100 transition hover:bg-lime-50/50',
                                            index % 2 === 0 ? 'bg-lime-50/30' : '']">
                                            <td class="px-6 py-4 text-gray-900 text-sm"></td>
                                            <td class="px-4 py-4 text-center text-sm text-gray-600">
                                                {{ formatDate(presence.date) }}
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span v-if="presence.present && presence.validated_by_admin"
                                                    class="text-emerald-500 text-xl">
                                                    <i class="fas fa-circle-check"></i>
                                                </span>
                                                <span v-else-if="presence.present && !presence.validated_by_admin"
                                                    class="text-amber-500 text-xl">
                                                    <i class="fas fa-clock"></i>
                                                </span>
                                                <span v-else
                                                    class="text-red-500 text-xl">
                                                    <i class="fas fa-circle-xmark"></i>
                                                </span>
                                            </td>
                                            <td class="px-4 py-4 text-center">
                                                <span v-if="!presence.present"
                                                    class="inline-flex px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                                    Absent
                                                </span>
                                                <span v-else-if="presence.validated_by_admin"
                                                    class="inline-flex px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">
                                                    ✓ Validée
                                                </span>
                                                <span v-else
                                                    class="inline-flex px-3 py-2 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">
                                                    ⏳ En attente
                                                </span>
                                            </td>
                                        </tr>
                                    </template>
                                </template>
                                <!-- Non-admin -->
                                <template v-else>
                                    <tr v-for="(presence, index) in presenceHistory" :key="presence.id" :class="[
                                        'border-t border-lime-100 transition hover:bg-lime-50/50',
                                        index % 2 === 0 ? 'bg-lime-50/30' : '',
                                    ]">
                                        <td class="px-6 py-4 text-gray-900 text-sm font-bold">
                                            {{ formatDate(presence.date) }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span v-if="presence.present && presence.validated_by_admin"
                                                class="text-emerald-500 text-xl">
                                                <i class="fas fa-circle-check"></i>
                                            </span>
                                            <span v-else-if="presence.present && !presence.validated_by_admin"
                                                class="text-amber-500 text-xl">
                                                <i class="fas fa-clock"></i>
                                            </span>
                                            <span v-else class="text-red-500 text-xl">
                                                <i class="fas fa-circle-xmark"></i>
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-center text-sm text-gray-600">
                                            {{ presence.present ? '✓ Présent' : '✗ Absent' }}
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <span v-if="!presence.present"
                                                class="inline-flex px-3 py-2 bg-red-100 text-red-700 rounded-lg text-xs font-bold">
                                                Absent
                                            </span>
                                            <span v-else-if="presence.validated_by_admin"
                                                class="inline-flex px-3 py-2 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold">
                                                ✓ Validée
                                            </span>
                                            <span v-else
                                                class="inline-flex px-3 py-2 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold">
                                                ⏳ En attente
                                            </span>
                                        </td>
                                    </tr>
                                </template>

                                <tr v-if="!presenceHistory || (Array.isArray(presenceHistory) && presenceHistory.length === 0) || (!Array.isArray(presenceHistory) && Object.keys(presenceHistory).length === 0)">
                                    <td colspan="4" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center gap-3">
                                            <i class="fas fa-calendar-times text-lime-200 text-4xl"></i>
                                            <p class="text-gray-600 font-medium">
                                                Aucune présence enregistrée
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Statistics (Admin only) -->
                <div v-if="isAdmin" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-gradient-to-br from-white to-red-50 backdrop-blur-sm rounded-2xl border-2 border-red-200/50 p-6 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-semibold text-gray-700">Absences totales</p>
                            <i class="fas fa-circle-xmark text-red-500 text-2xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-red-600">{{ calculateTotalAbsences() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-white to-emerald-50 backdrop-blur-sm rounded-2xl border-2 border-emerald-200/50 p-6 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-semibold text-gray-700">Présences déclarées</p>
                            <i class="fas fa-circle-check text-emerald-500 text-2xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-emerald-600">{{ calculateTotalPresences() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-white to-amber-50 backdrop-blur-sm rounded-2xl border-2 border-amber-200/50 p-6 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-semibold text-gray-700">En attente</p>
                            <i class="fas fa-clock text-amber-500 text-2xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-amber-600">{{ calculatePendingValidations() }}</p>
                    </div>
                    <div class="bg-gradient-to-br from-white to-lime-50 backdrop-blur-sm rounded-2xl border-2 border-lime-200/50 p-6 shadow-lg hover:shadow-xl transition">
                        <div class="flex items-center justify-between mb-4">
                            <p class="text-sm font-semibold text-gray-700">Validées</p>
                            <i class="fas fa-shield-check text-lime-500 text-2xl"></i>
                        </div>
                        <p class="text-4xl font-bold text-lime-600">{{ calculateValidated() }}</p>
                    </div>
                </div>
            </div>
        </main>
    </AuthenticatedLayout>
</template>