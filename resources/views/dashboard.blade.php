
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <!-- Mensaje de bienvenida -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    Bienvenido a EasyPlan. Aquí puedes gestionar tus proyectos, chatear en tiempo real y más.
                </div>
            </div>
        </div>
    </div>

    <!-- Componente para crear organización -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <h2 class="text-xl font-bold mb-4">Crear Nueva Organización</h2>
        
        <!-- Mensaje de éxito -->
        @if (session()->has('message'))
            <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                {{ session('message') }}
            </div>
        @endif

        <livewire:create-organization />
    </div>

    <!-- Lista de organizaciones -->
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6 mb-12">
        <h2 class="text-xl font-bold mb-4">Mis Organizaciones</h2>
        <livewire:organization-list />
    </div>
</x-app-layout>