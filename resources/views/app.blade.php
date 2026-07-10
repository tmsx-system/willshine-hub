<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Willshine Hub - fresh products and quality produce for personal, retail, and business customers.">
    <meta name="theme-color" content="#EC4899">

    <!-- Open Graph -->
    <meta property="og:title" content="Willshine Hub - Fresh Products, Selected with Care">
    <meta property="og:description" content="Shop fresh fruit, produce, promotions, and rewards from Willshine Hub.">
    <meta property="og:type" content="website">

    <title inertia>{{ config('app.name', 'Willshine Hub') }}</title>

    <!-- Google Fonts: Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

    @vite('resources/js/app.js')
    @inertiaHead
</head>

<body class="font-sans antialiased">
    @inertia
</body>

</html>
