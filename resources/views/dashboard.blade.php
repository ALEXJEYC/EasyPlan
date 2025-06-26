<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-8">

        <!-- Bienvenida -->
        <x-card class="mb-8">
            <div class="text-center">
                <h1 class="text-3xl font-bold text-gray-800 dark:text-white mb-2">Bienvenido a <span class="font-bold mt-3 bg-gradient-to-r from-blue-500 to-purple-500 bg-clip-text text-transparent">EasyPlan</span></h1>
                <p class="text-gray-600 dark:text-gray-300">Gestiona tus proyectos, colabora con tu equipo y aumenta tu productividad.</p>
            </div>
        </x-card>

        <!-- Botón de nueva organización SIN tarjeta de fondo -->
        <div>
            <livewire:create-organization />
        </div>

        <!-- Lista de organizaciones -->
        <x-card class="md:col-span-2">
            <h2 class="text-xl font-bold mb-4 text-gray-800 dark:text-white flex items-center">
                <i class="fas fa-layer-group mr-2 text-indigo-500"></i> Mis Organizaciones
            </h2>
            <livewire:organization-list />
        </x-card>
    </div>
</x-app-layout>