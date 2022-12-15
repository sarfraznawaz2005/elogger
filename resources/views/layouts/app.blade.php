<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="Sarfraz Ahmed (sarfraznawaz2005@gmail.com)">

    @if (!isLocalhost())
        <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    @vite(['resources/js/app.js'])

    {{--    <script src="https://cdn.tailwindcss.com"></script>--}}

    <livewire:styles/>

    @stack('css')
</head>
<body class="font-sans antialiased">

<x-flash/>
<x-toast/>
<x-confirm/>
<x-page-expired/>
<livewire:refresh/>

<div class="min-h-screen bg-gray-100 wrapper">
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

@if (!isLocalhost())
    <script src="/public/js/notyf.min.js"></script>
    <script src="/public/js/custom.js"></script>
@else
    <script src="/js/notyf.min.js"></script>
    <script src="/js/custom.js"></script>
@endif

<x-celebrate/>

<livewire:scripts/>

@stack('js')

<script src="https://cdnjs.cloudflare.com/ajax/libs/quicklink/2.3.0/quicklink.umd.js"></script>
<script>
    window.addEventListener('load', () => {
        quicklink.listen({
            el: document.querySelector('nav')
        });
    });
</script>

<!--
<script type="module" src="https://cdn.skypack.dev/pin/@hotwired/turbo@v7.1.0-V83RMQBlYCPK9CvTqQoL/mode=imports,min/optimized/@hotwired/turbo.js"></script>

<script src="https://cdn.jsdelivr.net/gh/livewire/turbolinks@v0.1.x/dist/livewire-turbolinks.js"
        data-turbolinks-eval="false"
        data-turbo-eval="false">
</script>
-->

@if (!isLocalhost())
    <script src="/public/js/pace.min.js"></script>
@else
    <script src="/js/pace.min.js"></script>
@endif

</body>
</html>
