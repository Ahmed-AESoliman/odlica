<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @livewireStyles
    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="antialiased dark:bg-gray-800 text-gray-900 dark:text-white
min-h-dvh">
    <livewire:layout.header />
    <livewire:layout.cart />
    <flux:main class="flex max-h-screen  gap-2">
        <livewire:layout.filter />
        {{ $slot }}
    </flux:main>


    @livewireScripts
    @fluxScripts

</body>

</html>
