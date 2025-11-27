<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

function handleImageError() {
    document.getElementById('screenshot-container')?.classList.add('!hidden');
    document.getElementById('docs-card')?.classList.add('!row-span-1');
    document.getElementById('docs-card-content')?.classList.add('!flex-row');
    document.getElementById('background')?.classList.add('!hidden');
}
</script>

<template>
    <Head title="FC Dynamo - Club Management Dashboard" />
    
    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <div class="relative flex min-h-screen w-full flex-col font-display bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
        <!-- Header -->
        <header class="flex h-16 w-full items-center justify-between border-b border-gray-200/50 bg-white dark:border-gray-700/50 dark:bg-gray-800 px-8">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3">
                    <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" 
                         data-alt="Official club logo" 
                         style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuClB_n3jrjwjjlowD9I-zILmuiHRal5v5RaZkCe1LkPaoyvyxR-WLUFsk_NwaCstGmWb--7mK_JNvXlpiJZePSJta9W7wZZgoF8SrlP2Sg4alz3U23JTYTJfriKib9et67H4lyMfU-hgCQqLN2ZfFIg_D2iPzFnbN2530VMHOOs2BIE0drSQBKu8Wchcnvd6Rt0_uazlyWnjdBfAWV7BNUcFkCMNNFV536XJCVhxvv5fdOkIIwZAFACkEx1YU3E_hrMfVHBGLc4Pw");'>
                    </div>
                    <h1 class="text-base font-bold leading-normal">FC Dynamo</h1>
                </div>
                <nav class="hidden md:flex items-center gap-2">
                    <Link href="/dashboard" 
                          class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-bold bg-green-100/20 text-gray-900 dark:text-gray-100">
                        <i class="fas fa-home text-base"></i>
                        <span>Dashboard</span>
                    </Link>
                    <Link href="/admin/stats" 
                          class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-100/20 transition-colors duration-200">
                        <i class="fas fa-chart-bar text-base"></i>
                        <span>Stats</span>
                    </Link>
                    <Link href="/joueur-du-mois" 
                          class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium hover:bg-green-100/20 transition-colors duration-200">
                        <i class="fas fa-trophy text-base"></i>
                        <span>Joueur du mois</span>
                    </Link>
                </nav>
            </div>
            <div class="flex items-center gap-4">
                <Link v-if="canLogin" :href="route('login')" 
                      class="flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-600 transition-opacity">
                    <span class="truncate">Connexion</span>
                </Link>
                <Link v-if="canRegister" :href="route('register')" 
                      class="flex items-center justify-center size-10 rounded-full hover:bg-green-100/20 transition-colors duration-200">
                    <i class="fas fa-user-plus"></i>
                </Link>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="p-8">
                <!-- Hero Section -->
                <div class="relative w-full rounded-xl overflow-hidden bg-white dark:bg-gray-800 shadow-lg dark:shadow-none mb-8">
                    <div class="absolute inset-0 bg-cover bg-center" 
                         style="background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuCq5SmIFwaqOxatQv-MMr6vmBY_JeCcxPb3rbasK4fR6vxUq8RhLoZj4U-HGbvTJ8EALKaDPfgZw0WJC9B5MU4YVTxgNrbb66g0F_9nkDl5NSh75LFTiWvXeZ8v-Xt3NftWiLKgmAH7Uho9PPKEPpZodFfuZ9hOt91WtwSJnO6B1c6dXf_Xy1-CIMJq8LmYvmI6qgipl_udXDpjf54bUIKBNoWOFQLJd4BkdarIHGqeyXCaEckHvoxKfNzq3fTFALJVJX4eRtUcA'); opacity: 0.1;">
                    </div>
                    <div class="relative flex flex-col md:flex-row items-center justify-between p-8 md:p-12 gap-8">
                        <div class="flex flex-col gap-4 max-w-2xl text-center md:text-left">
                            <h2 class="text-3xl md:text-4xl font-extrabold leading-tight tracking-tighter text-gray-900 dark:text-gray-100">
                                Welcome to the Home of FC Dynamo!
                            </h2>
                            <p class="text-lg text-gray-600 dark:text-gray-300">
                                Your official hub for all things FC Dynamo. Get the latest news, check player stats, and follow our journey through the season. We're glad to have you with us!
                            </p>
                        </div>
                        <div class="flex-shrink-0">
                            <Link href="/joueur-du-mois"
                                  class="flex items-center gap-2 min-w-[84px] cursor-pointer justify-center overflow-hidden rounded-lg h-12 px-6 bg-green-500 text-white text-base font-bold leading-normal tracking-[0.015em] hover:bg-green-600 transition-opacity">
                                <span class="truncate">Joueur du mois</span>
                                <i class="fas fa-arrow-right"></i>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Grid Layout -->
                <div class="grid grid-cols-12 gap-8">
                    <!-- Left Column -->
                    <div class="col-span-12 lg:col-span-8 flex flex-col gap-8">
                        <!-- Joueur du mois Section -->
                        <div>
                            <h2 class="text-2xl font-bold leading-tight tracking-tight px-0 pb-4">Joueur du mois</h2>
                            <div class="flex overflow-x-auto [-ms-scrollbar-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden -mx-8 px-4">
                                <div class="flex items-stretch p-4 gap-6">
                                    <!-- Player Cards -->
                                    <div class="flex h-full flex-1 flex-col gap-4 text-center rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none min-w-52 pt-4">
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full flex flex-col self-center w-4/5" 
                                             data-alt="Photo of player Alex Dupont" 
                                             style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBKogp_hGY-GXf40O-nR7Cw3VXMsSb4hb0ecIYZMNdhQpUnAePiDnG0SyYvW_8o9DQ6zUaA7JCMH18FEPv2rXLfZp2CLoaEQHapr2KIf5Q1blbp58ZwgpfLiVuPGBhpyFt1PJkrD1AuYdgLRsI_tvFItfWkgX9mMeSPb5z94yI3Jer1S2NxHIFMoU1-giooI4mbFilQd3zawb6DiV2_YV_IyAjDbb95L8w21pS3IdO0i0VG_P-ZtWP8iFFf6yqfme3nkV3CJoijHQ");'>
                                        </div>
                                        <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4 text-center">
                                            <div>
                                                <p class="text-base font-bold leading-normal">Alex Dupont</p>
                                                <p class="text-sm font-normal leading-normal text-gray-600 dark:text-gray-400">Midfielder</p>
                                            </div>
                                            <Link href="/joueur-du-mois"
                                                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-100/20 text-gray-900 dark:text-gray-100 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-100/30 transition-colors">
                                                <span class="truncate">View Profile</span>
                                            </Link>
                                        </div>
                                    </div>

                                    <div class="flex h-full flex-1 flex-col gap-4 text-center rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none min-w-52 pt-4">
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full flex flex-col self-center w-4/5" 
                                             data-alt="Photo of player Jean Moreau" 
                                             style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuCN3WC-DnvOXKcmIk4o7d94FCn-4vM8hDzDidufulUnoOq7OPz4Jt_RLaeifnF3zebaitUqkevOdsNs-g_ksmRyjC0rF5rIgbyXeeGFQQNIEgF1vSzsjsrkl7nFSdmvfmK6cmrZ8TKYgm8lDUcdxX1xlJTMtbdDdisoofGBxt0iWcFtCVTphmyxYnbbAw2gZtE8mg5ZeXxzfcFkbokeeQHJdcpOOKWgzlDuWXLSdAoUR1Bmrhtgq8PJNLLcwO0tWhWQnRlWY2nZLg");'>
                                        </div>
                                        <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4 text-center">
                                            <div>
                                                <p class="text-base font-bold leading-normal">Jean Moreau</p>
                                                <p class="text-sm font-normal leading-normal text-gray-600 dark:text-gray-400">Forward</p>
                                            </div>
                                            <Link href="/joueur-du-mois"
                                                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-100/20 text-gray-900 dark:text-gray-100 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-100/30 transition-colors">
                                                <span class="truncate">View Profile</span>
                                            </Link>
                                        </div>
                                    </div>

                                    <div class="flex h-full flex-1 flex-col gap-4 text-center rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none min-w-52 pt-4">
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full flex flex-col self-center w-4/5" 
                                             data-alt="Photo of player Marie Claire" 
                                             style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAIiQuCdDHx-Yq3chWaVEuiX8Ry4ndwm-ZpIgg0Sc7Og0gTL9mxrHfR4sPzwIS68FcbWfohkGC1TV2hpry7FumU-iAM_rcb9uEXnOnT_EXEqYTLQNSuymNVZL7StVPAQ8i_QTryaPKRJWu04k5SteSxNYIFPV9FYhqGkGiiAyQMI39cz6QsVXb713dYsClWS7ejDLH2X97saSg5yJqHeOuvHSCSw4Ja8je3y9MK8leegYu653RmCYuN3xtAPPj1Yef0XZ25aW615g");'>
                                        </div>
                                        <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4 text-center">
                                            <div>
                                                <p class="text-base font-bold leading-normal">Marie Claire</p>
                                                <p class="text-sm font-normal leading-normal text-gray-600 dark:text-gray-400">Defender</p>
                                            </div>
                                            <Link href="/joueur-du-mois"
                                                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-100/20 text-gray-900 dark:text-gray-100 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-100/30 transition-colors">
                                                <span class="truncate">View Profile</span>
                                            </Link>
                                        </div>
                                    </div>

                                    <div class="flex h-full flex-1 flex-col gap-4 text-center rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none min-w-52 pt-4">
                                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full flex flex-col self-center w-4/5" 
                                             data-alt="Photo of player Lucas Petit" 
                                             style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBYFpjGOiL_LbB6LvT0NZVjXsi89465xwsa-Efdd5UccxiFmBjN3_V_C6McdBI6AMgNsZyQ_s2SAVLLYFynzanjUZmBLPZCVwzdgx0bCryosfYf6qOOHtKDK4MBP3oPvCE5F-SR_ISl_FedWGATo2SRucZ-1r4kzIVGHJgybY0JhC1FL3w8rx6J-Xmktc2RXh55_oP5FPDU01s0ClwpNaT2Xylz2aqsmqZgKdKbGS7yrjkRrVBBXECV3sPcYgR9OkKJbwH8A5lpOA");'>
                                        </div>
                                        <div class="flex flex-col flex-1 justify-between p-4 pt-0 gap-4 text-center">
                                            <div>
                                                <p class="text-base font-bold leading-normal">Lucas Petit</p>
                                                <p class="text-sm font-normal leading-normal text-gray-600 dark:text-gray-400">Goalkeeper</p>
                                            </div>
                                            <Link href="/joueur-du-mois"
                                                  class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-100/20 text-gray-900 dark:text-gray-100 text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-100/30 transition-colors">
                                                <span class="truncate">View Profile</span>
                                            </Link>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- À la une Section -->
                        <div>
                            <h2 class="text-2xl font-bold leading-tight tracking-tight pb-4">À la une</h2>
                            <div class="group/card @container overflow-hidden rounded-xl shadow-lg dark:shadow-none bg-white dark:bg-gray-800">
                                <div class="flex flex-col items-stretch justify-start">
                                    <div class="w-full bg-center bg-no-repeat aspect-video bg-cover rounded-t-xl" 
                                         data-alt="Team celebrating a goal on the field" 
                                         style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBdSwkSe1989bCACfT1LmqOsoggkrHmcNcLzN1_vcxMXjAsCB_alNxblOBvhIZF4zMRV0YjmeA_3Zsq2S3LbrJ_HeQa5lVrcD4NBXbWZfxZ6-IE-mIQISNZh213LTz-ZdyVtlwd38tNckB3wOJvJZNRdlE97H8qQGiIniQMzYyD6iwmjPh32WYCyPB-rBTwonH-_UnDAlHVMRgPskAwR9OlUqQWCmlF-iWE3FG6E5ZvPXS35H3INLMFvTMvlnX_f1IkOuL42RATsg");'>
                                    </div>
                                    <div class="flex w-full grow flex-col items-stretch justify-center gap-2 p-6">
                                        <h3 class="text-xl font-bold leading-tight tracking-tight">Big Win Last Weekend!</h3>
                                        <div class="flex items-end justify-between gap-4">
                                            <div class="flex flex-col gap-1">
                                                <p class="text-base font-normal leading-normal text-gray-600 dark:text-gray-400">
                                                    A summary of the latest club news, an important announcement, or a match report summary. Read the full story to get all the details.
                                                </p>
                                            </div>
                                            <button class="flex shrink-0 min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-5 bg-green-500 text-white text-sm font-bold leading-normal hover:bg-green-600 transition-opacity">
                                                <span class="truncate">Voir plus</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-span-12 lg:col-span-4 flex flex-col gap-8">
                        <!-- Top Stats -->
                        <div>
                            <h2 class="text-2xl font-bold leading-tight tracking-tight pb-4">Top Stats</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 lg:grid-cols-1 gap-4">
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none">
                                    <div class="flex items-center justify-center size-12 rounded-full bg-green-100/20">
                                        <i class="fas fa-futbol text-green-500 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">42</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Buts</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none">
                                    <div class="flex items-center justify-center size-12 rounded-full bg-green-100/20">
                                        <i class="fas fa-exchange-alt text-green-500 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">28</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Passes</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4 p-4 rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none">
                                    <div class="flex items-center justify-center size-12 rounded-full bg-green-100/20">
                                        <i class="fas fa-hand-paper text-green-500 text-2xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-2xl font-bold">15</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">Arrêts</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Prochain match -->
                        <div>
                            <h2 class="text-2xl font-bold leading-tight tracking-tight pb-4">Prochain match</h2>
                            <div class="flex flex-col gap-4 p-6 rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none">
                                <div class="flex items-center justify-between">
                                    <p class="font-bold">vs. Rival FC</p>
                                    <span class="text-xs font-semibold uppercase tracking-wider bg-green-100/20 text-green-600 px-2 py-1 rounded-full">League</span>
                                </div>
                                <div class="flex flex-col gap-3 text-sm text-gray-600 dark:text-gray-400">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-calendar-alt"></i>
                                        <span>Sunday, 28th July</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-clock"></i>
                                        <span>15:00 K.O.</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <span data-location="City Stadium">City Stadium, Capital City</span>
                                    </div>
                                </div>
                                <button class="flex w-full cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-green-500 text-white text-sm font-bold leading-normal tracking-[0.015em] hover:bg-green-600 transition-opacity">
                                    <span class="truncate">Voir détails</span>
                                </button>
                            </div>
                        </div>

                        <!-- Classement -->
                        <div>
                            <h2 class="text-2xl font-bold leading-tight tracking-tight pb-4">Classement</h2>
                            <div class="flex flex-col gap-2 p-4 rounded-xl bg-white dark:bg-gray-800 shadow-lg dark:shadow-none">
                                <div class="flex items-center p-2 rounded-lg bg-green-100/20">
                                    <span class="w-6 font-bold">1.</span>
                                    <img alt="FC Dynamo logo" class="size-6 mx-3 rounded-full" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuBWm2O2Qpw2xd27Tjr9MLslXVwGUX_yu2Pmy1zLqr5uWLH33050KRX9Wg_QODW6CZ8lppoQRMjSVFscC0fxvJ2fI9W0lgo_4zQmwhMUx5JDnVOG8oyemJYHXeay27YlVEiXCGI20jzHLVnIumGxgl_midgJYL3AEwUHRoKqiRDGiefOzPoPT1ROhW6POPk0kd6CnB3wn20teKyM65QTJDsZrVRy6Lts33B8LWWER_d0oQxZS5pQ7y11FcPAt8xbVK7RWgdBlsoWig"/>
                                    <span class="font-bold">FC Dynamo</span>
                                    <span class="ml-auto font-bold">45 PTS</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg hover:bg-green-100/20 transition-colors">
                                    <span class="w-6">2.</span>
                                    <img alt="Rival FC logo" class="size-6 mx-3 rounded-full" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuAmkKg-fLJwhjQmkFK8SapuMAeNVXQdseZlW_TexJzGx3qAHZOUnVVPzUCExUIqXvNDZrK-b4fGAP5FZWTMsKdE9AdqTI6_IV6BHWBwshJICk8n0s4oWQWTMs6S9BLeufrfCJ-FXI1xIn83Ys31sOMrbh5UzAfbTOl9yCSzgmHyXjpOom7we2g-Bx6X_94A1MDnNewpfe9EriNS1w-C_mCgcyFoL5_WWaLuQjvFXpacT0JFxj5lPu6eQ-8xyPpbZXW_jSH4Ckka5w"/>
                                    <span>Rival FC</span>
                                    <span class="ml-auto">42 PTS</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg hover:bg-green-100/20 transition-colors">
                                    <span class="w-6">3.</span>
                                    <img alt="United FC logo" class="size-6 mx-3 rounded-full" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuD-Mf8CMv1gZPGOM_PBfTCwHe1eEr7mvO3qGROVs1krYKAKhwrneWuTspJB9F28fVBSnQF5Urat11rmtnl4lSUnOSWZeAGCDukvTZwgFXivVJ4Baxqfhq7HEFZUWJnOWWTIKXnmb-uJb00s6MfGXDc87pMiKkBiBmeaSlJEZ-eS99QAZ7QL_znOvAFMdApe3J59fpNElKkfBSBkvLm8mP2LsvvRnnh1ITN1YEvYmnsjw_2bsU2Gcnow1O2PtG5N8Bndz5GUlqIayw"/>
                                    <span>United FC</span>
                                    <span class="ml-auto">38 PTS</span>
                                </div>
                                <div class="flex items-center p-2 rounded-lg hover:bg-green-100/20 transition-colors">
                                    <span class="w-6">4.</span>
                                    <img alt="City Rovers logo" class="size-6 mx-3 rounded-full" 
                                         src="https://lh3.googleusercontent.com/aida-public/AB6AXuBud4IEjqn2SzlAAtLbeVXEStdhKSRUl-XnPCsuPY_aoIpx4uAj0PDO6qpY0SUwDYof8TfmRHo6Mi3RSOcV5z5OVu96g30A_Xchdc3I01uklti-AEBCAlSH3dZN-k6QxuSmjv4bthl-OLxSujoAmRIzCU37NtzyddmQ_QvmZupTskfzZFZpnHN14alkdj7J5SP3T231IwRhP2lZqx2xVwlRj8GJKBGlLBE5TFtkqLfmkfmg05SNEdn_WOoCUWYWhhacKrjSiKX9zg"/>
                                    <span>City Rovers</span>
                                    <span class="ml-auto">35 PTS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>

<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    font-size: 24px;
}

.material-symbols-outlined.fill {
    font-variation-settings: 'FILL' 1;
}
</style>
