<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    pseudo: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <GuestLayout>
        <Head title="Register" />
        
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Section gauche - Illustration -->
            <div class="hidden md:flex md:w-1/2 bg-gradient-to-br from-lime-50 to-green-50 items-center justify-center overflow-hidden">
                <div class="relative w-full h-full">
                    <!-- Video de fond -->
                    <video 
                        class="absolute inset-0 w-full h-full object-cover opacity-90"
                        autoplay
                        muted
                        loop
                        playsinline
                        preload="metadata"
                        poster="/storage/assets/blueLock.png"
                    >
                        <source src="/storage/assets/video.mp4" type="video/mp4">
                    </video>
                    
                    <!-- Overlay flou et coloré -->
                    <div class="absolute inset-0 backdrop-blur-sm bg-gradient-to-br from-lime-50/70 to-green-50/70"></div>
                    
                    <!-- Contenu par-dessus la vidéo -->
                    <div class="relative z-10 h-full flex items-center justify-center p-12">
                        <div class="max-w-md">
                            <div class="text-center mb-10">
                                <h1 class="text-4xl font-bold text-lime-800 mb-4">Rejoins nous</h1>
                                <p class="text-lime-700 text-lg">Commencez votre aventure avec nous</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -top-6 -left-6 w-24 h-24 bg-lime-200/50 rounded-full opacity-50"></div>
                                <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-green-200/50 rounded-full opacity-50"></div>
                                <div class="relative bg-white/80 backdrop-blur-sm p-8 rounded-2xl shadow-lg border border-lime-200/50">
                                    <div class="flex justify-center mb-6">
                                        <div class="w-16 h-16 bg-lime-100 rounded-full flex items-center justify-center">
                                            <svg class="w-8 h-8 text-lime-600" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-center text-gray-700 mb-4">Créez votre compte en quelques clics et profitez de toutes nos fonctionnalités.</p>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-lime-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-lime-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700">Accès immédiat</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-lime-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-lime-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700">Personnalisation complète</span>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-6 h-6 bg-lime-100 rounded-full flex items-center justify-center mr-3">
                                                <svg class="w-4 h-4 text-lime-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-gray-700">Sécurité garantie</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Séparateur vertical visible sur desktop -->
            <div class="hidden md:block absolute left-1/2 transform -translate-x-1/2 h-5/6 top-1/2 transform -translate-y-1/2">
                <div class="border-l border-gray-300 h-full"></div>
            </div>

            <!-- Section droite - Formulaire -->
            <div class="w-full md:w-1/2 flex items-center justify-center p-4 md:p-8">
                <div class="w-full max-w-md">
                    <div class="md:hidden text-center mb-8">
                        <h1 class="text-3xl font-bold text-lime-700 mb-2">Créer un compte</h1>
                        <p class="text-lime-600">Rejoignez notre communauté</p>
                    </div>
                    
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <InputLabel for="name" value="Nom complet" class="text-lime-700 font-medium" />
                            <TextInput
                                id="name"
                                type="text"
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500"
                                v-model="form.name"
                                required
                                autofocus
                                autocomplete="name"
                            />
                            <InputError class="mt-2" :message="form.errors.name" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="pseudo" value="Pseudo" class="text-lime-700 font-medium" />
                            <TextInput
                                id="pseudo"
                                type="text"
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500"
                                v-model="form.pseudo"
                                required
                                autocomplete="pseudo"
                            />
                            <InputError class="mt-2" :message="form.errors.pseudo" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="email" value="Email" class="text-lime-700 font-medium" />
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500"
                                v-model="form.email"
                                required
                                autocomplete="username"
                            />
                            <InputError class="mt-2" :message="form.errors.email" />
                        </div>

                        <div class="mb-4">
                            <InputLabel for="password" value="Mot de passe" class="text-lime-700 font-medium" />
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500"
                                v-model="form.password"
                                required
                                autocomplete="new-password"
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mb-6">
                            <InputLabel
                                for="password_confirmation"
                                value="Confirmer le mot de passe"
                                class="text-lime-700 font-medium"
                            />
                            <TextInput
                                id="password_confirmation"
                                type="password"
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500"
                                v-model="form.password_confirmation"
                                required
                                autocomplete="new-password"
                            />
                            <InputError
                                class="mt-2"
                                :message="form.errors.password_confirmation"
                            />
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <Link
                                :href="route('login')"
                                class="text-sm text-lime-600 hover:text-lime-800 hover:underline transition-colors"
                            >
                                Déjà inscrit ?
                            </Link>

                            <PrimaryButton 
                                class="ml-4 bg-lime-500 hover:bg-lime-600 focus:ring-lime-500 text-white px-6 py-2 rounded-lg transition-colors"
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                            >
                                S'inscrire
                            </PrimaryButton>
                        </div>

                        <div class="mb-6">
                            <div class="relative">
                                <div class="absolute inset-0 flex items-center">
                                    <div class="w-full border-t border-gray-300"></div>
                                </div>
                                <div class="relative flex justify-center text-sm">
                                    <span class="px-4 bg-white text-gray-500">Ou continuer avec</span>
                                </div>
                            </div>

                            <div class="mt-6">
                                <a :href="route('google.login')" 
                                   class="w-full inline-flex justify-center items-center py-3 px-4 border border-lime-300 rounded-lg shadow-sm bg-white text-sm font-medium text-lime-700 hover:bg-lime-50 hover:border-lime-400 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500">
                                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                                    </svg>
                                    <span class="ml-3">Google</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </GuestLayout>
</template>