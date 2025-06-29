<nav class="fixed top-4 left-1/2 transform -translate-x-1/2 w-[95%] md:w-[92%] z-10 bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm shadow-xl rounded-2xl px-6 py-3 flex justify-between items-center transition-all duration-300 hover:shadow-2xl">
    <!-- Botón Hamburguesa mejorado -->
    <button 
        @click="sidebarOpen = !sidebarOpen" 
        class="md:hidden text-gray-600 dark:text-gray-300 p-2 rounded-lg transition-all"
        x-data="{ hover: false, isOpen: false }"
        @mouseenter="hover = true"
        @mouseleave="hover = false"
        :class="{ 'rotate-90': isOpen, 'bg-gray-100 dark:bg-gray-700': hover }"
    >
        <i class="fas fa-bars fa-lg transition-transform" 
           :class="{ 'fa-bounce': hover, 'rotate-45': isOpen }"></i>
    </button>

    <!-- Título con efecto neón sutil -->
    <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white transition-all duration-500 hover:text-blue-500 dark:hover:text-purple-400">
        EASYPLAN
    </h1>

    <!-- Controles de usuario -->
    <div class="flex items-center gap-1">

        <!-- Cambiar Tema con animación orbital -->
        <button 
            id="theme-toggle" 
            class="p-2.5 text-gray-600 dark:text-gray-300 rounded-full transition-all relative overflow-hidden"
            x-data="{ themeHover: false }"
            @mouseenter="themeHover = true"
            @mouseleave="themeHover = false"
            :class="{ 'bg-gray-100 dark:bg-gray-700': themeHover }"
        >
            <i id="theme-icon-light" class="fas fa-sun fa-lg hidden"></i>
            <i id="theme-icon-dark" class="fas fa-moon fa-lg hidden"></i>
            <div class="absolute inset-0 opacity-0 transition-opacity duration-300"
                 :class="{ 'opacity-10': themeHover }"
                 style="background: radial-gradient(circle, currentColor 10%, transparent 70%)"></div>
        </button>

        <!-- Perfil con animación flotante -->
        <div class="relative" x-data="{ open: false, hoverProfile: false }">
            <button 
                @click="open = !open"
                class="p-2.5 text-gray-600 dark:text-gray-300 rounded-full transition-all"
                @mouseenter="hoverProfile = true"
                @mouseleave="hoverProfile = false"
                :class="{ 'bg-gray-100 dark:bg-gray-700': hoverProfile || open }"
            >
                <i class="fas fa-user-circle fa-lg transform transition-all"
                   :class="{ 'translate-y-1': hoverProfile }"></i>
            </button>
            
            <!-- Menú desplegable con animación -->
            <div 
                x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2"
                x-transition:enter-end="opacity-100 translate-y-0"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 translate-y-2"
                @click.away="open = false"
                class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700"
                x-cloak
            >
                <div class="p-2 space-y-2">
                    <a href="/settings/account" 
                       class="flex items-center px-3 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-all"
                       x-data="{ hoverItem: false }"
                       @mouseenter="hoverItem = true"
                       @mouseleave="hoverItem = false"
                       :class="{ 'translate-x-2': hoverItem }">
                        <i class="fas fa-user-edit mr-2 transition-transform"
                           :class="{ 'scale-125': hoverItem }"></i> Editar Perfil
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button 
                            type="submit"
                            class="w-full text-left flex items-center px-3 py-2 text-sm text-red-500 rounded-lg transition-all"
                            x-data="{ hoverExit: false }"
                            @mouseenter="hoverExit = true"
                            @mouseleave="hoverExit = false"
                            :class="{ 'bg-red-50 dark:bg-red-900/20 translate-x-3 opacity-75': hoverExit }"
                        >
                            <i class="fas fa-sign-out-alt mr-2 transform transition-all"
                               :class="{ 'rotate-45 scale-125': hoverExit }"></i> Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Animación especial para la campana
    function bellAnimation() {
        anime({
            targets: '#bell-icon',
            rotate: [-10, 10, -5, 5, 0],
            duration: 800,
            easing: 'easeInOutQuad'
        });
    }

    // Animación mejorada para todos los botones
    document.querySelectorAll('nav button').forEach(button => {
        button.addEventListener('mouseenter', () => {
            anime({
                targets: button,
                scale: 1.05,
                duration: 200,
                easing: 'easeOutElastic(1, .5)'
            });
        });
        
        button.addEventListener('mouseleave', () => {
            anime({
                targets: button,
                scale: 1,
                duration: 300,
                easing: 'easeOutExpo'
            });
        });
    });
    // Función para aplicar el tema (sincroniza con el script del head)
    function applyTheme(theme) {
        const html = document.documentElement;
        const themeIconLight = document.getElementById('theme-icon-light');
        const themeIconDark = document.getElementById('theme-icon-dark');
        
        if (theme === 'dark') {
            html.classList.add('dark');
            themeIconLight.classList.add('hidden');
            themeIconDark.classList.remove('hidden');
        } else {
            html.classList.remove('dark');
            themeIconDark.classList.add('hidden');
            themeIconLight.classList.remove('hidden');
        }
    }

    // Inicializar iconos según el tema actual
    function initThemeIcons() {
        const isDark = document.documentElement.classList.contains('dark');
        document.getElementById(isDark ? 'theme-icon-dark' : 'theme-icon-light').classList.remove('hidden');
    }

    // Evento para cambiar el tema
    document.getElementById('theme-toggle').addEventListener('click', () => {
        const html = document.documentElement;
        const newTheme = html.classList.contains('dark') ? 'light' : 'dark';
        
        localStorage.setItem('theme', newTheme);
        applyTheme(newTheme);
    });

    // Escuchar cambios en la preferencia del sistema
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            applyTheme(e.matches ? 'dark' : 'light');
        }
    });

    // Inicializar iconos al cargar
    document.addEventListener('DOMContentLoaded', initThemeIcons);
</script>