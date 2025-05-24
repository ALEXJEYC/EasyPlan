<!DOCTYPE html>
<html lang="es">
<head>
    <!-- Script de inicialización del tema DEBE SER LO PRIMERO -->
    <script>
        // Bloque de script crítico (ejecución inmediata)
        (function() {
            try {
                var savedTheme = localStorage.getItem('theme');
                var systemDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = savedTheme || (systemDark ? 'dark' : 'light');
                
                // Aplicar clase dark inmediatamente
                if (theme === 'dark') {
                    document.documentElement.classList.add('dark');
                }
                
                // Guardar preferencia del sistema si no hay tema guardado
                if (!savedTheme) {
                    localStorage.setItem('theme', systemDark ? 'dark' : 'light');
                }
            } catch(e) { console.error('Error setting theme:', e); }
        })();
    </script>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EasyPlan</title>

    @vite(['resources/js/app.js', 'resources/css/app.css'])
    @livewireStyles

    <!-- Font Awesome y Alpine -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

@auth
<div x-data="{ sidebarOpen: false }" class="flex h-screen relative">
    
    <!-- Menú lateral flotante (móviles) -->
    <aside 
     style="top: 100px; left: 10px; width: 270px;"
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
        <x-layouts.navbarleft />
    </aside>

    <!-- Menú lateral fijo (desktop) -->
     <aside class="hidden md:block fixed top-28 left-12 h-[calc(95vh-7rem)] w-64 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 z-10">
        <x-layouts.navbarleft />
    </aside>
     
    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navigation superior -->
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
<div>
    <div>
        {{ $slot }}
    </div>
</div>
@endguest

@livewireScripts
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    anime({
        targets: '.logo-line',
        strokeDashoffset: [anime.setDashoffset, 0],
        easing: 'easeInOutSine',
        duration: 3000,
        direction: 'alternate',
        loop: true
    });
});
</script>

@stack('scripts')
</body>
</html>