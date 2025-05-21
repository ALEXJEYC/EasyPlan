<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Botón para crear proyecto -->
    <div class="p-4 border rounded bg-blue-50 hover:bg-blue-100 dark:bg-gray-800 dark:hover:bg-blue-800 flex items-center justify-center cursor-pointer transition" 
         wire:click="openModal">
        <span class="text-4xl font-bold text-blue-600 dark:text-white">+</span>
    </div>

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

    <!-- Modal para crear proyecto -->
    @if ($isModalOpen)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" wire:click="closeModal"></div>
        
        <div class="relative w-full max-w-md mx-auto bg-white rounded-lg shadow-xl dark:bg-gray-800">
            <div class="flex justify-between items-center px-5 py-4 border-b dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-white">Crear Nuevo Proyecto</h2>
                <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                    ✕
                </button>
            </div>
            
            <div class="p-5">
                @livewire('create-project', ['organization' => $organization], key('create-project-'.$organization->id))
            </div>
        </div>
    </div>
    @endif
</div>