<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Crypto bots') }}</title>
        @include('layouts.favicon')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <style media="screen">
            /* .bitcoin-background{
                background-opacity: 0.5;
                background-image: url('{{ asset('img/bitcoin-broken.png') }}');
                background-repeat: no-repeat;
                background-position: center;
            } */
        </style>
    </head>
    <body class="">
        <div class="font-sans text-gray-900 antialiased bg-gray-100 dark:bg-gray-900">
            {{ $slot }}
        </div>
        @include('partials.js-theme-switcher')
    </body>
</html>
