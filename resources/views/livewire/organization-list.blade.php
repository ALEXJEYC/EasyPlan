<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    @if ($organizations->isEmpty())
        <div class="col-span-2 flex flex-col items-center py-12">
            <i class="fas fa-building text-5xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg">No tienes organizaciones creadas.</p>
        </div>
    @else
        @foreach ($organizations as $organization)
            <a href="{{ route('organizations.show', $organization) }}" class="block group">
                <div
                    class="bg-white dark:bg-gray-800 rounded-2xl shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-xl hover:border-indigo-400 transition-all duration-300">
                    <!-- Imagen o iniciales de la organización -->
                    <div class="relative">
                        @if ($organization->hasMedia('images'))
                            <img src="{{ $organization->getFirstMediaUrl('images') }}" alt="{{ $organization->name }}"
                                class="w-full h-44 object-cover" loading="lazy">
                        @else
                            <div class="w-full h-44 flex items-center justify-center bg-indigo-100 dark:bg-indigo-900">
                                <span class="text-5xl font-bold text-indigo-600 dark:text-indigo-300 select-none">
                                    {{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($organization->name, 0, 2)) }}
                                </span>
                            </div>
                        @endif

                    </div>

                    <!-- Contenido de la tarjeta -->
                    <div class="p-5">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100 mb-1 truncate">
                            {{ $organization->name }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                            {{ $organization->description ?? 'Sin descripción disponible.' }}</p>

                        <!-- Cantidad de usuarios -->
                        <div class="flex items-center space-x-2 mt-2">
                            <i class="fas fa-users text-indigo-400"></i>
                            <span class="text-sm text-gray-500 dark:text-gray-400">
                                {{ $organization->members->count() }}
                                {{ Str::plural('miembro', $organization->members->count()) }}
                            </span>
                        </div>
                    </div>
                </div>
            </a>
        @endforeach
    @endif
</div>
