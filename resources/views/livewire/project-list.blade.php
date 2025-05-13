<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <!-- Botón para crear un nuevo proyecto -->
    <div class="p-4 border rounded bg-blue-50 flex items-center justify-center cursor-pointer hover:bg-blue-100 transition" wire:click="openModal">
        <span class="text-4xl font-bold text-blue-600">+</span>
    </div>
        <!-- Modal para crear un proyecto -->
    @if ($isModalOpen)
        <div class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 z-50">
            <div class="bg-white p-6 rounded shadow-lg w-1/2">
                <h2 class="text-xl font-bold mb-4">Crear Nuevo Proyecto</h2>
                <livewire:create-project :organization="$organization" />
                <button type="button" wire:click="closeModal" class="mt-4 bg-gray-500 text-white px-4 py-2 rounded">Cerrar</button>
            </div>
        </div>
    @endif

    <!-- Modal para crear un proyecto -->
    @foreach ($projects as $project)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            <img src="{{ $project->getFirstMediaUrl('images') ?? 'https://via.placeholder.com/400x200' }}" alt="Imagen del Proyecto" class="w-full h-48 object-cover">
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