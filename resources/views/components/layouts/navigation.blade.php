<nav class="fixed top-4 left-1/2 transform -translate-x-1/2 w-[95%] md:w-[92%] z-10 bg-white dark:bg-gray-800 shadow-xl rounded-2xl px-6 py-4 flex justify-between items-center transition-all duration-300">
    <!-- Botón Hamburguesa (solo en móviles) -->
    <button 
        @click="sidebarOpen = !sidebarOpen" 
        class="md:hidden text-gray-600 dark:text-gray-300 p-2 focus:outline-none"
    >
        <i class="fas fa-bars fa-lg"></i>
    </button>

    <!-- Título -->
    <h1 class="text-xl md:text-2xl font-bold text-gray-800 dark:text-white">EASYPLAN</h1>

    <!-- Controles de usuario -->
    <div class="flex items-center space-x-4">
        <!-- Cambiar Tema -->
        <button id="theme-toggle" type="button" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:scale-105 transition">
            <span id="theme-icon-light" class="hidden">🌞</span>
            <span id="theme-icon-dark" class="hidden">🌙</span>
        </button>

        <!-- Editar Perfil -->
        <a href="/profile" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:underline">Editar Perfil</a>

        <!-- Cerrar Sesión -->
        <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="text-sm font-medium text-red-500 hover:underline focus:outline-none">
                Cerrar Sesión
            </button>
        </form>
    </div>
</nav>

<script>
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