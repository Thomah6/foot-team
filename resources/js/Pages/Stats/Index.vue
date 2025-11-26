<script setup>
import AdminsideBar from '@/Components/AdminsideBar.vue';
import FormStats from '@/Components/FormStats.vue';
</script>

<template>
    <Link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;900&amp;display=swap" rel="stylesheet"></Link>  
<Link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"></Link>

    <div class="relative flex min-h-screen w-full">
        <!-- SideNavBar -->
        <!-- <aside
            class="flex h-screen flex-col bg-white dark:bg-[#111a22] w-64 border-r border-gray-200 dark:border-gray-800 sticky top-0"
        >
            <div class="flex flex-col justify-between flex-1 p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-3 p-2">
                        <div
                            class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                            data-alt="Admin avatar"
                            style="
                                background-image: url('https://lh3.googleusercontent.com/aida-public/AB6AXuDXAP7Ip9l7ZDNGAAUWPAr3IoWx-arxHHugwWRgJF3v8OWmwGZUkegTjmVtieeCueHlh7wTL20akT7LFgq3eoVH9VezFeXLMxdF-YmDZ4XXVP1VO4TI-SWsJXDBxjGuf4iVk5wkUIjc6v0O7aNMQzL9wtKBi3gGmTOiRNymQfRSiH17UoWmjNU8SauYUMu1IWlVMKSS0vosdrK2aRTB-7PKsKSumU_pN7TACd1W-xDLDrIESLeVgtxBK0CR-FGSTThTIEUWv_tYAJJs');
                            "
                        ></div>
                        <div class="flex flex-col">
                            <h1
                                class="text-gray-900 dark:text-white text-base font-medium leading-normal"
                            >
                                Admin
                            </h1>
                            <p
                                class="text-gray-500 dark:text-[#92adc9] text-sm font-normal leading-normal"
                            >
                                admin@example.com
                            </p>
                        </div>
                    </div>
                    <nav class="flex flex-col gap-2 mt-4">
                        <a
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#233648]"
                            href="#"
                        >
                            <span class="material-symbols-outlined"
                                >dashboard</span
                            >
                            <p class="text-sm font-medium leading-normal">
                                Dashboard
                            </p>
                        </a>
                        <a
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#233648]"
                            href="#"
                        >
                            <span class="material-symbols-outlined">group</span>
                            <p class="text-sm font-medium leading-normal">
                                Players
                            </p>
                        </a>
                        <a
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#233648]"
                            href="#"
                        >
                            <span class="material-symbols-outlined"
                                >sports_soccer</span
                            >
                            <p class="text-sm font-medium leading-normal">
                                Matches
                            </p>
                        </a>
                        <a
                            class="flex items-center gap-3 px-3 py-2 rounded-lg bg-primary/10 dark:bg-[#233648] text-primary dark:text-white"
                            href="#"
                        >
                            <span
                                class="material-symbols-outlined"
                                style="font-variation-settings: 'FILL' 1"
                                >monitoring</span
                            >
                            <p class="text-sm font-medium leading-normal">
                                Statistics
                            </p>
                        </a>
                        <a
                            class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#233648]"
                            href="#"
                        >
                            <span class="material-symbols-outlined"
                                >settings</span
                            >
                            <p class="text-sm font-medium leading-normal">
                                Settings
                            </p>
                        </a>
                    </nav>
                </div>
                <div class="flex flex-col gap-1">
                    <a
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-[#233648]"
                        href="#"
                    >
                        <span class="material-symbols-outlined">logout</span>
                        <p class="text-sm font-medium leading-normal">Logout</p>
                    </a>
                </div>
            </div>
        </aside> -->
        <AdminsideBar />
        <!-- Main Content -->
        <main class="flex-1 p-6 lg:p-10">
            <div class="mx-auto max-w-7xl">
                <!-- Page Heading -->
                <header class="mb-8">
                    <p
                        class="text-gray-900 dark:text-white text-3xl md:text-4xl font-black leading-tight tracking-[-0.033em]"
                    >
                        Gestion manuelle des statistiques
                    </p>
                </header>
                <!-- Form Section -->
                <FormStats />
                <!-- Data Table Section -->
                <section>
                    <div
                        class="bg-white dark:bg-[#111a22] rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 overflow-hidden"
                    >
                        <div class="overflow-x-auto">
                            <table class="w-full text-sm text-left">
                                <thead
                                    class="bg-gray-50 dark:bg-[#192633] text-xs text-gray-700 dark:text-gray-400 uppercase"
                                >
                                    <tr>
                                        <th class="px-6 py-3" scope="col">
                                            Joueur
                                        </th>
                                        <th class="px-6 py-3" scope="col">
                                            Match
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center"
                                            scope="col"
                                        >
                                            Buts
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center"
                                            scope="col"
                                        >
                                            Passes D.
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center"
                                            scope="col"
                                        >
                                            Min.
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center"
                                            scope="col"
                                        >
                                            CJ
                                        </th>
                                        <th
                                            class="px-6 py-3 text-center"
                                            scope="col"
                                        >
                                            CR
                                        </th>
                                        <th
                                            class="px-6 py-3 text-right"
                                            scope="col"
                                        >
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-800"
                                    >
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            Kylian Mbappé
                                        </td>
                                        <td
                                            class="px-6 py-4 text-gray-600 dark:text-gray-300"
                                        >
                                            PSG vs Marseille
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            2
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            1
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            90
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="flex items-center justify-end gap-4"
                                            >
                                                <button
                                                    class="text-primary dark:text-blue-400 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >edit</span
                                                    >
                                                </button>
                                                <button
                                                    class="text-red-600 dark:text-red-500 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >delete</span
                                                    >
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-800"
                                    >
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            Antoine Griezmann
                                        </td>
                                        <td
                                            class="px-6 py-4 text-gray-600 dark:text-gray-300"
                                        >
                                            PSG vs Marseille
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            1
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            85
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            1
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="flex items-center justify-end gap-4"
                                            >
                                                <button
                                                    class="text-primary dark:text-blue-400 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >edit</span
                                                    >
                                                </button>
                                                <button
                                                    class="text-red-600 dark:text-red-500 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >delete</span
                                                    >
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr
                                        class="border-b border-gray-200 dark:border-gray-800"
                                    >
                                        <td
                                            class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap"
                                        >
                                            N'Golo Kanté
                                        </td>
                                        <td
                                            class="px-6 py-4 text-gray-600 dark:text-gray-300"
                                        >
                                            Lyon vs Monaco
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            90
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td
                                            class="px-6 py-4 text-center text-gray-600 dark:text-gray-300"
                                        >
                                            0
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="flex items-center justify-end gap-4"
                                            >
                                                <button
                                                    class="text-primary dark:text-blue-400 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >edit</span
                                                    >
                                                </button>
                                                <button
                                                    class="text-red-600 dark:text-red-500 hover:underline"
                                                >
                                                    <span
                                                        class="material-symbols-outlined text-xl"
                                                        >delete</span
                                                    >
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <!-- Empty State -->
                            <!--
                     <div class="text-center py-16">
                         <span class="material-symbols-outlined text-5xl text-gray-400 dark:text-gray-600">query_stats</span>
                         <p class="mt-4 text-gray-600 dark:text-gray-400">Aucune statistique trouvée.</p>
                         <p class="text-sm text-gray-500 dark:text-gray-500">Utilisez le formulaire ci-dessus pour ajouter la première entrée.</p>
                     </div>
                     -->
                        </div>
                    </div>
                </section>
            </div>
        </main>
    </div>
</template>

<style scoped></style>
