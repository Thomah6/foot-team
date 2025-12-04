<script setup>
import Checkbox from '@/Components/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>
<template>
    <GuestLayout>
        <Head title="Log in" />
        
        <div class="min-h-screen flex flex-col md:flex-row">
            <!-- Section gauche - Illustration -->
                        <!-- Section gauche - Video -->
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
                                <h1 class="text-4xl font-bold text-lime-800 mb-4">Welcome back</h1>
                                <p class="text-lime-700 text-lg">Connecte toi pour continuer</p>
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
                    <div v-if="status" class="mb-4 p-4 rounded-lg" :class="{
                        'bg-red-100 border border-red-400 text-red-700': $page.props.errors.status || status.includes('désactivé'),
                        'bg-green-100 border border-green-400 text-green-700': !$page.props.errors.status && !status.includes('désactivé')
                    }">
                        <p class="text-sm font-medium">{{ status }}</p>
                    </div>

                    <div class="md:hidden text-center mb-8">
                        <h1 class="text-3xl font-bold text-lime-700 mb-2">Connexion</h1>
                        <p class="text-lime-600">Accédez à votre compte</p>
                    </div>
                    
                    <form @submit.prevent="submit">
                        <div class="mb-4">
                            <InputLabel for="email" value="Email" class="text-lime-700 font-medium" />
                            <TextInput 
                                id="email" 
                                type="email" 
                                class="mt-1 block w-full border-lime-300 focus:border-lime-500 focus:ring-lime-500" 
                                v-model="form.email" 
                                required 
                                autofocus
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
                                autocomplete="current-password" 
                            />
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="mb-6 flex items-center justify-between">
                            <label class="flex items-center">
                                <Checkbox 
                                    name="remember" 
                                    v-model:checked="form.remember"
                                    class="text-lime-600 border-lime-300 focus:ring-lime-500"
                                />
                                <span class="ms-2 text-sm text-gray-600">Se souvenir de moi</span>
                            </label>
                            
                            <Link 
                                v-if="canResetPassword" 
                                :href="route('password.request')"
                                class="text-sm text-lime-600 hover:text-lime-800 hover:underline transition-colors"
                            >
                                Mot de passe oublié ?
                            </Link>
                        </div>

                        <div class="flex items-center justify-between mb-6">
                            <Link 
                                :href="route('register')"
                                class="text-sm text-lime-600 hover:text-lime-800 hover:underline transition-colors"
                            >
                                S'inscrire
                            </Link>

                            <PrimaryButton 
                                class="ml-4 bg-lime-500 hover:bg-lime-600 focus:ring-lime-500 text-white px-6 py-2 rounded-lg transition-colors"
                                :class="{ 'opacity-25': form.processing }" 
                                :disabled="form.processing"
                            >
                                Se connecter
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