import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        'node_modules/preline/dist/*.js',
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
                    '50': '#f7f6fc',
         '100': '#efedfa',
         '200': '#e1def6',
         '300': '#cbc4ee',
         '400': '#b0a2e3',
         '500': '#947cd6',
         '600': '#7e5bc6',
         '700': '#714db4',
         '800': '#5e4097',
         '900': '#4e367c',
         '950': '#312253',
                 }
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [require('preline/plugin')],
};
