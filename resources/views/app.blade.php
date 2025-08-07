<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Inline style to set the HTML background color --}}
    <style>
        html {
            background-color: #fafafa;
        }
    </style>

    <title inertia>{{ config('app.name', 'Laravel') }}</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicon -->
    <link rel="icon" href="/icons/icon-192x192.svg" type="image/svg+xml">
    <link rel="icon" href="/icons/icon-192x192.svg" sizes="192x192" type="image/svg+xml">
    <link rel="icon" href="/icons/icon-512x512.svg" sizes="512x512" type="image/svg+xml">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.svg">
    <link rel="manifest" href="/site.webmanifest">

    <!-- Theme Color for Mobile Browsers -->
    <meta name="theme-color" content="#5f5fcd">
    <meta name="msapplication-TileColor" content="#5f5fcd">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    @routes
    @vite(['resources/js/app.ts', "resources/js/pages/{$page['component']}.vue"])
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>