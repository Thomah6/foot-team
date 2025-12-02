<script setup>
import { Link } from "@inertiajs/vue3";


// Charts removed — we'll show card lists only

import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
    goals: Array,
    assists: Array,
    goalkeepers: Array,
});
</script>

<template>
    <AuthenticatedLayout>
      <div class="flex min-h-screen bg-gradient-to-br from-gray-50 to-blue-50/30">
        <!-- Sidebar -->
      
        

        <!-- Main Content -->
        <div class="flex-1 px-4 sm:px-6 lg:px-8 py-6 lg:py-8 animate-fadeIn">
            <div class="max-w-7xl mx-auto">
                <!-- Header -->
                <div
                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8 animate-slideDown"
                >
                    <div>
                        <h1
                            class="text-3xl sm:text-4xl  text-center font-bold bg-gradient-to-r from-gray-900 to-blue-800 bg-clip-text text-transparent"
                        >
                            Classements
                        </h1>
                        <p class="text-gray-600 mt-2 text-sm sm:text-base">
                            Découvrez les meilleurs joueurs de la saison
                        </p>
                    </div>
                    <div>
                        <Link
                            href="/bureau/stats"
                            class="group inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 transition-all duration-300"
                        >
                            <svg
                                class="w-4 h-4 transform group-hover:-translate-x-1 transition-transform duration-300"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"
                                />
                            </svg>
                            Retour au tableau de bord
                        </Link>
                    </div>
                </div>

                <!-- Stats Grid -->
                <div
                    class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8 mb-8"
                >
                    <!-- Top Scorers -->
                    <div
    class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-[1.02] transition-transform duration-300 animate-slideInLeft"
>
    <div
        class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4"
    >
        <div class="flex items-center justify-between">
            <h3 class="text-xl font-bold text-white">
                 Meilleurs buteurs
            </h3>
            <div class="flex items-center gap-2">
                <!-- Icône de trophée pour le meilleur score -->
  <svg  class="w-10 h-10 text-white" fill="currentColor" viewBox="0 0 20 20">
    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
