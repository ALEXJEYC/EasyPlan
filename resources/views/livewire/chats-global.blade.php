<div>
    <h2 class="text-xl font-semibold mb-4">Chats</h2>

    @if ($chats->isEmpty())
        <p class="text-gray-500 dark:bg-gray-800">No hay chats disponibles para esta organización.</p>
    @else
        <!-- Chat Grupal Principal -->
        <div class="mb-4 p-4 border rounded bg-blue-50 dark:bg-gray-800 ">
            <h3 class="text-lg font-bold text-gray-800 dark:text-white ">Chat Grupal Organización</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400">Este es el chat principal de la organización.</p>
            <a href="{{ route('chat.show', $chats->first()) }}" class="text-blue-600 underline">Ir al chat grupal</a>
        </div>

        <!-- Otros Chats -->
        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-6">Chats Proyectos</h3>
        <ul class="space-y-4 mt-4">
            @foreach ($chats as $chat)
                @if ($chat->type === 'project')
                    <li class="p-4 bg-white dark:bg-gray-800 rounded shadow">
                        <div class="flex items-center justify-between">
                            <div>
                                <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $chat->name }}</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $chat->description ?? 'Sin descripción disponible.' }}</p>
                            </div>
                            <a href="{{ route('chat.show', $chat) }}" class="text-blue-600 underline">Abrir Chat</a>
                        </div>
                    </li>
                @endif
            @endforeach
        </ul>
    @endif
</div>
