<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="{{ asset('images/logo/suite_sense_logo_icon.png') }}">
    <meta property="og:image" content={{ asset('/images/logo/suite_sense_logo_icon.png') }}">
    <meta property="og:url" content="https://suitify.cloud">
    <meta property="og:type" content="website">
    <title>{{ $title ?? 'Page Title' }}</title>
    <style>
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 6rem;
            /* Adjust size for A4 */
            font-weight: 700;
            color: rgba(239, 68, 68, 0.1);
            /* Tailwind's red-500 with transparency */
            white-space: nowrap;
            pointer-events: none;
            z-index: 0;
            /* Ensure it appears beneath content */
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased">

    {{ $slot }}



</body>

</html>
