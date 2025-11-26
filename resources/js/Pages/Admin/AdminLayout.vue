<script setup>

</script>

<template>


  



<Link href="https://fonts.googleapis.com/css2?family=Lexend:wght@100..900&amp;display=swap" rel="stylesheet"></Link>
<Link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"></Link>




<div class="relative flex h-auto min-h-screen w-full flex-col">
<div class="flex h-full min-h-screen">
<!-- SideNavBar -->
<aside class="flex w-64 flex-col border-r border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-4">
<div class="flex flex-col gap-4">
<div class="flex items-center gap-3 px-2 py-2">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-lg size-10" data-alt="Abstract logo for a club" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBjeJUorb03AmubZfs-dGdxk-uIlSOG-IS4USL9V2y1gzr1QEN3a82Lf5Rflk_gAbyxL3atcRzZQ7fd84B5-oumbQU5rQl1mMGaRcd7JBprWoz447_FLP_r-0Kz8LpZSuIJYv4rjniixzQvbHNbgAKnlcpO-4tyXzzFwGg8RDU1ypjO6vHqdZK6LzDTAWEK0JAzHi4MNyupBCwRWsOvJ9NCQ9EPPjLa3BUrWsihxtKPBa7qGiDAVBC_e7pQRrYct0CwiraW1SQEdg");'></div>
<div class="flex flex-col">
<h1 class="text-text-primary-light dark:text-text-primary-dark text-base font-bold leading-normal">Lumina Club</h1>
<p class="text-text-secondary-light dark:text-text-secondary-dark text-sm font-normal leading-normal">Admin Panel</p>
</div>
</div>
<nav class="flex flex-col gap-2 mt-4">
<a class="flex items-center gap-3 px-3 py-2 rounded-md bg-primary/10 text-primary" href="#">
<span class="material-symbols-outlined fill text-2xl">dashboard</span>
<p class="text-sm font-medium leading-normal">Dashboard</p>
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
</aside>
<main class="flex-1 flex flex-col">
<!-- TopNavBar -->
<header class="flex items-center justify-between whitespace-nowrap border-b border-border-light dark:border-border-dark px-10 py-3 bg-background-light dark:bg-background-dark sticky top-0 z-10">
<h2 class="text-text-primary-light dark:text-text-primary-dark text-lg font-bold leading-tight tracking-[-0.015em]">Dashboard</h2>
<div class="flex flex-1 justify-end items-center gap-4">
<label class="relative flex flex-col w-full max-w-sm">
<div class="absolute inset-y-0 left-0 flex items-center pl-3">
<span class="material-symbols-outlined text-text-secondary-light dark:text-text-secondary-dark">search</span>
</div>
<input class="form-input flex w-full min-w-0 flex-1 resize-none overflow-hidden rounded-md text-text-primary-light dark:text-text-primary-dark focus:outline-0 focus:ring-2 focus:ring-primary/50 border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark h-10 placeholder:text-text-secondary-light dark:placeholder:text-text-secondary-dark pl-10 text-base font-normal leading-normal" placeholder="Search..." value=""/>
</label>
<button class="flex max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md size-10 bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark text-text-primary-light dark:text-text-primary-dark hover:bg-primary/10 transition-colors">
<span class="material-symbols-outlined text-2xl">notifications</span>
</button>
<div class="relative group">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10 cursor-pointer" data-alt="User avatar" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuDlTFN6OT7OU1OEzGjNwBdl7OOHij6dgzS2tATsNAQ5RdH0g6FTA3GX7ZpP-srSjG4C4Row0HL8olRO0dchQnz1mg3GNapJyW6dWD7wGZYSvN4agceQ9zVTPp8ctgoaKQckv3kqwC_0B4DvbcaNzuJFGy8OzvdRab-6efWdc84HhOtBo4FmxvFeOErJaz10tBqsxtgeX-qp3zrohSWC21nXy2VO7v-RLxi6d1twM_ydPIaDD4iBTbLx4iPJps_pwoZvoS9SrWMu8A");'></div>
<div class="absolute right-0 mt-2 w-48 bg-background-light dark:bg-background-dark border border-border-light dark:border-border-dark rounded-md shadow-lg opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none group-hover:pointer-events-auto">
<a class="block px-4 py-2 text-sm text-text-primary-light dark:text-text-primary-dark hover:bg-surface-light dark:hover:bg-surface-dark" href="#">Profile</a>
<a class="block px-4 py-2 text-sm text-text-primary-light dark:text-text-primary-dark hover:bg-surface-light dark:hover:bg-surface-dark" href="#">Settings</a>
<div class="border-t border-border-light dark:border-border-dark my-1"></div>
<a class="block px-4 py-2 text-sm text-red-500 hover:bg-surface-light dark:hover:bg-surface-dark" href="#">Logout</a>
</div>
</div>
</div>
</header>
<div class="flex-1 overflow-y-auto p-8">
<!-- PageHeading -->
<div class="flex flex-wrap justify-between items-center gap-4 mb-8">
<div class="flex flex-col gap-1">
<p class="text-text-primary-light dark:text-text-primary-dark text-3xl font-bold leading-tight tracking-tight">Welcome, Admin!</p>
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-normal leading-normal">Here's what's happening with your club today.</p>
</div>
<div class="flex items-center gap-4">
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md h-10 px-4 bg-transparent text-text-primary-light dark:text-text-primary-dark border border-border-light dark:border-border-dark text-sm font-medium leading-normal hover:bg-surface-light dark:hover:bg-surface-dark transition-colors">
<span class="truncate">Post News</span>
</button>
<button class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-md h-10 px-4 bg-primary text-white text-sm font-medium leading-normal hover:bg-primary/90 transition-colors">
<span class="truncate">Add Member</span>
</button>
</div>
</div>
<!-- Stats -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
<div class="flex flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal">Total Members</p>
<div class="flex items-baseline gap-2">
<p class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">124</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center"><span class="material-symbols-outlined text-base">arrow_upward</span>+2%</p>
</div>
</div>
<div class="flex flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal">Cashbox Balance</p>
<div class="flex items-baseline gap-2">
<p class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">$1,234.56</p>
<p class="text-green-500 text-sm font-medium leading-normal flex items-center"><span class="material-symbols-outlined text-base">arrow_upward</span>+5.2%</p>
</div>
</div>
<div class="flex flex-col gap-2 rounded-lg p-6 border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark">
<p class="text-text-secondary-light dark:text-text-secondary-dark text-base font-medium leading-normal">Recent Attendances</p>
<div class="flex items-baseline gap-2">
<p class="text-text-primary-light dark:text-text-primary-dark tracking-tight text-3xl font-bold leading-tight">85%</p>
<p class="text-red-500 text-sm font-medium leading-normal flex items-center"><span class="material-symbols-outlined text-base">arrow_downward</span>-1.5%</p>
</div>
</div>
</div>
<!-- SectionHeader -->
<h2 class="text-text-primary-light dark:text-text-primary-dark text-xl font-bold leading-tight tracking-tight mt-12 mb-4">Recent Activity</h2>
<!-- Compact List (Recent Activity) -->
<div class="bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark rounded-lg overflow-hidden">
<ul class="divide-y divide-border-light dark:divide-border-dark">
<li class="flex items-center justify-between p-4 hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors">
<div class="flex items-center gap-4">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar of Sarah Miller" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAL4a2_wl1Hn9aLmuE-z6B9qjf105G02g_i5mbPEgvbBoa8xbk-_G-OXidSaM1ysW2X9QlZz6JNHy04hG4dVVgXnd2jCohGAQy6ASCgxkyzv9Ao1YFW74MJrHnc89B_iueJAo8go4_MTRphSkEbX1B9q7wo1FKxGLPiamDbSqmjFXPIPMh-4GZAJ0kSwlAC5DiYz3gcw8Ykk9JpKLAbfnjq5cX9jCMRfeOEkCkccnCYWrXTib3zMB0LyjWRJiO-q6eOpJUxlIRgMg");'></div>
<div>
<p class="font-medium text-text-primary-light dark:text-text-primary-dark">Sarah Miller <span class="font-normal text-text-secondary-light dark:text-text-secondary-dark">paid her monthly dues.</span></p>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">5 minutes ago</p>
</div>
</div>
<span class="text-green-500 font-semibold">+$25.00</span>
</li>
<li class="flex items-center justify-between p-4 hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors">
<div class="flex items-center gap-4">
<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="Avatar of John Doe" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuBJ8z5nvfhzSB3kaLijhRaXGYzgCfNGsMJpbGIGEs2DQqDI9_KqdZSxlpxhcXwvWqJyDTzCanEEDbRzuTsTMlXoR3NM1kBhdd5YIhh6mvFCHdKMnn0fuAcBLl2g2auIYKFmHpMnQ84T7MP-UvMcSrsPOis114qSGSJ3DdHqQX0iS_0GaGKXSTABhX_TCCYHRPa23f0jQMwL2SRnIB4DBKRIsGd97y2dRdLx-A37M9zP5-gFP071BTloUEmAXxh2rwVO3gE-3Et_Hg");'></div>
<div>
<p class="font-medium text-text-primary-light dark:text-text-primary-dark">John Doe <span class="font-normal text-text-secondary-light dark:text-text-secondary-dark">was added to the "Alpha Team".</span></p>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">1 hour ago</p>
</div>
</div>
<button class="text-primary hover:underline text-sm font-medium">View Team</button>
</li>
<li class="flex items-center justify-between p-4 hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors">
<div class="flex items-center gap-4">
<div class="size-10 rounded-full bg-secondary/20 flex items-center justify-center">
<span class="material-symbols-outlined text-secondary">calendar_month</span>
</div>
<div>
<p class="font-medium text-text-primary-light dark:text-text-primary-dark">New Event: <span class="font-normal text-text-secondary-light dark:text-text-secondary-dark">"Annual Club Picnic" was scheduled.</span></p>
<p class="text-sm text-text-secondary-light dark:text-text-secondary-dark">3 hours ago</p>
</div>
</div>
<button class="text-primary hover:underline text-sm font-medium">View Event</button>
</li>
</ul>
</div>
</div>
</main>
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