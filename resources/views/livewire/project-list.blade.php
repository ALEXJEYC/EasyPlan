<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    
    <!-- Botón para crear un nuevo proyecto -->
<div class="p-4 border rounded 
            bg-blue-50 hover:bg-blue-100 
            dark:bg-blue-900 dark:hover:bg-blue-800 
            flex items-center justify-center cursor-pointer transition" 
     wire:click="openModal">
    <span class="text-4xl font-bold text-blue-600 dark:text-white">+</span>
</div>
        <!-- Modal para crear un proyecto -->
        @if ($isModalOpen)
            <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50 ">
                <div class="bg-white p-6 rounded shadow-lg w-1/2 dark:bg-gray-800">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-bold  dark:text-white">Crear Nuevo Proyecto</h2>
                        <button type="button" wire:click="closeModal" class="text-gray-500 hover:text-gray-700 text-2xl font-bold ">&times;</button>
                    </div>

                    <livewire:create-project :organization="$organization" />
                </div>
            </div>
        @endif

    <!-- Lista de proyectos -->
@foreach ($projects as $project)
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
@if($project->hasMedia('images'))
    <img src="{{ $project->getFirstMediaUrl('images') }}" 
         alt="{{ $project->name }}"
         class="w-full h-48 object-cover"
         onerror="this.onerror=null;this.src='{{ asset('images/default-project.png') }}'">
@else
    <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
        <img src="{{ asset('images/default-project.png') }}" 
             alt="Imagen por defecto" 
             class="h-full object-cover">
    </div>
@endif

            <div class="p-4">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $project->name }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $project->description ?? 'Sin descripción disponible.' }}</p>
            </div>
            <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <a href="{{ route('projects.show', $project) }}" class="text-blue-600 underline">Ver Proyecto</a>
                <form action="{{ route('projects.archive', $project) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="text-red-600 underline">Archivar</button>
                </form>
            </div>
        </div>
    @endforeach
</div>
