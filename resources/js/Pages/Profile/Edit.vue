<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateAvatarForm from './Partials/UpdateAvatarForm.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>
<template>
<AuthenticatedLayout>
    <Head title="Profile" />

        <div class="py-6 px-4 md:px-10">
            <div class="max-w-4xl mx-auto space-y-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-citron-50">Profil</h1>
                        <p class="text-sm text-gray-500 dark:text-citron-200">Mettez à jour vos informations de profil</p>
                    </div>
                    <div>
                        <Link href="/dashboard" class="text-sm text-citron-600 dark:text-citron-400 hover:text-citron-800 dark:hover:text-citron-300 transition-colors">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Retour au dashboard
                        </Link>
                    </div>
                </div>

                <!-- Avatar et Poster -->
                <div class="bg-white dark:bg-gray-800 p-4 shadow-lg sm:rounded-xl sm:p-8 border border-gray-100 dark:border-gray-700">
                    <UpdateAvatarForm class="max-w-2xl" />
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 shadow-lg sm:rounded-xl sm:p-8 border border-gray-100 dark:border-gray-700">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 shadow-lg sm:rounded-xl sm:p-8 border border-gray-100 dark:border-gray-700">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white dark:bg-gray-800 p-4 shadow-lg sm:rounded-xl sm:p-8 border border-gray-100 dark:border-gray-700">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    
    </AuthenticatedLayout>
</template>