<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-body antialiased">
        <div class="min-vh-100 d-flex flex-column justify-content-sm-center align-items-center pt-5 pt-sm-0 bg-light">
            <div class="text-center">
                <a href="/">
                    <x-application-logo class="d-block mx-auto" style="width: 80px; height: 80px; color: #6c757d;" />
                </a>
            </div>

            <div class="w-100 mt-4 p-4 bg-white shadow-sm rounded" style="max-width: 28rem;">
                {{ $slot }}
            </div>
        </div>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>