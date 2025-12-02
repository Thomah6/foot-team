<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import CreateForm from '@/Pages/Reflections/CreateForm.vue';
import { useForm, usePage, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const create=ref(false);
const user = usePage().props.auth.user;

const avatarForm = useForm({
    avatar: null,
});

const posterForm = useForm({
    poster: null,
});

const avatarPreview = ref(user.avatar ? '/storage/' + user.avatar : null);
const posterPreview = ref(user.poster ? '/storage/' + user.poster : null);
console.log(posterPreview.value);

const handleAvatarChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        avatarForm.avatar = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            avatarPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const handlePosterChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        posterForm.poster = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            posterPreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const updateAvatar = () => {
    console.log('updateAvatar called', avatarForm.avatar);
    avatarForm.post(route('profile.avatar.update'), {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Avatar update success', page);
            avatarForm.reset('avatar');
            // Forcer le rechargement complet de la page pour mettre à jour les données utilisateur
            window.location.href = route('profile.edit');
        },
        onError: (errors) => {
            console.log('Avatar update error', errors);
        },
    });
};

const updatePoster = () => {
    console.log('updatePoster called', posterForm.poster);
    posterForm.post(route('profile.poster.update'), {
        preserveScroll: true,
        onSuccess: (page) => {
            console.log('Poster update success', page);
            posterForm.reset('poster');
            // Forcer le rechargement complet de la page pour mettre à jour les données utilisateur
            window.location.href = route('profile.edit');
        },
        onError: (errors) => {
            console.log('Poster update error', errors);
        },
    });
};
</script>
<template>
    <section>
        <!-- Message de succès -->
        <div v-if="$page.props.flash && $page.props.flash.success"
             class="mb-4 p-4 bg-green-50 dark:bg-green-900/30 border border-green-200 dark:border-green-800 rounded-lg">
            <p class="text-sm text-green-800 dark:text-green-300">{{ $page.props.flash.success }}</p>
        </div>

        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-citron-50">
                Avatar et Poster
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-citron-200">
                Mettez à jour votre avatar et votre poster de profil.
            </p>
        </header>

        <div class="mt-6 space-y-8">
            <!-- Avatar Section -->
            <div class="space-y-4">
                <h3 class="text-md font-medium text-gray-800 dark:text-citron-100">Avatar</h3>

                <div class="flex items-center space-x-6">
                    <!-- Avatar Preview -->
                    <div class="shrink-0">
                        <img
                            :src="avatarPreview || 'https://ui-avatars.com/api/?name=' + user.name + '&color=7F9CF5&background=EBF4FF'"
                            :alt="user.name"
                            class="h-20 w-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700"
                        >
                    </div>

                    <!-- Avatar Upload -->
                    <div class="flex-1">
                        <InputLabel for="avatar" value="Avatar" class="text-gray-700 dark:text-citron-100" />
                        <input
                            id="avatar"
                            type="file"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-citron-50 dark:file:bg-citron-900/50 file:text-citron-700 dark:file:text-citron-300 hover:file:bg-citron-100 dark:hover:file:bg-citron-800 transition-colors"
                            @change="handleAvatarChange"
                            accept="image/*"
                        />
                        <InputError class="mt-2" :message="avatarForm.errors.avatar" />

                        <p class="mt-1 text-xs text-gray-500 dark:text-citron-300">
                            Formats acceptés: JPG, PNG, GIF. Taille max: 2MB.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton
                        @click="updateAvatar"
                        :disabled="!avatarForm.avatar || avatarForm.processing"
                        class="bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white"
                    >
                        Mettre à jour l'avatar
                    </PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="avatarForm.recentlySuccessful"
                            class="text-sm text-citron-600 dark:text-citron-400"
                        >
                            Avatar mis à jour.
                        </p>
                    </Transition>
                </div>
            </div>

            <!-- Poster Section -->
            <div class="space-y-4">
                <h3 class="text-md font-medium text-gray-800 dark:text-citron-100">Poster de profil</h3>

                <div class="space-y-4">
                    <!-- Poster Preview -->
                    <div class="rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-700">
                        <img
                            v-if="posterPreview"
                            :src="posterPreview"
                            :alt="user.name + ' poster'"
                            class="w-full h-48 object-cover"
                        >
                        <div
                            v-else
                            class="w-full h-48 bg-gradient-to-br from-citron-400 to-citron-600 dark:from-citron-500 dark:to-citron-700 flex items-center justify-center"
                        >
                            <span class="text-white text-lg font-medium">Aucun poster</span>
                        </div>
                    </div>

                    <!-- Poster Upload -->
                    <div>
                        <InputLabel for="poster" value="Poster" class="text-gray-700 dark:text-citron-100" />
                        <input
                            id="poster"
                            type="file"
                            class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-citron-50 dark:file:bg-citron-900/50 file:text-citron-700 dark:file:text-citron-300 hover:file:bg-citron-100 dark:hover:file:bg-citron-800 transition-colors"
                            @change="handlePosterChange"
                            accept="image/*"
                        />
                        <InputError class="mt-2" :message="posterForm.errors.poster" />

                        <p class="mt-1 text-xs text-gray-500 dark:text-citron-300">
                            Formats acceptés: JPG, PNG. Recommandé: 1200x400px. Taille max: 5MB.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton
                        @click="updatePoster"
                        :disabled="!posterForm.poster || posterForm.processing"
                        class="bg-citron-600 hover:bg-citron-700 dark:bg-citron-500 dark:hover:bg-citron-600 text-white"
                    >
                        Mettre à jour le poster
                    </PrimaryButton>
                    <PrimaryButton
                        v-if="!create"
                        @click="create=true"
                        class="bg-gray-700 hover:bg-gray-800 dark:bg-gray-600 dark:hover:bg-gray-700 text-white"
                    >
                        Créer une Réflexion
                    </PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="posterForm.recentlySuccessful"
                            class="text-sm text-citron-600 dark:text-citron-400"
                        >
                            Poster mis à jour.
                        </p>
                    </Transition>
                </div>
                <CreateForm v-if="create" @close="create = false" />
            </div>
        </div>
    </section>
</template>