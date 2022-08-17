<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @vite(['resources/js/app.js'])

    <livewire:styles />

    @stack('css')
</head>
<body class="font-sans antialiased">

<x-flash/>
<x-toast/>
<x-page-expired/>
<livewire:refresh/>

<div class="min-h-screen bg-gray-100">
    <livewire:navigation-menu/>

    <main>
        <div class="py-8">

            <div class="mx-auto">
                <x-bc-connection-checker/>
                <x-stats-checker/>
            </div>

            {{ $slot }}
        </div>
    </main>

</div>

@stack('modals')

<script src="/js/notyf.min.js"></script>
<script src="/js/custom.js?v={{time()}}"></script>

<x-celebrate/>

<livewire:scripts />

@stack('js')

<script src="/js/pace.min.js"></script>

</body>
</html>
