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
        <Head title="Connexion - FC Dynamo" />

        <!-- Status Alert -->
        <div v-if="status" :class="['alert mb-6', status.includes('désactivé') ? 'alert-error' : 'alert-success']">
            <i :class="['fa-solid', status.includes('désactivé') ? 'fa-circle-xmark' : 'fa-check-circle']"></i>
            <p class="text-sm font-medium">{{ status }}</p>
        </div>

        <!-- Form Title -->
        <div class="mb-8">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Connexion</h1>
            <p class="text-gray-600 dark:text-gray-400">Connectez-vous à votre compte FC Dynamo</p>
        </div>

        <form @submit.prevent="submit" class="space-y-6">
            <!-- Email Field -->
            <div class="form-group">
                <InputLabel for="email" value="Adresse email" required />
                <TextInput
                    id="email"
                    type="email"
                    v-model="form.email"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="exemple@email.com"
                />
                <InputError :message="form.errors.email" />
            </div>

            <!-- Password Field -->
            <div class="form-group">
                <InputLabel for="password" value="Mot de passe" required />
                <TextInput
                    id="password"
                    type="password"
                    v-model="form.password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••"
                />
                <InputError :message="form.errors.password" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center">
                <Checkbox id="remember" name="remember" v-model:checked="form.remember" />
                <label for="remember" class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                    Se souvenir de moi
                </label>
            </div>

            <!-- Submit Button -->
            <PrimaryButton 
                type="submit"
                size="lg"
                class="w-full"
                :disabled="form.processing"
            >
                <span v-if="form.processing" class="inline-flex items-center gap-2">
                    <i class="fas fa-spinner fa-spin"></i>
                    Connexion en cours...
                </span>
                <span v-else class="inline-flex items-center gap-2">
                    <i class="fas fa-sign-in-alt"></i>
                    Se connecter
                </span>
            </PrimaryButton>

            <!-- Forgot Password Link -->
            <div class="text-center">
                <Link
                    v-if="canResetPassword"
                    :href="route('password.request')"
                    class="text-sm text-primary-600 hover:text-primary-700 font-medium transition-colors"
                >
                    Mot de passe oublié ?
                </Link>
            </div>

            <!-- Divider -->
            <div class="relative my-6">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">Ou continuez avec</span>
                </div>
            </div>

            <!-- Google Login Button -->
            <a 
                :href="route('google.login')" 
                class="w-full inline-flex justify-center items-center gap-3 py-3 px-4 border border-gray-300 dark:border-gray-600 rounded-button bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 transition-colors"
            >
                <svg class="w-5 h-5" viewBox="0 0 24 24">
                    <path fill="currentColor" d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/>
                </svg>
                <span>Connexion avec Google</span>
            </a>

            <!-- Sign Up Link -->
            <div class="text-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Pas encore de compte ?
                    <Link :href="route('register')" class="font-medium text-primary-600 hover:text-primary-700 transition-colors">
                        Inscrivez-vous
                    </Link>
                </p>
            </div>
        </form>
    </GuestLayout>
</template>
