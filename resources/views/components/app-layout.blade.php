<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description"
        content="Suitify adalah aplikasi inovatif untuk manajemen hotel, dirancang untuk mempermudah pengelolaan operasional hotel Anda.">
    <meta name="keywords"
        content="manajemen hotel, sistem perhotelan, hotel management system, aplikasi hotel, property management system, PMS, Suitify">
    <meta name="author" content="Suitify">
    <meta name="robots" content="index, follow">

    <!-- Open Graph / Facebook -->
    <meta property="og:title" content="Suitify - Sistem Manajemen Perhotelan Terintegrasi">
    <meta property="og:description"
        content="Kelola hotel Anda dengan mudah dan efisien menggunakan Suitify, solusi lengkap untuk manajemen hotel.">
    <meta property="og:image" content={{ asset('images/logo/suite_sense_logo_icon.png') }}">
    <meta property="og:url" content="https://suitify.com">
    <meta property="og:type" content="website">

    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Suitify - Sistem Manajemen Perhotelan Terintegrasi">
    <meta name="twitter:description"
        content="Kelola hotel Anda dengan mudah dan efisien menggunakan Suitify, solusi lengkap untuk manajemen hotel.">
    <meta name="twitter:image" content="{{ asset('images/logo/suite_sense_logo_icon.png') }}">

    <title>Suitify - Sistem Manajemen Perhotelan Terintegrasi</title>

    <!-- favicon icon -->
    <link rel="shortcut icon" href="{{ asset('images/logo/suite_sense_logo_icon.png') }}">
    <link rel="apple-touch-icon" href="images/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="72x72" href="images/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="114x114" href="images/apple-touch-icon-114x114.png">
    <!-- google fonts preconnect -->
    <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="stylesheet" href="css/layout/vendors.min.css" />
    <link rel="stylesheet" href="css/layout/icon.min.css" />
    <link rel="stylesheet" href="css/layout/style.css" />
    <link rel="stylesheet" href="css/layout/responsive.css" />
    <link rel="stylesheet" href="css/layout/suitify.css" />


    {{-- @vite(['resources/js/app.js']) --}}
    <script>
        const html = document.querySelector('html');
        const isLightOrAuto = localStorage.getItem('hs_theme') === 'light' || (localStorage.getItem('hs_theme') ===
            'auto' && !window.matchMedia('(prefers-color-scheme: dark)').matches);
        const isDarkOrAuto = localStorage.getItem('hs_theme') === 'dark' || (localStorage.getItem('hs_theme') ===
            'auto' && window.matchMedia('(prefers-color-scheme: dark)').matches);

        if (isLightOrAuto && html.classList.contains('dark')) html.classList.remove('dark');
        else if (isDarkOrAuto && html.classList.contains('light')) html.classList.remove('light');
        else if (isDarkOrAuto && !html.classList.contains('dark')) html.classList.add('dark');
        else if (isLightOrAuto && !html.classList.contains('light')) html.classList.add('light');
    </script>
</head>

<body data-mobile-nav-trigger-alignment="right" data-mobile-nav-style="modern" data-mobile-nav-bg-color="#1d1d1d">
    <x-navbar-layout />
    {{ $slot }}

    <!-- javascript libraries -->
    <script type="text/javascript" src="js/layout/jquery.js"></script>
    <script type="text/javascript" src="js/layout/vendors.min.js"></script>
    <script type="text/javascript" src="js/layout/main.js"></script>
</body>

</html>
