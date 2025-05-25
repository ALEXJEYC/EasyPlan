<div class="space-y-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        @foreach($statusMetrics as $status => $count)
        <div class="bg-gray-100 dark:bg-gray-800 p-4 rounded-lg shadow-md transition-shadow duration-300 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                        {{ \App\Enums\TaskStatus::tryFrom($status)?->name }}
                    </p>
                    <p class="text-3xl font-semibold text-gray-900 dark:text-white">{{ $count }}</p>
                </div>
                @livewire('task-status-badge', ['status' => $status])
            </div>
        </div>
        @endforeach
    </div>

    <!-- Panel de Revisión -->
<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg transition-all duration-300">
        <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <!-- Columnas... (se mantienen igual) -->
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($tasks as $taskUser )
                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $taskUser ->task->title }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full" 
                                         src="{{ $taskUser->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($taskUser->user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                         alt="{{ $taskUser->user->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $taskUser->user->name }}</div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $taskUser->user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <div class="flex flex-wrap gap-2">
                                @foreach ($taskUser->evidences as $evidence)
                                <a href="{{ asset($evidence->file_path) }}" target="_blank"
                                   class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200 hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors">
                                    <!-- Icono... -->
                                    {{ $evidence->file_name }}
                                </a>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300">
                            @if($taskUser->observation)
                            <!-- Popup de observación... (usar $taskUser->observation) -->
                            @else
                            <span class="text-gray-400 dark:text-gray-500 italic">Sin observaciones</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                            <div class="flex space-x-2">
                                <button wire:click="approveTask({{ $taskUser->id }})"
                                        class="...">
                                    <!-- Icono... -->
                                    Aprobar
                                </button>
                                <button wire:click="rejectTask({{ $taskUser->id }})"
                                        class="...">
                                    <!-- Icono... -->
                                    Rechazar
                                </button>
                                <button wire:click="viewDetails({{ $taskUser->id }})"
                                        class="...">
                                    <!-- Icono... -->
                                    Detalles
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Modal de Detalles -->
    <x-modal wire:model="showDetailsModal">
        <x-slot name="content">
            @if($selectedTask)
            <div class="space-y-4">
                <!-- Detalles de la tarea -->
                <div>
                    <h3 class="text-lg font-medium">{{ $selectedTask->task->title }}</h3>
                    <p class="text-sm text-gray-500">{{ $selectedTask->task->description }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Usuario asignado -->
                    <div>
                        <h4 class="font-medium">Usuario Asignado</h4>
                        <div class="flex items-center mt-2">
                            <x-avatar :src="$selectedTask->user->profile_photo_url" 
                                    :alt="$selectedTask->user->name" 
                                    size="8" />
                            <div class="ml-3">
                                <p>{{ $selectedTask->user->name }}</p>
                                <p class="text-sm text-gray-500">{{ $selectedTask->user->email }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Estado -->
                    <div>
                        <h4 class="font-medium">Estado</h4>
                        @livewire('task-status-badge', ['status' => $selectedTask->status])
                    </div>
                </div>

                <!-- Historial de revisiones -->
                <div>
                    <h4 class="font-medium">Historial de Revisiones</h4>
                    <div class="mt-2 space-y-2">
                        @forelse($selectedTask->reviews as $review)
                        <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex justify-between">
                                <span class="font-medium">{{ $review->reviewer->name }}</span>
                                <span class="text-sm text-gray-500">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <p class="mt-1 text-sm">{{ $review->comments }}</p>
                        </div>
                        @empty
                        <p class="text-sm text-gray-500">No hay revisiones registradas</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif
        </x-slot>
    </x-modal>
</div>