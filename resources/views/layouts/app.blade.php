<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPlan</title>
    @vite('resources/css/app.css')
    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">
    @auth
    <div class="flex h-screen">
        <!-- Navbar Izquierdo -->
        <aside class="w-64 bg-white dark:bg-gray-800 shadow-md">
            <div class="p-4 text-center">
                <x-application-logo class="h-10 w-auto mx-auto" />
                <h2 class="text-xl font-bold mt-2">EasyPlan</h2>
            </div>
            <nav class="mt-4">
                <ul>
                    <li class="px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <a href="/dashboard" class="block">Inicio</a>
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <a href="/projects" class="block">Proyectos</a>
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <a href="/chats" class="block">Chats</a>
                    </li>
                    <li class="px-4 py-2 hover:bg-gray-200 dark:hover:bg-gray-700">
                        <a href="/settings" class="block">Configuración</a>
                    </li>
                </ul>
            </nav>
        </aside>

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