</svg>
                <span
                    class="text-white font-bold text-lg"
                    >{{
                        props.goals[0]?.total_goals || 0
                    }}</span
                >
            </div>
        </div>
    </div>

    <div class="p-6">
        <div class="space-y-3">
            <div
                v-for="(u, index) in props.goals.slice(
                    0,
                    6
                )"
                :key="u.id"
                class="group p-4 rounded-xl border border-gray-100 hover:border-orange-200 hover:bg-orange-50/50 transition-all duration-300 animate-fadeIn"
                :style="{
                    animationDelay: `${index * 100}ms`,
                }"
            >
                <div
                    class="flex items-center justify-between"
                >
                    <div class="flex items-center gap-4">
                        <!-- Ranking Badge -->
                        <div
                            class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-white"
                            :class="{
                                'bg-gradient-to-r from-yellow-500 to-amber-500':
                                    index === 0,
                                'bg-gradient-to-r from-gray-400 to-gray-500':
                                    index === 1,
                                'bg-gradient-to-r from-amber-700 to-amber-800':
                                    index === 2,
                                'bg-gradient-to-r from-blue-400 to-blue-500':
                                    index >= 3,
                            }"
                        >
                            {{ index + 1 }}
                        </div>

                        <!-- Avatar -->
                        <div class="relative">
                            <div
                                class="w-12 h-12 rounded-full bg-gradient-to-r from-orange-100 to-amber-100 flex items-center justify-center font-bold text-orange-600 text-lg"
                            >
                                {{ u.name.substring(0, 1) }}
                            </div>
                            <div
                                class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-orange-500 border-2 border-white flex items-center justify-center"
                            >
                                <!-- Icône de feu simple pour les buteurs -->
                                <svg
                                    class="w-3 h-3 text-white"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.38z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Player Info -->
                        <div>
                            <p
                                class="font-bold text-gray-900"
                            >
                                {{ u.name }}
                            </p>
                            <p
                                class="text-sm text-gray-500"
                            >
                            
                                {{
                                    u.teams[0].name ??
                                    "Sans équipe"
                                }}
                            </p>
                                 <p class="text-sm text-gray-500 mt-1">
                                        {{ u.position || 'Gardien' }}
                                    </p>
                        </div>
                    </div>

                    <!-- Goals -->
                    <div
                        class="text-right transform group-hover:scale-110 transition-transform duration-300"
                    >
                        <p
                            class="text-xs text-gray-500 mb-1"
                        >
                            Buts marqués
                        </p>
                        <div
                            class="flex items-center justify-end gap-2"
                        >
                            <!-- Icône de ballon/cible pour les buts -->
                            <svg
                                class="w-5 h-5 text-orange-500"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p
                                class="font-bold text-2xl text-gray-900"
                            >
                                {{ u.total_goals ?? 0 }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

                    <!-- Top Assists -->
                    <div
                        class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden transform hover:scale-[1.02] transition-transform duration-300 animate-slideInRight"
                    >
                        <div
                            class="bg-gradient-to-r from-blue-500 to-cyan-500 px-6 py-4"
                        >
                            <div class="flex items-center justify-between">
                                <h3 class="text-xl font-bold text-white">
                                    Meilleurs passeurs
                                </h3>
                                <div class="flex items-center gap-2">
                                    <svg
                                        class="w-6 h-6 text-white"
                                        fill="currentColor"
                                        viewBox="0 0 20 20"
                                    >
                                        <path
                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                                        />
                                    </svg>
                                    <span
                                        class="text-white font-bold text-lg"
                                        >{{
                                            props.assists[0]?.total_assists || 0
                                        }}</span
                                    >
                                </div>
                            </div>
                        </div>

                        <div class="p-6">
                            <div class="space-y-3">
                                <div
                                    v-for="(u, index) in props.assists.slice(
                                        0,
                                        6
                                    )"
                                    :key="u.id"
                                    class="group p-4 rounded-xl border border-gray-100 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-300 animate-fadeIn"
                                    :style="{
                                        animationDelay: `${
                                            index * 100 + 300
                                        }ms`,
                                    }"
                                >
                                    <div
                                        class="flex items-center justify-between"
                                    >
                                        <div class="flex items-center gap-4">
                                            <!-- Ranking Badge -->
                                            <div
                                                class="flex-shrink-0 w-8 h-8 rounded-lg flex items-center justify-center font-bold text-white"
                                                :class="{
                                                    'bg-gradient-to-r from-yellow-500 to-amber-500':
                                                        index === 0,
                                                    'bg-gradient-to-r from-gray-400 to-gray-500':
                                                        index === 1,
                                                    'bg-gradient-to-r from-amber-700 to-amber-800':
                                                        index === 2,
                                                    'bg-gradient-to-r from-blue-400 to-blue-500':
                                                        index >= 3,
                                                }"
                                            >
                                                {{ index + 1 }}
                                            </div>

                                            <!-- Avatar -->
                                            <div class="relative">
                                                <div
                                                    class="w-12 h-12 rounded-full bg-gradient-to-r from-blue-100 to-cyan-100 flex items-center justify-center font-bold text-blue-600 text-lg"
                                                >
                                                    {{ u.name.substring(0, 1) }}
                                                </div>
                                                <div
                                                    class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-blue-500 border-2 border-white flex items-center justify-center"
                                                >
                                                    <svg
                                                        class="w-3 h-3 text-white"
                                                        fill="currentColor"
                                                        viewBox="0 0 20 20"
                                                    >
                                                        <path
                                                            d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                                                        />
                                                    </svg>
                                                </div>
                                            </div>

                                            <!-- Player Info -->
                                            <div>
                                                <p
                                                    class="font-bold text-gray-900"
                                                >
                                                    {{ u.name }}
                                                </p>
                                                <p
                                                    class="text-sm text-gray-500"
                                                >
                                                
                                                    {{
                                                        u.teams[0].name  ??
                                                        "Sans équipe"
                                                    }}
                                                </p>
                                                     <p class="text-sm text-gray-500 mt-1">
                                        {{ u.position || 'Gardien' }}
                                    </p>
                                            </div>
                                        </div>

                                        <!-- Assists -->
                                        <div
                                            class="text-right transform group-hover:scale-110 transition-transform duration-300"
                                        >
                                            <p
                                                class="text-xs text-gray-500 mb-1"
                                            >
                                                Passes décisives
                                            </p>
                                            <div
                                                class="flex items-center justify-end gap-2"
                                            >
                                                <svg
                                                    class="w-5 h-5 text-blue-500"
                                                    fill="currentColor"
                                                    viewBox="0 0 20 20"
                                                >
                                                    <path
                                                        d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"
                                                    />
                                                </svg>
                                                <p
                                                    class="font-bold text-2xl text-gray-900"
                                                >
                                                    {{ u.total_assists ?? 0 }}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Goalkeepers -->
                               <div
    class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden animate-slideUp"
