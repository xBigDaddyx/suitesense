import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './node_modules/preline/dist/*.js',
        './vendor/jaocero/radio-deck/resources/views/**/*.blade.php',
        './vendor/codewithdennis/filament-price-filter/resources/**/*.blade.php',
        './vendor/codewithdennis/filament-simple-alert/resources/**/*.blade.php',
        './app/Filament/FrontOffice/**/*.php',
        './resources/views/filament/front-office/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    plugins: [require('preline/plugin')],
}
