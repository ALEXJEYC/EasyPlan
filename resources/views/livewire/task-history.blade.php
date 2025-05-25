<div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow-lg transition-all duration-300">
    <h2 class="text-xl font-semibold mb-4 text-gray-900 dark:text-white">Historial de Tareas Completadas</h2>
    <div class="overflow-x-auto">
        <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Usuario Responsable de Aprobacion</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tarea Asginada a</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Fecha de Aprobación</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @foreach ($tasks as $task)
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                        {{ $task->task->title }}
                    </td>
                    <td> 
                        @if ($task->latestReview && $task->latestReview->reviewer)
                            {{ $task->latestReview->reviewer->name }}
                        @else
                            <span class="italic text-gray-400">No disponible</span>
                        @endif
                    </td>

                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-10 w-10">
                                <img class="h-10 w-10 rounded-full" 
                                     src="{{ $task->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name='.urlencode($task->user->name).'&color=7F9CF5&background=EBF4FF' }}" 
                                     alt="{{ $task->user->name }}">
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $task->user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $task->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                        {{ $task->updated_at->format('d/m/Y H:i') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>