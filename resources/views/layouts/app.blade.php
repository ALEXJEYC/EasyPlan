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
<!-- TODO: AGREGAR ICONO A LA PAGINA PRINCIPAL -->
<title>EasyPlan</title>
@vite('resources/css/app.css')
@livewireStyles



<!-- Font Awesome y Alpine -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body class="bg-gray-100 dark:bg-gray-900 text-gray-800 dark:text-gray-200">

@auth
<div x-data="{ sidebarOpen: false, mobileMenuOpen: false }" class="flex h-screen relative">
    
    <!-- Menú lateral flotante (móviles) -->
<template x-if="sidebarOpen">
    <div 
        class="fixed inset-0 z-10 flex items-start justify-start pt-24 pl-4 pr-8 bg-black/40 backdrop-blur-sm md:hidden"
        @click.self="sidebarOpen = false"
        x-show="sidebarOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transient:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-init="initLogoAnimation()"
    >
        <div 
            class="bg-white dark:bg-gray-800 w-72 rounded-2xl shadow-2xl p-4"
            x-show="sidebarOpen"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-x-[-100%]"
            x-transition:enter-end="opacity-100 translate-x-0"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-x-0"
            x-transition:leave-end="opacity-0 translate-x-[-100%]"
        >
            <x-layouts.navbarleft />
        </div>
    </div>
</template>
    <!-- Menú lateral desktop -->
     <aside class="hidden md:block fixed top-28 left-10 h-[calc(95vh-7rem)] w-64 bg-white dark:bg-gray-800 shadow-lg rounded-xl p-4 z-10">
        <x-layouts.navbarleft />
    </aside>

     
    <!-- Contenido Principal -->
    <div class="flex-1 flex flex-col overflow-hidden">
        <!-- Navigation superior mejorada para móvil -->
        <x-layouts.navigation class="md:pl-72" />

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
@stack('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/animejs@3.2.1/lib/anime.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

<script>
function initLogoAnimation() {
    anime({
        targets: '.logo-line',
        strokeDashoffset: [anime.setDashoffset, 0],
        easing: 'easeInOutSine',
        duration: 3000,
        direction: 'alternate',
        loop: true
    });
}

// Ejecutar al cargar y cada vez que Alpine actualice el DOM
document.addEventListener('DOMContentLoaded', initLogoAnimation);
document.addEventListener('alpine:init', initLogoAnimation);
</script>

</body>
</html>