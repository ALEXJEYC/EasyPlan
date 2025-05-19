<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPlan</title>

    @vite('resources/css/app.css')
    @livewireStyles

    <!-- Font Awesome y Alpine -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

@auth
<div x-data="{ sidebarOpen: false }" class="flex h-screen relative">
    
    <!-- Menú lateral flotante (móviles) -->
    <aside 
     style="top: 120px; left: 30px; width: 270px;"
        x-show="sidebarOpen" 
        @click.away="sidebarOpen = false"
           class="fixed z-30 bg-white dark:bg-gray-800 shadow-lg rounded-lg p-4 transition-transform transform duration-300 md:hidden"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 -translate-x-10"
        x-transition:enter-end="opacity-100 translate-x-0"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100 translate-x-0"
        x-transition:leave-end="opacity-0 -translate-x-10"
    >
        <!-- <button @click="sidebarOpen = false" class="text-gray-500 hover:text-gray-900 dark:hover:text-white mb-4">
            <i class="fas fa-times"></i> Cerrar
        </button> -->
        <x-layouts.navbarleft />
    </aside>

    <!-- Menú lateral fijo (desktop) -->
     <aside class="hidden md:block fixed top-28 left-12 h-[calc(95vh-7rem)] w-64 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 z-10">
        <x-layouts.navbarleft />
    </aside>
     
    <!-- <aside class="hidden md:block fixed left-6 top-28 h-[calc(100vh-7rem)] w-64 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 z-10">
    <x-layouts.navbarleft />
</aside> -->



    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navigation superior (el botón hamburguesa va aquí) -->
        <x-layouts.navigation />



    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navigation superior (el botón hamburguesa va aquí) -->
        <x-layouts.navigation />

        <!-- Contenido -->
       <main class="flex-1 p-4 overflow-y-auto mt-16  md:pl-72">
            {{ $slot }}
        </main>
    </div>
</div>
@endauth

@guest
<!-- Si no está autenticado -->
<div >
    <div >
        {{ $slot }}
    </div>
</div>
@endguest

@livewireScripts
</body>
</html>
