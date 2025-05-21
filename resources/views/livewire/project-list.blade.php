<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 col-span-2 md:col-span-2 lg:col-span-2">
    <!-- Listado de proyectos -->
    @foreach ($projects as $project)
        <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col"> -->
         <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 h-full flex flex-col">
        <!-- Imagen del proyecto -->
        <div class="relative h-48 bg-gray-200 dark:bg-gray-700 overflow-hidden">
            @if($project->hasMedia('images'))
            <img src="{{ $project->getFirstMediaUrl('images') }}" 
                 alt="{{ $project->name }}"
                 class="w-full h-full object-cover transition-transform duration-300 hover:scale-105"
                 loading="lazy"
                 onerror="this.onerror=null;this.src='{{ asset('images/default-project.png') }}'">
            @else
            <div class="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-600 dark:to-gray-700">
                <svg class="w-16 h-16 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
            @endif
        </div>

        <!-- Contenido del proyecto -->
        <div class="p-4 flex-1 flex flex-col">
            <h3 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $project->name }}</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-4 flex-1">{{ Str::limit($project->description ?? 'Sin descripción disponible', 120) }}</p>
            
            <!-- Acciones -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 flex justify-between items-center">
                <a href="{{ route('projects.show', $project) }}" 
                   class="text-blue-600 hover:text-blue-700 dark:hover:text-blue-500 transition-colors flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Ver
                </a>
                
                <form action="{{ route('projects.archive', $project) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <button type="submit" 
                            class="text-red-600 hover:text-red-700 dark:hover:text-red-500 transition-colors flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Archivar
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>