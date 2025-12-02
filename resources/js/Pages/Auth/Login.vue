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

        <div v-if="status" class="mb-4 p-4 rounded-md" :class="{
            'bg-red-100 border border-red-400 text-red-700 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300': $page.props.errors.status || status.includes('désactivé'),
            'bg-green-100 border border-green-400 text-green-700 dark:bg-green-900/20 dark:border-green-700 dark:text-green-300': !$page.props.errors.status && !status.includes('désactivé')
        }">
            <p class="text-sm font-medium">{{ status }}</p>
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="email" value="Email" class="text-gray-800 dark:text-gray-200" />

                <TextInput 
                    id="email" 
                    type="email" 
                    class="mt-1 block w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-lime-500 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400" 
                    v-model="form.email" 
                    required 
                    autofocus
                    autocomplete="username" 
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="mt-4">
                <InputLabel for="password" value="Password" class="text-gray-800 dark:text-gray-200" />

                <TextInput 
                    id="password" 
                    type="password" 
                    class="mt-1 block w-full bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-700 text-gray-900 dark:text-gray-100 focus:border-lime-500 dark:focus:border-lime-400 focus:ring-lime-500 dark:focus:ring-lime-400" 
                    v-model="form.password" 
                    required
                    autocomplete="current-password" 
                />

                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 block">
                <label class="flex items-center">
                    <Checkbox 
                        name="remember" 
                        v-model:checked="form.remember"
                        class="text-lime-600 dark:text-lime-400 border-gray-300 dark:border-gray-700 focus:ring-lime-500 dark:focus:ring-lime-400 focus:border-lime-500 dark:focus:border-lime-400" 
                    />
                    <span class="ms-2 text-sm text-gray-700 dark:text-gray-300">Remember me</span>
                </label>
            </div>

            <div class="flex items-center justify-between mt-6">
                <Link 
                    v-if="canResetPassword" 
                    :href="route('password.request')"
                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-lime-600 dark:hover:text-lime-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:focus:ring-lime-400"
                >
                    Mot de passe oublié ?
                </Link>
                <Link 
                    :href="route('register')"
                    class="text-sm text-gray-700 dark:text-gray-300 hover:text-lime-600 dark:hover:text-lime-400 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:focus:ring-lime-400"
                >
                    S'inscrire
                </Link>

                <PrimaryButton 
                    class="ml-4 bg-lime-500 hover:bg-lime-600 dark:bg-lime-600 dark:hover:bg-lime-500 text-white focus:ring-lime-500 dark:focus:ring-lime-400 transition-colors duration-200" 
                    :class="{ 'opacity-25': form.processing }" 
                    :disabled="form.processing"
                >
                    Se connecter
                </PrimaryButton>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-3 bg-white dark:bg-gray-900 text-gray-500 dark:text-gray-400">Ou continuer avec</span>
                    </div>
                </div>

                <div class="mt-6">
                    <a 
                        :href="route('google.login')"
                        class="w-full inline-flex justify-center items-center py-3 px-4 border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm bg-white dark:bg-gray-800 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-lime-300 dark:hover:border-lime-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500 dark:focus:ring-lime-400 transition-all duration-200"
                    >
                        <svg class="w-5 h-5" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z" />
                        </svg>
                        <span class="ml-3">Google</span>
                    </a>
                </div>
            </div>
        </form>
    </GuestLayout>
</template>