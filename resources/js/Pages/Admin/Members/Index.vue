<style>
/* Boutons globaux cohérents */
.btn-primary {
  @apply rounded-lg bg-citron-500 px-4 py-2.5 text-sm font-semibold text-white hover:bg-citron-600 transition-all duration-200 shadow-sm;
}

.btn-secondary {
  @apply rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 px-4 py-2.5 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-all duration-200;
}

/* Champs */
.input-select {
  @apply w-full rounded-lg border border-gray-300 dark:border-gray-600 px-3 py-2.5 text-sm bg-white dark:bg-gray-800 focus:ring-citron-500 focus:border-citron-500 dark:focus:ring-citron-600 dark:focus:border-citron-600 transition-all;
}
</style>