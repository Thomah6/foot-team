<script setup>


</script>


<template>

<section
                    class="bg-white dark:bg-gray-900 rounded-lg sm:rounded-xl shadow-sm border border-gray-200 dark:border-gray-800 p-4 sm:p-6 mb-6 sm:mb-8"
                >
                    <h2
                        class="text-lg sm:text-xl md:text-2xl text-gray-900 dark:text-white font-bold leading-tight tracking-[-0.015em] mb-6"
                    >
                        Ajouter / Modifier une statistique
                    </h2>
                    <form
                        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6"
                    >
                        <!-- Select Player -->
                        <div class="flex flex-col">
                            <label
                                class="text-xs sm:text-sm md:text-base text-gray-800 dark:text-white font-medium leading-normal pb-2"
                                for="player"
                                >Joueur</label
                            >
                            <select
                                class="form-select w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-emerald-500 focus:ring-emerald-500 h-10 sm:h-12 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-normal leading-normal"
                                id="player"
                            >
                                <option value="">
                                    Sélectionner un joueur...
                                </option>
                                <option value="1">Kylian Mbappé</option>
                                <option value="2">Antoine Griezmann</option>
                                <option value="3">N'Golo Kanté</option>
                            </select>
                        </div>
                        <!-- Select Match -->
                        <div class="flex flex-col">
                            <label
                                class="text-xs sm:text-sm md:text-base text-gray-800 dark:text-white font-medium leading-normal pb-2"
                                for="match"
                                >Match</label
                            >
                            <select
                                class="form-select w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-gray-700 bg-gray-50 dark:bg-gray-800 focus:border-emerald-500 focus:ring-emerald-500 h-10 sm:h-12 px-3 sm:px-4 py-2 sm:py-3 text-xs sm:text-sm font-normal leading-normal"
                                id="match"
                            >
                                <option value="">
                                    Sélectionner un match...
                                </option>
                                <option value="1">
                                    PSG vs Marseille - 2025-09-15
                                </option>
                                <option value="2">
                                    Lyon vs Monaco - 2025-09-22
                                </option>
                            </select>
                        </div>
                        <!-- Minutes Played -->
                        <div class="flex flex-col">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="minutes"
                                >Minutes jouées</label
                            >
                            <input
                                class="form-input w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary h-14 p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9]"
                                id="minutes"
                                placeholder="ex: 90"
                                type="number"
                            />
                        </div>
                        <!-- Goals -->
                        <div class="flex flex-col">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="goals"
                                >Buts</label
                            >
                            <input
                                class="form-input w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary h-14 p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9]"
                                id="goals"
                                placeholder="ex: 2"
                                type="number"
                            />
                        </div>
                        <!-- Assists -->
                        <div class="flex flex-col">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="assists"
                                >Passes décisives</label
                            >
                            <input
                                class="form-input w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary h-14 p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9]"
                                id="assists"
                                placeholder="ex: 1"
                                type="number"
                            />
                        </div>
                        <!-- Yellow Cards -->
                        <div class="flex flex-col">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="yellow-cards"
                                >Cartons jaunes</label
                            >
                            <input
                                class="form-input w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary h-14 p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9]"
                                id="yellow-cards"
                                placeholder="ex: 1"
                                type="number"
                            />
                        </div>
                        <!-- Red Cards -->
                        <div class="flex flex-col lg:col-span-3">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="red-cards"
                                >Cartons rouges</label
                            >
                            <input
                                class="form-input w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary h-14 p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9]"
                                id="red-cards"
                                placeholder="ex: 0"
                                type="number"
                            />
                        </div>
                        <!-- Notes -->
                        <div class="md:col-span-2 lg:col-span-3">
                            <label
                                class="text-gray-800 dark:text-white text-base font-medium leading-normal pb-2"
                                for="notes"
                                >Notes</label
                            >
                            <textarea
                                class="form-textarea w-full rounded-lg text-gray-800 dark:text-white border-gray-300 dark:border-[#324d67] bg-gray-50 dark:bg-[#192633] focus:border-primary focus:ring-primary p-[15px] text-base font-normal leading-normal placeholder:text-gray-400 dark:placeholder:text-[#92adc9] resize-none"
                                id="notes"
                                placeholder="Ajouter un commentaire..."
                                rows="3"
                            ></textarea>
                        </div>
                        <!-- Action Buttons -->
                        <div
                            class="md:col-span-2 lg:col-span-3 flex justify-end gap-4 mt-4"
                        >
                            <button
                                class="px-6 py-3 rounded-lg bg-gray-200 dark:bg-[#324d67] text-gray-800 dark:text-white font-semibold text-sm hover:bg-gray-300 dark:hover:bg-[#415a73] transition-colors"
                                type="button"
                            >
                                Annuler
                            </button>
                            <button
                                class="px-6 py-3 rounded-lg bg-primary text-white font-semibold text-sm hover:bg-primary/90 transition-colors flex items-center gap-2"
                                type="submit"
                            >
                                <span
                                    class="material-symbols-outlined text-base"
                                    >add_circle</span
                                >
                                <span>Ajouter la statistique</span>
                            </button>
                        </div>
                    </form>
                </section>

</template>


<style scoped>


</style>