<div class="space-y-8">
    @php
    use App\Enums\TaskStatus;
    @endphp
    <!-- Sección de Creación de Tareas -->
    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-8 rounded-2xl shadow-2xl">
        <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-6 gap-4">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center min-w-[300px]">
                <svg class="w-6 h-6 mr-2 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Crear Nueva Tarea
            </h2>
        </div>

        <form wire:submit.prevent="createTask" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Título -->
                <div class="relative">
                    <input type="text" wire:model="title" placeholder="Título"
                           class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @error('title') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Fecha Límite -->
                <div class="relative">
                    <input type="date" wire:model="deadline"
                           class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    @error('deadline') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Descripción -->
                <div class="md:col-span-2 relative">
                    <textarea wire:model="description" placeholder="Descripción"
                              class="w-full pl-12 pr-4 py-3 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent h-32"></textarea>
                    <svg class="w-5 h-5 absolute left-4 top-3.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    @error('description') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>

                <!-- Asignación de Usuarios -->
                <div class="md:col-span-2">
                    <div class="bg-white dark:bg-gray-800 p-4 rounded-xl border border-gray-200 dark:border-gray-700">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Asignar a:</label>
                        <div class="space-y-3 max-h-40 overflow-y-auto">
                            @foreach ($users as $user)
                            <label class="flex items-center space-x-3 p-3 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition-colors cursor-pointer">
                                <input type="checkbox" 
                                       wire:model="assignedTo"
                                       value="{{ $user->id }}"
                                       class="form-checkbox h-5 w-5 text-blue-600 dark:text-blue-400 rounded border-2 transition-colors">
                                <div class="flex items-center">
                                    <div class="relative shrink-0">
                                        <img class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800 shadow-sm" 
                                             src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                             alt="{{ $user->name }}">
                                        <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-400 rounded-full border-2 border-white dark:border-gray-800"></div>
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    @error('assignedTo') <span class="text-red-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="flex justify-end">
                <button type="submit" 
                        class="hidden md:flex px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl items-center transition-colors duration-300 shadow-lg hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Crear Tarea
                </button>
            </div>
        </form>
    </div>

