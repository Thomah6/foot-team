<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DeleteUserForm from './Partials/DeleteUserForm.vue';
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue';
import UpdateAvatarForm from './Partials/UpdateAvatarForm.vue';
import { Head } from '@inertiajs/vue3';

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

    <div class="min-h-screen bg-gray-50">
        <!-- Navbar simple sans sidebar -->
        <nav class="border-b border-gray-100 bg-white">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <div class="flex">
                        <!-- Logo -->
                        <div class="flex shrink-0 items-center">
                            <Link href="/" class="text-xl font-bold text-gray-900">
                                FC Dynamo
                            </Link>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <!-- User Dropdown -->
                        <div class="relative ms-3">
                            <div class="flex items-center gap-3">
                                <Link href="/dashboard" class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-arrow-left mr-2"></i>
                                    Retour au dashboard
                                </Link>
                                <span class="text-gray-300">|</span>
                                <span class="text-gray-700">{{ $page.props.auth.user.name }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <!-- Avatar et Poster -->
                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateAvatarForm class="max-w-2xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div
                    class="bg-white p-4 shadow sm:rounded-lg sm:p-8"
                >
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>
