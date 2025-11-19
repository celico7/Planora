import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class', // Active le mode sombre avec une classe
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './resources/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: '#0CBABA',
                secondary: '#380036',
                // Couleurs pour le mode sombre
                dark: {
                    bg: '#0f172a',      // Fond principal sombre
                    card: '#1e293b',    // Cartes/composants
                    hover: '#334155',   // Hover states
                    border: '#475569',  // Bordures
                    text: '#e2e8f0',    // Texte principal
                    muted: '#94a3b8',   // Texte secondaire
                },
            },
        },
    },

    plugins: [forms],
};
