<div class="space-y-6">
    <!-- Tarjetas de Estado Mejoradas -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @php
            // Estados específicos a mostrar en el orden deseado
            $filteredStatuses = [
                \App\Enums\TaskStatus::PENDING,
                \App\Enums\TaskStatus::SUBMITTED,
                \App\Enums\TaskStatus::APPROVED,
                \App\Enums\TaskStatus::REJECTED
            ];
            
            $total = array_sum($statusMetrics);
        @endphp
        
        @foreach($filteredStatuses as $status)
        @php
            $count = $statusMetrics[$status->value] ?? 0;
            $percentage = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        @endphp
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 dark:from-gray-900 dark:to-gray-950 p-4 rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300 group">
            <div class="flex items-center justify-between">
                <div class="space-y-2">
                    <div class="flex items-center gap-2">
                        @switch($status->value)
                            @case(\App\Enums\TaskStatus::PENDING->value)
                                <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @break
                            @case(\App\Enums\TaskStatus::SUBMITTED->value)
                                <svg class="w-6 h-6 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            @break
                            @case(\App\Enums\TaskStatus::APPROVED->value)
                                <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            @break
                            @case(\App\Enums\TaskStatus::REJECTED->value)
                                <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            @break
                        @endswitch
                        <p class="text-sm font-semibold text-gray-300 uppercase tracking-wider">
                            @if($status->value === \App\Enums\TaskStatus::SUBMITTED->value)
                                Enviado para Revisión
                            @else
                                {{ $status->name() }}
                            @endif
                        </p>
                    </div>
                    <p class="text-4xl font-bold text-white">{{ $count }}</p>
                </div>
                <div class="opacity-70 group-hover:opacity-100 transition-opacity">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full 
                        @switch($status->value)
                            @case(\App\Enums\TaskStatus::SUBMITTED->value) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                            @case(\App\Enums\TaskStatus::APPROVED->value) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                            @case(\App\Enums\TaskStatus::REJECTED->value) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                            @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                        @endswitch">
                        {{ $percentage }}%
                    </span>
                </div>
            </div>
            <div class="mt-3 h-1 bg-gray-700 rounded-full">
                <div class="h-full rounded-full transition-all duration-500
                    @switch($status->value)
                        @case(\App\Enums\TaskStatus::SUBMITTED->value) bg-blue-500 @break
                        @case(\App\Enums\TaskStatus::APPROVED->value) bg-green-500 @break
                        @case(\App\Enums\TaskStatus::REJECTED->value) bg-red-500 @break
                        @default bg-gray-500
                    @endswitch" 
                     style="width: {{ $percentage }}%"></div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Panel de Revisión Mejorado -->
    <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-6 rounded-2xl shadow-2xl transition-all duration-300 hover:shadow-3xl">
        <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                    <tr>
                        <!-- Añadir anchos mínimos a las columnas -->
                        <th class="px-6 py-4 text-left min-w-[300px] text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Tarea</th>
                        <th class="px-6 py-4 text-left min-w-[200px] text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Asignados</th>
                        <th class="px-6 py-4 text-left min-w-[180px] text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Enviado por</th>
                        <th class="px-6 py-4 text-left min-w-[250px] text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Evidencias</th>
                        <th class="px-6 py-4 text-left min-w-[220px] text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($tasks as $task)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-200">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                               <div class="ml-4 space-y-1.5">
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $task->title }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($task->description, 40) }}</div>
                                </div>
                            </div>
                        </td>
               <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                            <!-- Contenedor de avatares -->
                            <div class="flex -space-x-2 relative group">
                                @foreach($task->users as $user)
                                   <div class="relative hover:-translate-y-1 transition-transform duration-200">
                                        <img 
                                            class="h-7 w-7 rounded-full border-2 border-white dark:border-gray-800 shadow-sm cursor-pointer"
                                            src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                            alt="{{ $user->name }}"
                                            x-tooltip="'{{ $user->name }}<br>{{ $user->email }}'"
                                        >
                                    </div>
                                    @endforeach
                                </div>
                            
                            <!-- Menú desplegable "Ver todos" -->
                                <div class="relative" x-data="{ open: false }">
                                    <button @click="open = !open" class="text-blue-500 hover:text-blue-700 text-sm flex items-center gap-1">
                                        <span>Ver todos</span>
                                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                
                                <!-- Lista desplegable -->
                             <div x-show="open" @click.away="open = false" class="absolute z-10 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700" x-cloak>
                                        <div class="p-2 max-h-60 overflow-y-auto space-y-2">
                                            @foreach($task->users as $user)
                                            <div class="flex items-center p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg gap-2">
                                                <img 
                                                    class="h-6 w-6 rounded-full" 
                                                    src="{{ $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                                    alt="{{ $user->name }}"
                                                >
                                                <div class="truncate">
                                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $user->name }}</div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $user->email }}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                @if($task->submittedBy)
                                <img class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800 shadow-sm flex-shrink-0" 
                                    src="{{ $task->submittedBy->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->submittedBy->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                    alt="{{ $task->submittedBy->name }}">
                                <div class="min-w-0"> 
                                    <div class="text-sm font-medium text-gray-900 dark:text-white truncate">{{ $task->submittedBy->name }}</div>
                                    @if($submittedAt = $task->submittedAtForUser(auth()->id()))
                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate">
                                        {{ \Carbon\Carbon::parse($submittedAt)->diffForHumans() }}
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-wrap gap-2 items-center"> 
                                @foreach ($task->evidences as $evidence)
                                <a href="{{ asset($evidence->file_path) }}" target="_blank"
                                   class="inline-flex items-center px-3 py-1 rounded-lg text-xs font-medium bg-blue-50 dark:bg-blue-900 text-blue-600 dark:text-blue-200 hover:bg-blue-100 dark:hover:bg-blue-800 transition-all duration-200 gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="truncate max-w-[100px]">{{ $evidence->file_name }}</span>
                                </a>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex space-x-2">
                                <!-- Botones más compactos -->
                                <button wire:click="approveTask({{ $task->id }})"
                                        class="flex items-center px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white rounded-lg gap-1.5 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    <span class="text-sm">Aprobar</span>
                                </button>
                                <button wire:click="rejectTask({{ $task->id }})"
                                        class="flex items-center px-3 py-1.5 bg-red-500 hover:bg-red-600 text-white rounded-lg gap-1.5 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    <span class="text-sm">Rechazar</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal de Detalles Mejorado -->
    <x-modal wire:model="showDetailsModal" maxWidth="4xl">
        <x-slot name="content">
            @if($selectedTask)
            <div class="space-y-6 p-6 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 rounded-xl">
                <div class="flex justify-between items-center border-b border-gray-200 dark:border-gray-700 pb-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $selectedTask->title }}</h2>
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-1.5 text-xs font-semibold rounded-full 
                            @switch($selectedTask->status->value)
                                @case(\App\Enums\TaskStatus::SUBMITTED->value) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 @break
                                @case(\App\Enums\TaskStatus::APPROVED->value) bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 @break
                                @case(\App\Enums\TaskStatus::REJECTED->value) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @break
                                @default bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                            @endswitch">
                            {{ $selectedTask->status->name() }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Detalles</h3>
                            <dl class="grid grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Creada por</dt>
                                    <dd class="text-gray-900 dark:text-white">{{ $selectedTask->createdBy->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Fecha límite</dt>
                                    <dd class="text-gray-900 dark:text-white">{{ $selectedTask->deadline->format('d M Y') }}</dd>
                                </div>
                                <div class="col-span-2">
                                    <dt class="text-sm text-gray-500 dark:text-gray-400">Descripción</dt>
                                    <dd class="text-gray-900 dark:text-white">{{ $selectedTask->description }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-semibold mb-2 text-gray-800 dark:text-gray-200">Evidencias</h3>
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($selectedTask->evidences as $evidence)
                                <a href="{{ asset($evidence->file_path) }}" target="_blank"
                                   class="flex items-center p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 hover:shadow-md transition-shadow">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="text-sm truncate">{{ $evidence->file_name }}</span>
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-200">Historial</h3>
                            <div class="relative pl-6 border-l-2 border-gray-200 dark:border-gray-700">
                                @forelse($selectedTask->reviews as $review)
                                <div class="mb-6">
                                    <div class="absolute w-3 h-3 bg-blue-500 rounded-full -left-[7px] border-2 border-white dark:border-gray-900"></div>
                                    <time class="block text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">
                                        {{ $review->created_at->format('d M Y, H:i') }}
                                    </time>
                                    <div class="text-gray-900 dark:text-white">
                                        {{ $review->reviewer->name }}
                                        <span class="text-sm text-gray-500 dark:text-gray-400">({{ $review->comments }})</span>
                                    </div>
                                </div>
                                @empty
                                <div class="text-gray-500 dark:text-gray-400 italic">No hay registro de actividades</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </x-slot>
    </x-modal>
</div>