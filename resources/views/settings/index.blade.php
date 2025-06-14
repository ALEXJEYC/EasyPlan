<x-app-layout>
    <div class="p-6">
        <h1 class="text-2xl font-semibold mb-4">Configuración</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <a href="{{ route('settings.account') }}"
               class="p-4 bg-white dark:bg-gray-800 border rounded-lg hover:shadow transition">
                <i class="fas fa-user-cog text-blue-500 mr-2"></i>
                Cuenta
            </a>

            {{-- Botones futuros (deshabilitados visualmente) --}}
            <div class="p-4 bg-gray-100 dark:bg-gray-700 border border-dashed rounded-lg text-gray-400 cursor-not-allowed">
                <i class="fas fa-paint-brush mr-2"></i>
                Apariencia (Próximamente)
            </div>

            <div class="p-4 bg-gray-100 dark:bg-gray-700 border border-dashed rounded-lg text-gray-400 cursor-not-allowed">
                <i class="fas fa-bell mr-2"></i>
                Notificaciones (Próximamente)
            </div>
        </div>
    </div>
</x-app-layout>
