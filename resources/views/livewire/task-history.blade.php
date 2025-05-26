<div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 p-6 rounded-2xl shadow-2xl transition-all duration-300">
    <!-- Header Section -->
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
            <svg class="w-6 h-6 mr-2 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Historial de Tareas Completadas
        </h2>
        <button wire:click="clearFilters" 
                class="flex items-center px-4 py-2 bg-red-100 dark:bg-red-900 hover:bg-red-200 dark:hover:bg-red-800 text-red-600 dark:text-red-200 rounded-lg transition-colors duration-200">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Limpiar Filtros
        </button>
    </div>

    <!-- Search and Table Section -->
    <div class="overflow-x-auto rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900">
                <tr>
                    <!-- Título Column -->
                    <th class="px-6 py-4 text-left">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                <span>Título</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="searchTitle" 
                                           type="text" 
                                           class="w-full pl-10 pr-8 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Buscar título...">
                                    <svg class="w-5 h-5 absolute left-2 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                    @if($searchTitle)
                                    <button wire:click="$set('searchTitle', '')" 
                                            class="absolute right-2 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>

                    <!-- Aprobador Column -->
                    <th class="px-6 py-4 text-left">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                <span>Aprobador</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="searchApprover" 
                                           type="text" 
                                           class="w-full pl-10 pr-8 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Buscar aprobador...">
                                    <svg class="w-5 h-5 absolute left-2 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    @if($searchApprover)
                                    <button wire:click="$set('searchApprover', '')" 
                                            class="absolute right-2 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>

                    <!-- Asignado a Column -->
                    <th class="px-6 py-4 text-left">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                <span>Asignado a</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="searchAssignedUser" 
                                           type="text" 
                                           class="w-full pl-10 pr-8 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                           placeholder="Buscar usuario...">
                                    <svg class="w-5 h-5 absolute left-2 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    @if($searchAssignedUser)
                                    <button wire:click="$set('searchAssignedUser', '')" 
                                            class="absolute right-2 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>

                    <!-- Fecha Column -->
                    <th class="px-6 py-4 text-left">
                        <div class="flex flex-col space-y-2">
                            <div class="flex items-center space-x-2 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase">
                                <span>Fecha</span>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <div class="relative">
                                <div class="relative">
                                    <input wire:model.live.debounce.300ms="searchDate" 
                                           type="date" 
                                           class="w-full pl-10 pr-8 py-2 bg-white dark:bg-gray-800 rounded-lg border border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <svg class="w-5 h-5 absolute left-2 top-2.5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    @if($searchDate)
                                    <button wire:click="$set('searchDate', '')" 
                                            class="absolute right-2 top-2.5 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </th>
                </tr>
            </thead>

            <!-- Table Body -->
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($tasks as $task)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors duration-150">
                    <!-- Título Cell -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center group">
                            <svg class="w-5 h-5 mr-3 text-blue-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <span class="font-medium text-gray-900 dark:text-white group-hover:text-blue-600 transition-colors">
                                {{ $task->title }}
                            </span>
                        </div>
                    </td>

                    <!-- Aprobador Cell -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($task->latestReview && $task->latestReview->reviewer)
                        <div class="flex items-center">
                            <div class="relative shrink-0">
                                <img class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800 shadow-sm" 
                                     src="{{ $task->reviews->first()->reviewer->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->latestReview->reviewer->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                     alt="{{ $task->reviews->first()->reviewer->name }}">
                                <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-green-400 rounded-full border-2 border-white dark:border-gray-800"></div>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->latestReview->reviewer->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Aprobado</div>
                            </div>
                        </div>
                        @else
                        <span class="italic text-gray-400">-</span>
                        @endif
                    </td>

                    <!-- Asignado a Cell -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="relative shrink-0">
                                @foreach($task->taskUsers as $taskUser)
                                <img class="h-8 w-8 rounded-full border-2 border-white dark:border-gray-800 shadow-sm" 
                                     src="{{ $taskUser->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($taskUser->user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                     alt="{{ $taskUser->user->name }}">
                                       @endforeach
                                <div class="absolute bottom-0 right-0 h-2.5 w-2.5 bg-blue-400 rounded-full border-2 border-white dark:border-gray-800"></div>
                           
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $taskUser->user->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $taskUser->user->email }}</div>
                            </div>
                        </div>
                    </td>

                    <!-- Fecha Cell -->
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-purple-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm font-medium text-gray-900 dark:text-white">
                                {{ $task->updated_at->isoFormat('D MMM Y, HH:mm') }}
                            </span>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                        No se encontraron resultados
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $tasks->links('vendor.pagination.tailwind') }}
    </div>
</div>