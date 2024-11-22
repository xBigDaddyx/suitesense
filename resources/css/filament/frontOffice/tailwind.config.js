import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    theme:{
        extend:{
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
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
        }

    },

    content: [
        './node_modules/preline/dist/*.js',
        './vendor/awcodes/filament-table-repeater/resources/**/*.blade.php',
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
        './vendor/codewithdennis/filament-price-filter/resources/**/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
        './app/Filament/FrontOffice/**/*.php',
        './resources/views/filament/front-office/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    plugins: [require('preline/plugin')],
}
