import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './node_modules/preline/dist/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            colors: {
                primary:{
                    50: '#EAE5F6',
                    100: '#DED6F1',
                    200: '#C5B8E6',
                    300: '#AC9ADC',
                    400: '#937BD1',
                    500: '#7A5DC7',
                    600: '#5C3DAF',
                    700: '#462E86',
                    800: '#30205C',
                    900: '#1A1132',
                    950: '#0F0A1D'
                 }
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [require('@tailwindcss/forms'),require('@tailwindcss/typography'),require('preline/plugin')],
};