<!-- Listado de Tareas Activas -->
    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-8 rounded-2xl shadow-2xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center mb-6">
                <svg class="w-6 h-6 mr-2 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                Tareas Activas
            </h2>
            
            <!-- Filtros y Buscador -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <!-- Botón Limpiar Filtros -->
                <div class="md:col-span-1">
                    <button wire:click="clearFilters" 
                            class="w-full px-4 py-2.5 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 transition-colors flex items-center justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Limpiar
                    </button>
                </div>
                <!-- Buscador Global -->
                <div class="md:col-span-2">
                    <div class="relative">
                        <input type="text" 
                               wire:model.live.debounce.300ms="globalSearch"
                               placeholder="Buscar en tareas..."
                               class="w-full pl-10 pr-8 py-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
                <!-- Filtro por Estado -->
                <div class="relative">
                    <select wire:model.live="statusFilter" 
                            class="w-full pl-10 pr-8 py-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="">Todos los estados</option>
                        @foreach(TaskStatus::cases() as $status)
                            <option value="{{ $status->value }}">{{ $status->name() }}</option>
                        @endforeach
                    </select>
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                </div>

                <!-- Filtro por Fecha -->
                <div class="relative">
                    <input type="date" 
                        wire:model.live="searchDeadline"
                        class="w-full pl-10 pr-8 py-2.5 bg-white dark:bg-gray-800 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <svg class="w-5 h-5 absolute left-3 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Listado de Tareas -->
        <div class="space-y-4">
            @forelse ($tasks as $task)
            <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-4 mb-4">
                    <div class="flex-1 min-w-0">
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white truncate">{{ $task->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $task->description }}</p>
                    </div>
                    
                    <div class="flex-shrink-0 flex gap-2">
                        @if(($task->status === TaskStatus::PENDING || $task->status === TaskStatus::REJECTED) && $task->users->contains(auth()->id()))
                            <button wire:click="prepareSubmit({{ $task->id }})" 
                                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center transition-transform transform hover:scale-105">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                <span class="hidden md:inline ml-2">Enviar</span>
                            </button>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm border-t pt-4 border-gray-200 dark:border-gray-700">
 <!-- Asignado a Cell - Nuevo diseño con avatares apilados -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <!-- Contenedor de avatares -->
                            <div class="flex -space-x-2 relative group">
                                @foreach($task->users as $user)
                                <div class="relative hover:-translate-y-2 transition-transform duration-200">
                                    <img 
                                        class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800 shadow-sm cursor-pointer z-{{ $loop->remaining + 1 }}"
                                        src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                        alt="{{ $user->name }}"
                                        x-data="{ showTooltip: false }"
                                        @mouseover="showTooltip = true"
                                        @mouseleave="showTooltip = false"
                                    >
                                    <!-- Tooltip flotante -->
                                    <div 
                                        class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-900 text-white px-2 py-1 rounded-lg text-xs whitespace-nowrap"
                                        x-show="showTooltip"
                                        x-cloak
                                    >
                                        <div class="font-medium">{{ $user->name }}</div>
                                        <div class="text-gray-300">{{ $user->email }}</div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            
                            <!-- Menú desplegable "Ver todos" -->
                            <div class="ml-3 relative" x-data="{ open: false }">
                                <button 
                                    @click="open = !open"
                                    class="text-blue-500 hover:text-blue-700 text-sm font-medium flex items-center"
                                >
                                    <span class="mr-1">Ver todos</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <!-- Lista desplegable -->
                                <div 
                                    x-show="open"
                                    @click.away="open = false"
                                    class="absolute z-10 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700"
                                    x-cloak
                                >
                                    <div class="p-2 max-h-60 overflow-y-auto">
                                        @foreach($task->users as $user)
                                        <div class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
                                            <img 
                                                class="h-6 w-6 rounded-full mr-2" 
                                                src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                                alt="{{ $user->name }}"
                                            >
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>

         

                    <!-- Estado -->
                    <div>
                        <p class="font-medium text-gray-700 dark:text-gray-300 mb-2">Estado:</p>
                        <span class="px-2.5 py-1.5 text-xs font-semibold rounded-full 
                            @if($task->status === TaskStatus::PENDING)
                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                            @elseif($task->status === TaskStatus::SUBMITTED)
                                bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                            @elseif($task->status === TaskStatus::REJECTED)
                                bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                            @endif">
                            {{ $task->status->name() }}
                        </span>
                    </div>


                    <!-- Fecha Límite -->
                    <div>
                        <p class="font-medium text-gray-700 dark:text-gray-300 mb-2">Fecha Límite:</p>
                        <div class="flex items-center space-x-2">
                            <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-gray-600 dark:text-gray-400">
                                {{ $task->deadline->isoFormat('D MMM Y') }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Observación -->
                @if($task->observation)
                <div class="mt-4 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Observación:</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $task->observation }}</p>
                </div>
                @endif
            </div>
            @empty
            <div class="text-center p-6 text-gray-500 dark:text-gray-400 italic">
                No se encontraron tareas coincidentes
            </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($tasks->hasPages())
            <div class="mt-6">
                {{ $tasks->links('vendor.pagination.tailwind') }}
            </div>
        @endif


            <!-- Modal -->
        <div x-data="{ showSubmissionModal: @entangle('showSubmissionModal') }" x-cloak>
            <div x-show="showSubmissionModal" class="fixed z-50 inset-0 bg-black/30 backdrop-blur-sm flex items-center justify-center p-4">
                <div class="bg-white dark:bg-gray-800 rounded-2xl w-full max-w-lg p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800 dark:text-white">Confirmar Envío</h3>
                        <button @click="showSubmissionModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            ✕
                        </button>
                    </div>

                    <div class="space-y-4">
                        <p class="text-gray-600 dark:text-gray-400">
                            Estás a punto de enviar la tarea "<span class="font-semibold">{{ $selectedTask?->title }}</span>" para revisión.
                        </p>

                        <div class="space-y-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Observaciones</label>
                            <textarea wire:model="observation" rows="3"
                                      class="w-full px-4 py-2 bg-white dark:bg-gray-700 rounded-xl border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                      placeholder="Agregar comentarios opcionales..."></textarea>
                        </div>

                        <div class="flex justify-end space-x-3 mt-6">
                            <button @click="showSubmissionModal = false" 
                                    class="px-4 py-2 text-gray-600 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-xl transition-colors">
                                Cancelar
                            </button>
                            <button wire:click="submitTask" 
                                    class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl flex items-center space-x-2 transition-all">
                                <svg wire:loading.remove wire:target="submitTask" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <svg wire:loading wire:target="submitTask" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
                                </svg>
                                <span>Confirmar Envío</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    Livewire.on('notify', (data) => {
        Toastify({
            text: data.message,
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            backgroundColor: data.type === 'success' ? '#10B981' : '#EF4444',
            className: "shadow-lg rounded-xl",
            stopOnFocus: true,
        }).showToast();
    });
</script>
@endpush