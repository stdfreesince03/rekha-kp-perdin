import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                'akhdani': {
                    'primary': '#3B82F6',
                    'secondary': '#64748B',
                    'orange': '#F97316',
                    'light': '#F8FAFC',
                }
            },
            fontFamily: {
                sans: ['Figtree', 'ui-sans-serif', 'system-ui'],
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
