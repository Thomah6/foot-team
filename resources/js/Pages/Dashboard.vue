<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import BannerPlayerMonth from '@/Pages/Admin/News/BannerPlayerMonth.vue'
import ALaUne from '@/Pages/Admin/News/ALaUne.vue'
const page = usePage();
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <!-- <template #header>
            <h2
                class="text-xl font-semibold leading-tight text-gray-800"
            >
                Dashboard
            </h2>
        </template> -->

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

                <div>
                    <BannerPlayerMonth 
                    :player="page.props.player" 
                    :stats="page.props.stats"
                    :previousPlayers="page.props.previousPlayers" 
                    />
                </div>

                <div class="py-5">
                    <ALaUne :featured="page.props.featured" />

                </div>


                <!-- <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div> -->
            </div>
        </div>
    </AuthenticatedLayout>
</template>
