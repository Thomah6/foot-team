import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                heading: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                // Palette primaire - Bleu football professionnel
                primary: {
                    50: '#f0f9ff',
                    100: '#e0f2fe',
                    200: '#bae6fd',
                    300: '#7dd3fc',
                    400: '#38bdf8',
                    500: '#0ea5e9',  // Bleu principal
                    600: '#0284c7',  // Bleu foncé
                    700: '#0369a1',  // Bleu très foncé
                    800: '#075985',
                    900: '#0c3d66',
                },
                // Accent - Vert électrique pour les actions positives
                accent: {
                    50: '#f0fdf4',
                    100: '#dcfce7',
                    200: '#bbf7d0',
                    300: '#86efac',
                    400: '#4ade80',
                    500: '#22c55e',  // Vert principal
                    600: '#16a34a',  // Vert foncé
                    700: '#15803d',
                    800: '#166534',
                    900: '#145231',
                },
                // Couleurs de surface - Neutres pour fond/cartes
                surface: {
                    light: '#ffffff',
                    dark: '#1a1a1a',
                    gray: '#f8f9fa',
                    'gray-light': '#f3f4f6',
                    'gray-dark': '#374151',
                },
                // État des couleurs
                success: '#22c55e',
                warning: '#f59e0b',
                error: '#ef4444',
                info: '#3b82f6',
                
                // Texte
                'text-light': '#1f2937',
                'text-dark': '#f3f4f6',
                'text-muted': '#6b7280',
                
                // Subtext pour descriptions
                'subtext-light': '#6b7280',
                'subtext-dark': '#9ca3af',
                
                // Background
                'background-light': '#ffffff',
                'background-dark': '#111827',
            },
            spacing: {
                'safe-top': 'env(safe-area-inset-top)',
                'safe-bottom': 'env(safe-area-inset-bottom)',
                'safe-left': 'env(safe-area-inset-left)',
                'safe-right': 'env(safe-area-inset-right)',
            },
            borderRadius: {
                'card': '0.75rem',      // Pour les cartes
                'button': '0.5rem',     // Pour les boutons
                'input': '0.5rem',      // Pour les inputs
                'modal': '1rem',        // Pour les modales
            },
            boxShadow: {
                'card': '0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06)',
                'card-hover': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                'elevation': '0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)',
                'button': '0 2px 4px 0 rgba(0, 0, 0, 0.1)',
            },
            fontSize: {
                'heading-1': ['2.5rem', { lineHeight: '1.2', fontWeight: '700' }],
                'heading-2': ['2rem', { lineHeight: '1.25', fontWeight: '700' }],
                'heading-3': ['1.5rem', { lineHeight: '1.375', fontWeight: '600' }],
                'heading-4': ['1.25rem', { lineHeight: '1.5', fontWeight: '600' }],
                'subheading': ['1rem', { lineHeight: '1.5', fontWeight: '600' }],
                'body': ['1rem', { lineHeight: '1.5', fontWeight: '400' }],
                'caption': ['0.875rem', { lineHeight: '1.25', fontWeight: '400' }],
                'small': ['0.75rem', { lineHeight: '1rem', fontWeight: '400' }],
            },
            animation: {
                'fade-in': 'fadeIn 0.3s ease-in',
                'slide-up': 'slideUp 0.3s ease-out',
                'pulse-soft': 'pulseSoft 2s cubic-bezier(0.4, 0, 0.6, 1) infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                slideUp: {
                    '0%': { transform: 'translateY(10px)', opacity: '0' },
                    '100%': { transform: 'translateY(0)', opacity: '1' },
                },
                pulseSoft: {
                    '0%, 100%': { opacity: '1' },
                    '50%': { opacity: '.8' },
                },
            },
        },
    },

    plugins: [forms],
};
