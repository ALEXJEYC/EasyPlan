<nav class="fixed top-4 left-1/2 transform -translate-x-1/2 w-[95%] md:w-[92%] z-40 bg-white dark:bg-gray-800 shadow-xl rounded-2xl px-6 py-4 flex justify-between items-center transition-all duration-300">
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
        <button id="theme-toggle" class="p-2 rounded-full bg-gray-200 dark:bg-gray-700 hover:scale-105 transition">
            <span id="theme-icon">🌞</span>
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
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const html = document.documentElement;

    themeToggle.addEventListener('click', () => {
        if (html.classList.contains('dark')) {
            html.classList.remove('dark');
            themeIcon.textContent = '🌞';
        } else {
            html.classList.add('dark');
            themeIcon.textContent = '🌙';
        }
    });
</script>
