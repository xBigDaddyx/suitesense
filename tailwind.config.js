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
                    '50': '#fffcea',
                    '100': '#fff7c6',
                    '200': '#ffee87',
                    '300': '#ffdd49',
                    '400': '#ffcb1f',
                    '500': '#fbac08',
                    '600': '#de8101',
                    '700': '#b85a05',
                    '800': '#95450b',
                    '900': '#7b390c',
                    '950': '#471c01',
                }
              },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [require('preline/plugin')],
};
