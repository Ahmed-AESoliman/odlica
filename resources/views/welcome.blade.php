<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600&display=swap" rel="stylesheet" />

    @livewireStyles
    @fluxAppearance
    @vite(['resources/css/app.css', 'resources/js/app.js'])


</head>

<body class="">
    <flux:button x-data x-on:click="$flux.dark = ! $flux.dark" icon="moon" variant="subtle"
        aria-label="Toggle dark mode" />
    @livewireScripts
    @fluxScripts

</body>

</html>
