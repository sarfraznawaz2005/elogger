<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/js/app.js'])

    <livewire:styles />

    @stack('css')
</head>
<body class="font-sans antialiased">

<x-flash/>
<x-toast/>
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
<script>
    window.notyf = new Notyf({duration: 5000, position: {x: 'right', y: 'bottom'}, ripple: false, dismissible: true})
</script>

<x-celebrate/>

<livewire:scripts />

@stack('js')

<script>
    window.paceOptions = {ajax: false, elements: false, restartOnPushState: false, restartOnRequestAfter: false}
</script>
<script src="/js/pace.min.js"></script>

</body>
</html>
