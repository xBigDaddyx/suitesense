import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    theme:{
        extend:{
            colors: {
                transparent: 'transparent',
                current: 'currentColor',
                primary:{
                    50: '#fffcea',
                    100: '#fff7c6',
                    200: '#ffee87',
                    300: '#ffdd49',
                    400: '#ffcb1f',
                    500: '#fbac08',
                    600: '#de8101',
                    700: '#b85a05',
                    800: '#95450b',
                    900: '#7b390c',
                    950: '#471c01',
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
