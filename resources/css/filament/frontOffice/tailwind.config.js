import preset from '../../../../vendor/filament/filament/tailwind.config.preset'
const colors = require('tailwindcss/colors')

export default {
    presets: [preset],
    theme:{
        extend:{
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                primary:{
                    50: '#FFFFFF',
  100: '#F8F5FE',
  200: '#DFD0FB',
  300: '#C7AAF7',
  400: '#AE85F4',
  500: '#955FF0',
  600: '#7C3AED',
  700: '#5D14DB',
  800: '#470FA7',
  900: '#320B74',
  950: '#27085A'
                 },

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
