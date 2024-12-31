<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('favicon.png') }}" type="image/x-icon">

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

<body class="md:flex bg-gray-100 overflow-y-scroll scrollbar">
    <div class="hidden md:block bg-white shadow-md">
        <div class="sticky top-0 print:hidden">
            <x-sidebar />
        </div>
    </div>
    <div class="flex-1 flex min-h-screen flex-col justify-between">
        <div class="sticky top-0 z-50 print:hidden">
            <x-header-dashboard />
        </div>
        <div class="flex-grow pb-16 md:pb-0">
            {{ $slot }}
        </div>
        <div class="fixed md:hidden bottom-0 z-50 block w-full print:hidden">
            <x-bottombar />
        </div>
        <div class="hidden md:block print:hidden">
             <x-footer-bottom />
        </div>
    </div>
</body>

</html>
