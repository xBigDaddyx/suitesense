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

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased relative min-h-screen bg-primary-50 flex flex-col" data-barba="wrapper" wire:transition>

    <x-layouts.header />
    {{ $slot }}

    <x-layouts.footer />


</body>

</html>
