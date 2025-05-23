<x-app-layout>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'inicio' }" x-cloak>
    <h1 class="text-2xl font-bold mb-6">{{ $project->name }}</h1>
    
    <!-- Navegación de Pestañas -->
    <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
            <li class="mr-2">
                <button @click="tab = 'inicio'" :class="{ 'border-blue-600 text-blue-600': tab === 'inicio' }" 
                        class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">
                    Inicio
                </button>
            </li>
            <li class="mr-2">
                <button @click="tab = 'tareas'" :class="{ 'border-blue-600 text-blue-600': tab === 'tareas' }" 
                        class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">
                    Tareas
                </button>
            </li>
            <li class="mr-2">
                <button @click="tab = 'revision'" :class="{ 'border-blue-600 text-blue-600': tab === 'revision' }" 
                        class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">
                    Revisión
                </button>
            </li>
            <li class="mr-2">
                <button @click="tab = 'historial'" :class="{ 'border-blue-600 text-blue-600': tab === 'historial' }" 
                        class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">
                    Historial
                </button>
            </li>
        </ul>
    </div>

    <!-- Contenido de las Pestañas -->
    <div x-show="tab === 'inicio'" class="mb-8">
        @livewire('project-overview', ['project' => $project])
    </div>

    <div x-show="tab === 'tareas'" class="mb-8">
        @livewire('project-tasks', ['project' => $project]) <!-- Simplificado -->
    </div>
    <div x-show="tab === 'revision'" class="mb-8">
        @livewire('task-review-panel', ['project' => $project])
    </div>

    <div x-show="tab === 'historial'" class="mb-8">
        @livewire('task-history', ['project' => $project])
    </div>
</div>


</x-app-layout>
