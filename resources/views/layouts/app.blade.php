<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ?? 'Lydia Sena | Psicologia Clínica e Neuropsicológica' }}</title>
        <link rel="shortcut icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body class="font-sans antialiased bg-[#F9F6F3]">
        <div class="min-h-screen">
            {{-- Navegação com a Logo no canto esquerdo --}}
            @include('layouts.navigation')

            <main>
                {{-- Conteúdo principal sem a logo duplicada --}}
                <div class="max-w-7xl mx-auto pt-8 pb-12">
                    {{ $slot }}
                </div>
            </main>
        </div>
    </body>
</html>