>
    <div
        class="bg-gradient-to-r from-emerald-500 to-green-500 px-6 py-4"
    >
        <div class="flex items-center gap-3">
            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
            </svg>
            <h3 class="text-xl font-bold text-white">
                Les Meilleurs gardiens
            </h3>
        </div>
        <p class="text-emerald-100 text-sm mt-1">
            Moins de buts encaissés
        </p>
    </div>

    <div class="p-6">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[600px]">
                <thead>
                    <tr class="border-b border-gray-200">
                        <th class="pb-4 px-4 text-left text-sm font-semibold text-gray-600">
                            Position
                        </th>
                        <th class="pb-4 px-4 text-left text-sm font-semibold text-gray-600">
                            Joueur
                        </th>
                        <th class="pb-4 px-4 text-left text-sm font-semibold text-gray-600">
                            Équipe
                        </th>
                        <th class="pb-4 px-4 text-right text-sm font-semibold text-gray-600">
                            Buts encaissés
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="(u, index) in props.goalkeepers"
                        :key="u.id"
                        class="border-b border-gray-100 hover:bg-emerald-50/50 transition-all duration-200 group"
                        :class="{ 'animate-fadeIn': true }"
                        :style="{ animationDelay: `${index * 100}ms` }"
                    >
                        <td class="py-5 px-4">
                            <div class="flex items-center">
                                <div 
                                    class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg text-white shadow-sm"
                                    :class="{
                                        'bg-gradient-to-r from-yellow-400 to-amber-500 shadow-yellow-200': index === 0,
                                        'bg-gradient-to-r from-gray-300 to-gray-400 shadow-gray-200': index === 1,
                                        'bg-gradient-to-r from-amber-600 to-amber-700 shadow-amber-200': index === 2,
                                        'bg-gradient-to-r from-emerald-300 to-green-400 shadow-emerald-200': index >= 3
                                    }"
                                >
                                    {{ index + 1 }}
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex items-center gap-4">
                                <div class="relative">
                                    <div class="w-12 h-12 rounded-xl bg-gradient-to-r from-emerald-100 to-green-100 flex items-center justify-center font-bold text-emerald-700 text-lg shadow-sm border border-emerald-200">
                                        {{ u.name.substring(0, 1) }}
                                    </div>
                                    <div class="absolute -bottom-1 -right-1 w-6 h-6 rounded-full bg-emerald-500 border-2 border-white flex items-center justify-center shadow-sm">
                                        <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900 text-base">
                                        {{ u.name }}
                                    </p>
                                    <p class="text-sm text-gray-500 mt-1">
                                        {{  'Gardien' }}
                                    </p>
                                </div>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex items-center">
                                <span 
                                    class="inline-flex items-center px-4 py-2 rounded-lg text-sm font-medium bg-gradient-to-r from-gray-50 to-gray-100 text-gray-700 border border-gray-200 shadow-sm"
                                >
                                    {{ u.teams[0].name  ?? "Sans équipe" }}
                                </span>
                            </div>
                        </td>
                        <td class="py-5 px-4">
                            <div class="flex flex-col items-end">
                                <div class="flex items-center justify-end gap-3 mb-2">
                                   
                                    <div class="flex items-center gap-2">
                                   
                                        <span class="font-bold text-2xl text-gray-900 min-w-[40px] text-right">
                                        
                                            {{ u.total_goals_against ?? 0 }}
                                        </span>
                                    </div>
                                </div>
                            
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div
            v-if="props.goalkeepers.length === 0"
            class="py-16 text-center"
        >
            <div
                class="w-24 h-24 mx-auto mb-6 rounded-full bg-gradient-to-r from-gray-100 to-gray-200 flex items-center justify-center shadow-inner"
            >
                <svg
                    class="w-12 h-12 text-gray-400"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"
                    />
                </svg>
            </div>
            <p class="text-gray-600 font-semibold text-lg mb-2">
                Aucune donnée de gardien disponible
            </p>
            <p class="text-gray-400 text-sm">
                Les statistiques apparaîtront ici dès qu'un gardien sera enregistré
            </p>
        </div>
    </div>
</div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>

<style>
@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes slideInLeft {
    from {
        opacity: 0;
        transform: translateX(-30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideInRight {
    from {
        opacity: 0;
        transform: translateX(30px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fadeIn {
    animation: fadeIn 0.6s ease-out;
}

.animate-slideDown {
    animation: slideDown 0.6s ease-out;
}

.animate-slideInLeft {
    animation: slideInLeft 0.6s ease-out;
}

.animate-slideInRight {
    animation: slideInRight 0.6s ease-out;
}

.animate-slideUp {
    animation: slideUp 0.6s ease-out;
}
</style>
