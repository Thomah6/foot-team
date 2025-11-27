<script setup>
import AdminsideBar from '@/Components/AdminsideBar.vue';




defineProps({
  stats: Object
});


</script>

<template>



  <!-- TopNavBar -->
  <header
    class="flex items-center justify-between whitespace-nowrap border-b border-border-light dark:border-border-dark px-10 py-3 bg-background-light dark:bg-background-dark sticky top-0 z-10">
    <h2 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold leading-tight tracking-[-0.015em]">
      Dashboard</h2>
    <div class="flex flex-1 justify-end items-center gap-4">
      <label class="relative flex flex-col w-full max-w-sm">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3">
          <span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">search</span>
        </div>
        <input
          class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-md text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark h-10 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark pl-10 text-base font-normal leading-normal"
          placeholder="Search..." value="" />
      </label>
      <button
        class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md size-10 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-primary-light dark:text-text-primary-dark hover:bg-primary/10 transition-colors">
        <span class="material-symbols-outlined text-2xl">notifications</span>
      </button>
      <div class="relative group">
        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 cursor-pointer"
          data-alt="User avatar"
          style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDlTFN6OT7OU1OEzGjNwBdl7OOHij6dgzS2tATsNAQ5RdH0g6FTA3GX7ZpP-srSjG4C4Row0HL8olRO0dchQnz1mg3GNapJyW6dWD7wGZYSvN4agceQ9zVTPp8ctgoaKQckv3kqwC_0B4DvbcaNzuJFGy8OzvdRab-6efWdc84HhOtBo4FmxvFeOErJaz10tBqsxtgeX-qp3zrohSWC21nXy2VO7v-RLxi6d1twM_ydPIaDD4iBTbLx4iPJps_pwoZvoS9SrWMu8A");'>
        </div>
        <div
          class="absolute right-0 mt-2 w-48 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none group-hover:pointer-events-auto">
          <a class="block px-4 py-2 text-sm text-text-primary-light dark:text-text-primary-dark hover:bg-surface-light dark:hover:bg-surface-dark"
            href="#">Profile</a>
          <a class="block px-4 py-2 text-sm text-text-primary-light dark:text-text-primary-dark hover:bg-surface-light dark:hover:bg-surface-dark"
            href="#">Settings</a>
          <div class="border-t border-border-light dark:border-border-dark my-1"></div>
          <a class="block px-4 py-2 text-sm text-red-500 hover:bg-surface-light dark:hover:bg-surface-dark"
            href="#">Logout</a>
        </div>
      </div>
    </div>
  </header>
  <Link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet">
  </Link>
  <Link
    href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
    rel="stylesheet">
  </Link>
  
  
  <div
    class="bg-background-light dark:bg-background-dark font-display text-text-primary-light dark:text-text-primary-dark">

    <div class="flex h-full min-h-screen">
      <!-- SideNavBar -->
      <!-- <aside
      class="flex w-64 flex-col border-r border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-4">
        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3 px-2 py-2">

            <div class="flex flex-col">

            </div>
          </div>
          <nav class="flex flex-col gap-2 mt-4">
            <a class="flex items-center gap-3 px-3 py-2 rounded-md bg-primary/10 text-primary" href="#">

            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">group</span>
              <p class="text-sm font-medium leading-normal">Members</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">groups</span>
              <p class="text-sm font-medium leading-normal">Sub-Teams</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">account_balance_wallet</span>
              <p class="text-sm font-medium leading-normal">Cashbox</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">comment</span>
              <p class="text-sm font-medium leading-normal">Reflections</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">photo_library</span>
              <p class="text-sm font-medium leading-normal">Gallery</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">checklist</span>
              <p class="text-sm font-medium leading-normal">Attendances</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">bar_chart</span>
              <p class="text-sm font-medium leading-normal">Stats</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">emoji_events</span>
              <p class="text-sm font-medium leading-normal">Player of the Month</p>
            </a>
            <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
              <span class="material-symbols-outlined text-2xl">newspaper</span>
              <p class="text-sm font-medium leading-normal">Featured News</p>
            </a>
          </nav>
        </div>
        <div class="mt-auto flex flex-col gap-2">
          <a class="flex items-center gap-3 px-3 py-2 rounded-md hover:bg-primary/10 transition-colors" href="#">
            <span class="material-symbols-outlined text-2xl">settings</span>
            <p class="text-sm font-medium leading-normal">Settings</p>
          </a>
        </div>
      </aside> -->

    <a href="/admin/create">form</a>
  <AdminsideBar />

<div> 
      <!-- PageHeading -->
      <div>
        <div class="flex flex-wrap justify-between items-center gap-4 mb-8">
          <div class="flex flex-col gap-1">
            <p
              class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold leading-tight tracking-tight">
              bienvenue sur la page des membres</p>
          
              
          </div>
          <div class="flex items-center gap-4">
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md h-10 px-4 bg-transparent text-text-primary-light dark:text-text-primary-dark border border-border-light dark:border-border-dark text-sm font-medium leading-normal hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
              <span class="truncate">Post News</span>
            </button>
            <button
              class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md h-10 px-4 bg-primary text-white text-sm font-medium leading-normal hover:bg-primary/90 transition-colors">
              <span class="truncate">Add Member</span>
            </button>
          </div>
        </div>
        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            class="flex flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
            <p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal">
              Total Members</p>
            <div class="flex items-baseline gap-2">
              <p
                class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                {{ stats.totalUsers  }}</p>
              <p class="text-green-500 text-sm font-medium leading-normal flex items-center"><span
                  class="material-symbols-outlined text-base">arrow_upward</span>+0%</p>
            </div>
          </div>
          <div
            class="flex flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
            <p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal">
              Cashbax Balance</p>
            <div class="flex items-baseline gap-2">
              <p
                class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">
                {{ stats.balance }}</p>
              <p class="text-green-500 text-sm font-medium leading-normal flex items-center"><span
                  class="material-symbols-outlined text-base">arrow_upward</span>+0%</p>
            </div>
        
         
            
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

</template>

<style scoped>
.admin-layout {
  display: flex;
  height: 100vh;
  background-color: #f4f7f6;
}

.main-content {
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  overflow-y: auto;
}

.content-body {
  padding: 2rem;
}
</style>