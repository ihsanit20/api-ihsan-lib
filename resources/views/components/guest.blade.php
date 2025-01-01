<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Bengali:wght@100..900&display=swap" rel="stylesheet">

<style>
*{
    font-family: "Noto Sans Bengali", sans-serif;
}
</style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/css/fontAwesome5Pro.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 flex min-h-screen flex-col justify-between overflow-y-scroll scrollbar">
    <div class="sticky top-0 z-50 print:hidden">
        <x-header-main />
    </div>
    <div class="flex-grow">
        {{ $slot }}
    </div>
    <div class="print:hidden">
        <x-footer-bottom />
    </div>
</body>

</html>
