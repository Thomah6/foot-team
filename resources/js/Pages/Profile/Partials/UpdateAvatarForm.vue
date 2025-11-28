<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const user = usePage().props.auth.user;

const avatarForm = useForm({
    avatar: null,
});

const posterForm = useForm({
    poster: null,
});

const avatarPreview = ref(user.avatar ? '/storage/' + user.avatar : null);
const posterPreview = ref(user.poster ? '/storage/' + user.poster : null);

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
        onSuccess: () => {
            console.log('Avatar update success');
            avatarForm.reset('avatar');
            // Recharger la page pour voir la nouvelle image
            window.location.reload();
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
        onSuccess: () => {
            console.log('Poster update success');
            posterForm.reset('poster');
            // Recharger la page pour voir la nouvelle image
            window.location.reload();
        },
        onError: (errors) => {
            console.log('Poster update error', errors);
        },
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Avatar et Poster
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Mettez à jour votre avatar et votre poster de profil.
            </p>
        </header>

        <div class="mt-6 space-y-8">
            <!-- Avatar Section -->
            <div class="space-y-4">
                <h3 class="text-md font-medium text-gray-800">Avatar</h3>
                
                <div class="flex items-center space-x-6">
                    <!-- Avatar Preview -->
                    <div class="shrink-0">
                        <img 
                            :src="avatarPreview || 'https://ui-avatars.com/api/?name=' + user.name + '&color=7F9CF5&background=EBF4FF'"
                            :alt="user.name"
                            class="h-20 w-20 rounded-full object-cover border-4 border-gray-200"
                        >
                    </div>

                    <!-- Avatar Upload -->
                    <div class="flex-1">
                        <InputLabel for="avatar" value="Avatar" />
                        <input
                            id="avatar"
                            type="file"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100"
                            @change="handleAvatarChange"
                            accept="image/*"
                        />
                        <InputError class="mt-2" :message="avatarForm.errors.avatar" />
                        
                        <p class="mt-1 text-xs text-gray-500">
                            Formats acceptés: JPG, PNG, GIF. Taille max: 2MB.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton 
                        @click="updateAvatar" 
                        :disabled="!avatarForm.avatar || avatarForm.processing"
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
                            class="text-sm text-gray-600"
                        >
                            Avatar mis à jour.
                        </p>
                    </Transition>
                </div>
            </div>

            <!-- Poster Section -->
            <div class="space-y-4">
                <h3 class="text-md font-medium text-gray-800">Poster de profil</h3>
                
                <div class="space-y-4">
                    <!-- Poster Preview -->
                    <div class="rounded-lg overflow-hidden border-2 border-gray-200">
                        <img 
                            v-if="posterPreview"
                            :src="posterPreview"
                            :alt="user.name + ' poster'"
                            class="w-full h-48 object-cover"
                        >
                        <div 
                            v-else
                            class="w-full h-48 bg-gradient-to-br from-green-400 to-blue-500 flex items-center justify-center"
                        >
                            <span class="text-white text-lg font-medium">Aucun poster</span>
                        </div>
                    </div>

                    <!-- Poster Upload -->
                    <div>
                        <InputLabel for="poster" value="Poster" />
                        <input
                            id="poster"
                            type="file"
                            class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                            @change="handlePosterChange"
                            accept="image/*"
                        />
                        <InputError class="mt-2" :message="posterForm.errors.poster" />
                        
                        <p class="mt-1 text-xs text-gray-500">
                            Formats acceptés: JPG, PNG. Recommandé: 1200x400px. Taille max: 5MB.
                        </p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <PrimaryButton 
                        @click="updatePoster" 
                        :disabled="!posterForm.poster || posterForm.processing"
                    >
                        Mettre à jour le poster
                    </PrimaryButton>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="posterForm.recentlySuccessful"
                            class="text-sm text-gray-600"
                        >
                            Poster mis à jour.
                        </p>
                    </Transition>
                </div>
            </div>
        </div>
    </section>
</template>
