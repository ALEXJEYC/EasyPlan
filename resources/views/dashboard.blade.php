<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Bienvenida -->
        <x-card class="mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-4">Bienvenido a EasyPlan</h1>
                <p class="text-gray-600 dark:text-gray-300">Gestiona tus proyectos, colabora con tu equipo y aumenta tu productividad.</p>
            </div>
        </x-card>

        <!-- Contenido organizado en grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Crear organización -->
            <x-card class="md:col-span-2">
                <livewire:create-organization />
            </x-card>

            <!-- Lista de organizaciones -->
            <x-card class="md:col-span-2">
                <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white">Mis Organizaciones</h2>
                <livewire:organization-list />
            </x-card>
        </div>
    </div>
</x-app-layout>