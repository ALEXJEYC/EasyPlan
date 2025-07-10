<x-app-layout>
    <div class="container mx-auto px-4 py-8">
        <!-- Encabezado con icono y título -->
        <div class="flex justify-between items-center mb-8">
            <div class="flex items-center">
                <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Mis Conversaciones</h1>
            </div>
            <div class="flex items-center space-x-4">
                <!-- Botón de búsqueda (opcional) -->
                <button class="p-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </div>

        @if ($chats->isEmpty())
            <!-- Estado vacío con icono -->
            <div class="text-center py-16">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                </svg>
                <h3 class="mt-6 text-lg font-medium text-gray-900 dark:text-white">No tienes chats activos</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Inicia una conversación con tus colegas o únete a un chat grupal.</p>
                <div class="mt-6">
                    <button class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">
                        Crear nuevo chat
                    </button>
                </div>
            </div>
        @else
            <!-- Sección de Chats Directos -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                        Chats personales
                    </h2>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $chats->where('type', 'direct')->count() }} chats
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach ($chats as $chat)
                        @if ($chat->type === 'direct')
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                <div class="flex items-start">
                                    <!-- Avatar del otro usuario -->
                                    <div class="flex-shrink-0 mr-4">
                                        @foreach($chat->users as $user)
                                            @if($user->id !== auth()->id())
                                                <div class="relative">
                                                    <div class="w-12 h-12 rounded-full bg-purple-100 dark:bg-purple-900/50 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                        </svg>
                                                    </div>
                                                    <!-- Indicador de estado en línea (opcional) -->
                                                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 rounded-full border-2 border-white dark:border-gray-800"></div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <h3 class="font-bold text-gray-800 dark:text-white truncate">
                                                @foreach($chat->users as $user)
                                                    @if($user->id !== auth()->id())
                                                        {{ $user->name }}
                                                    @endif
                                                @endforeach
                                            </h3>
                                            @if($chat->lastMessage)
                                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $chat->lastMessage->created_at->diffForHumans() }}
                                                </span>
                                            @endif
                                        </div>
                                        @if($chat->lastMessage)
                                            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm truncate">
                                                {{ $chat->lastMessage->content }}
                                            </p>
                                        @else
                                            <p class="text-gray-400 dark:text-gray-500 mt-1 text-sm italic">No hay mensajes aún</p>
                                        @endif
                                        <div class="mt-4">
                                            <a href="{{ route('chat.show', $chat) }}" class="inline-flex items-center text-sm text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300 font-medium">
                                                Abrir conversación
                                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>

            <!-- Sección de Chats Grupales -->
            <div class="mb-10">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        Chats grupales
                    </h2>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $chats->where('type', 'group')->count() }} chats
                    </span>
                </div>

                @if ($chats->where('type', 'group')->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No tienes chats grupales</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Únete a un grupo o crea uno nuevo para empezar a chatear.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($chats as $chat)
                            @if ($chat->type === 'group')
                                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-12 h-12 rounded-lg bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start">
                                                <h3 class="font-bold text-gray-800 dark:text-white truncate">{{ $chat->name ?? 'Chat grupal' }}</h3>
                                                @if($chat->lastMessage)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $chat->lastMessage->created_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm">
                                                {{ $chat->users->count() }} participantes
                                            </p>
                                            @if($chat->lastMessage)
                                                <p class="text-gray-600 dark:text-gray-300 mt-2 text-sm truncate">
                                                    <span class="font-medium">
                                                        {{ $chat->lastMessage->user->name }}:
                                                    </span>
                                                    {{ $chat->lastMessage->content }}
                                                </p>
                                            @endif
                                            <div class="mt-4">
                                                <a href="{{ route('chat.show', $chat) }}" class="inline-flex items-center text-sm text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                                    Unirse al chat
                                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Sección de Chats de Proyectos -->
            <div>
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center">
                        <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Chats de proyectos
                    </h2>
                    <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                        {{ $chats->where('type', 'project')->count() }} chats
                    </span>
                </div>

                @if ($chats->where('type', 'project')->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No tienes chats de proyectos</h3>
                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Únete a un proyecto para acceder a sus chats.</p>
                    </div>
                @else
                    <div class="space-y-4">
                        @foreach ($chats as $chat)
                            @if ($chat->type === 'project')
                                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md transition-all duration-300">
                                    <div class="flex items-start">
                                        <div class="flex-shrink-0 mr-4">
                                            <div class="w-12 h-12 rounded-lg bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                                <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start">
                                                <div>
                                                    <h3 class="font-bold text-gray-800 dark:text-white">{{ $chat->project->name ?? 'Chat de proyecto' }}</h3>
                                                    <p class="text-gray-600 dark:text-gray-300 text-sm mt-1">
                                                        {{ $chat->users->count() }} miembros
                                                    </p>
                                                </div>
                                                @if($chat->lastMessage)
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $chat->lastMessage->created_at->diffForHumans() }}
                                                    </span>
                                                @endif
                                            </div>
                                            @if($chat->lastMessage)
                                                <p class="text-gray-600 dark:text-gray-300 mt-3 text-sm">
                                                    <span class="font-medium">{{ $chat->lastMessage->user->name }}:</span>
                                                    {{ Str::limit($chat->lastMessage->content, 100) }}
                                                </p>
                                            @endif
                                            <div class="mt-4 flex justify-between items-center">
                                                <div class="flex -space-x-2">
                                                    @foreach($chat->users->take(3) as $user)
                                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 border-2 border-white dark:border-gray-800 flex items-center justify-center">
                                                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                            </svg>
                                                        </div>
                                                    @endforeach
                                                    @if($chat->users->count() > 3)
                                                        <div class="w-8 h-8 rounded-full bg-gray-100 dark:bg-gray-600 border-2 border-white dark:border-gray-800 flex items-center justify-center text-xs text-gray-500 dark:text-gray-300">
                                                            +{{ $chat->users->count() - 3 }}
                                                        </div>
                                                    @endif
                                                </div>
                                                <a href="{{ route('chat.show', $chat) }}" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                                    Ver chat
                                                    <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>