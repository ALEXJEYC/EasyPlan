<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @if ($organizations->isEmpty())
        <p class="text-gray-500 col-span-2">No tienes organizaciones creadas.</p>
    @else
        @foreach ($organizations as $organization)
            <a href="{{ route('organizations.show', $organization) }}" class="block">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <!-- Imagen de la organización -->
                    <img src="{{ $organization->image_url ?? 'https://via.placeholder.com/400x200' }}" alt="Imagen de la organización" class="w-full h-48 object-cover">

                    <!-- Contenido de la tarjeta -->
                    <div class="p-4">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $organization->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ $organization->description ?? 'Sin descripción disponible.' }}</p>

                        <!-- Cantidad de usuarios -->
                        <div class="mt-4">
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $organization->members->count() }} {{ Str::plural('miembro', $organization->members->count()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    @endif
</div>