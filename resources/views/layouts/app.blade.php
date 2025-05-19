<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPlan</title>
    @vite('resources/css/app.css')
    @livewireStyles
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    @auth
    <div class="flex h-screen">
        <!-- Navbar Izquierdo -->
            @include('layouts.navbarleft')
        <!-- Contenido Principal -->
        <div class="flex-1 flex flex-col">
            <!-- Navbar Superior -->
            @include('layouts.navigation')

            <!-- Contenido -->
            <main class="flex-1 p-6">
                @hasSection('content')
                    @yield('content') <!-- Renderiza el contenido de las vistas como show.blade.php -->
                @else
                    {{ $slot ?? '' }} <!-- Renderiza el slot si está definido (como en el dashboard) -->
                @endif
            </main>
        </div>
    </div>
    @else
    <!-- Si no está autenticado, solo muestra el contenido -->
    <div class="min-h-screen flex items-center justify-center">
        {{ $slot }}
    </div>
    @endauth

    @livewireScripts
</body>
</html>