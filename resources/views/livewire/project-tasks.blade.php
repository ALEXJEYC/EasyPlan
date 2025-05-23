<div>
    <!-- Formulario para Crear Tareas -->
    <div class="mb-8 bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Crear Nueva Tarea</h2>
        <form wire:submit.prevent="createTask">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <input type="text" wire:model="title" placeholder="Título" class="w-full p-2 border rounded">
                    @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div>
                    <input type="date" wire:model="deadline" class="w-full p-2 border rounded">
                    @error('deadline') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <textarea wire:model="description" placeholder="Descripción" class="w-full p-2 border rounded"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium">Asignar a:</label>
                    <select wire:model="assignedTo" multiple class="w-full p-2 border rounded">
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assignedTo') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
            </div>
            <button type="submit" class="mt-4 px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                Crear Tarea
            </button>
            
            <!-- Mensajes de éxito/error -->
            @if (session()->has('message'))
                <div class="mt-4 p-4 bg-green-100 text-green-700 rounded">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="mt-4 p-4 bg-red-100 text-red-700 rounded">
                    {{ session('error') }}
                </div>
            @endif
        </form>
    </div>

    <!-- Listado de Tareas -->
    <div class="bg-white dark:bg-gray-800 p-6 rounded-lg shadow">
        <h2 class="text-xl font-semibold mb-4">Tareas Activas</h2>
        <table class="w-full">
            <thead>
                <tr class="border-b">
                    <th class="text-left py-2"></th>
                    <th class="text-left py-2">Título</th>
                    <th class="text-left py-2">Asignado a</th>
                    <th class="text-left py-2">Fecha Límite</th>
                    <th class="text-left py-2">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    @foreach ($task->users as $user)
                        <tr class="border-b">
                            <td class="py-2">
                                @if ($user->pivot->user_id == Auth::id())
                                    <input type="checkbox" 
                                           wire:change="toggleTask({{ $user->pivot->id }})"
                                           class="form-checkbox">
                                @endif
                            </td>
                            <td class="py-2">{{ $task->title }}</td>
                            <td class="py-2">{{ $user->name }}</td>
                            <td class="py-2">{{ $task->deadline }}</td>
                            <td class="py-2">
                                <span class="px-2 py-1 bg-gray-200 rounded-full text-sm">
                                    {{ $user->pivot->status }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>

        <!-- Botón para Enviar Solicitud -->
        @if (count($selectedTasks) > 0)
            <button wire:click="submitSelectedTasks" 
                    class="mt-4 px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition-colors">
                <div wire:loading.remove wire:target="submitSelectedTasks">
                    Enviar Solicitud ({{ count($selectedTasks) }})
                </div>
                <div wire:loading wire:target="submitSelectedTasks">
                    Enviando...
                </div>
            </button>
        @endif
    </div>
    @push('scripts')
    <script>
        Livewire.on('show-toast', (data) => {
            Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: data.type === 'success' ? '#10B981' : '#EF4444',
            }).showToast();
        });
    </script>
    @endpush
</div>
