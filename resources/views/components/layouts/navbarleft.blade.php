<aside class="w-full h-full flex flex-col bg-transparent">    <!-- Logo y título -->
    <div class="p-6 text-center" x-data="{ hoverLogo: false }" style="margin-top: 1.5rem;">
        <x-application-logo 
            class="h-12 w-auto mx-auto transform transition-all duration-300" 
            x-bind:class="hoverLogo ? 'scale-110' : ''"
            @mouseenter="hoverLogo = true"
            @mouseleave="hoverLogo = false"
        />
        <h2 class="text-xl font-bold mt-3 bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">
            EasyPlan
        </h2>
    </div>

    <!-- Menú de navegación -->
    <nav class="flex-1 mt-4 px-3 space-y-1 overflow-y-auto">
        <a href="/dashboard" 
           class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:pl-6 hover:shadow-sm"
           x-data="{ hover: false }"
           @mouseenter="hover = true"
           @mouseleave="hover = false">
            <i class="fas fa-home fa-lg w-6 text-center transition-all"
               x-bind:class="hover ? 'scale-125 text-blue-500' : ''"></i>
            <span class="font-medium">Inicio</span>
        </a>

        <!-- <a href="/projects" 
           class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:pl-6 hover:shadow-sm group"
           x-data="{ hover: false }"
           @mouseenter="hover = true"
           @mouseleave="hover = false">
            <i class="fas fa-folder fa-lg w-6 text-center transform transition-all"
             x-bind:class="hover ? 'scale-125 text-black-500' : ''"></i>
            <span class="font-medium">Proyectos</span>
        </a> -->
        <a href="#"
        @click.prevent
        class="pointer-events-none opacity-50 flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 group"
        x-data="{ hover: false }"
        @mouseenter="hover = true"
        @mouseleave="hover = false">
            <i class="fas fa-folder fa-lg w-6 text-center transform transition-all"
            x-bind:class="hover ? 'scale-125 text-black-500' : ''"></i>
            <span class="font-medium">Proyectos</span>
        </a>


        <!-- <a href="/chats" 
           class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:pl-6 hover:shadow-sm group"
           x-data="{ hover: false }"
           @mouseenter="hover = true"
           @mouseleave="hover = false">
            <i class="fas fa-comments fa-lg w-6 text-center transform transition-all"
               x-bind:class="hover ? 'scale-125 text-green-500' : ''"></i>
            <span class="font-medium">Chats</span>
        </a> -->
        <a href="#"
            @click.prevent
            class="pointer-events-none opacity-50 flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 group"
            x-data="{ hover: false }"
            @mouseenter="hover = true"
            @mouseleave="hover = false">
                <i class="fas fa-comments fa-lg w-6 text-center transform transition-all"
                x-bind:class="hover ? 'scale-125 text-green-500' : ''"></i>
                <span class="font-medium">Chats</span>
            </a>

        <a href="/settings" 
           class="flex items-center gap-3 px-4 py-3 text-gray-700 dark:text-gray-300 rounded-xl transition-all duration-200 hover:bg-gray-100 dark:hover:bg-gray-700 hover:pl-6 hover:shadow-sm group"
           x-data="{ hover: false }"
           @mouseenter="hover = true"
           @mouseleave="hover = false">
            <i class="fas fa-cog fa-lg w-6 text-center transform transition-all"
                x-bind:class="hover ? 'scale-125 text-red-500' : ''"></i>
            <span class="font-medium">Configuración</span>
        </a>
    </nav>
</aside>

<script>
    // Animaciones para los elementos del sidebar
    document.querySelectorAll('aside nav a').forEach(link => {
        link.addEventListener('mouseenter', () => {
            anime({
                targets: link,
                translateX: 5,
                duration: 200,
                easing: 'easeOutExpo'
            });
        });
        
        link.addEventListener('mouseleave', () => {
            anime({
                targets: link,
                translateX: 0,
                duration: 200,
                easing: 'easeOutExpo'
            });
        });
    });
</script>