<nav class="bg-white dark:bg-gray-800 shadow-md">
    <div class="flex justify-between items-center px-6 py-4">
        <h1 class="text-2xl font-bold">Dashboard</h1>
        <div class="flex items-center space-x-4">
            <!-- Cambiar Tema -->
            <button id="theme-toggle" class="p-2 rounded bg-gray-200 dark:bg-gray-700">
                <span id="theme-icon">🌞</span>
            </button>
            <!-- Editar Perfil -->
            <a href="/profile" class="text-sm font-medium hover:underline">Editar Perfil</a>
            <!-- Cerrar Sesión -->
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-sm font-medium text-red-500 hover:underline focus:outline-none">
                    Cerrar Sesión
                </button>
            </form>
        </div>
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