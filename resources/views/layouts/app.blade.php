<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                <div class="bg-[#FDF2F4] pt-6 pb-2 text-center">
                    <div class="flex flex-col items-center">
                        <h1 class="text-4xl font-serif text-gray-800 mb-1">Lydia Sena</h1> 
                        <div class="h-[1px] w-32 bg-pink-300 mb-1"></div>
                        <p class="text-[10px] tracking-[0.2em] text-gray-600 uppercase">Psicologia Clínica e Neuropsicologia</p>
                    </div>
                </div>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
