<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ isset($title) ? config('app.name') .' - ' . $title : config('app.name', ' - Crypto bots') }}</title>
        @include('layouts.favicon')
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&amp;display=swap" rel="stylesheet">
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @livewireStyles
        @stack('styles')
        <style media="screen">
            [x-cloak] { display: none !important; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-900">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <main>
                {{ $slot }}
            </main>
            @include('layouts.footer')
        </div>
        @livewireScripts
        <link href="//cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function () {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-right',
                    showConfirmButton: false,
                    showCloseButton: true,
                    timer: 5000,
                    timerProgressBar:true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });
                window.Toast = Toast;
                window.addEventListener('alert',({detail:{type,message}})=>{
                    Toast.fire({
                        icon:type,
                        title:message
                    })
                });
            });
        </script>
        @include('partials.toast_message')
        @stack('scripts')
        @include('partials.js-theme-switcher')
    </body>
</html>
