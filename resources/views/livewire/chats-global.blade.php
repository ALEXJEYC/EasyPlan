<div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 transition-all duration-300">
    <!-- Encabezado con icono y modo oscuro integrado -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center">
            <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h2 class="text-2xl font-bold text-gray-800 dark:text-white">Conversaciones</h2>
        </div>
        <div class="flex items-center space-x-3">
            <div class="relative">
                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>
        </div>
    </div>

    @if ($chats->isEmpty())
        <!-- Estado vacío con icono -->
        <div class="text-center py-10">
            <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
            </svg>
            <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No hay chats disponibles</h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Actualmente no hay chats para esta organización.</p>
        </div>
    @else
        <!-- Chat Grupal Principal con diseño destacado -->
        <div class="mb-8">
            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-3 flex items-center">
                <svg class="w-4 h-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Chat principal
            </h3>
            <div class="bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900/30 dark:to-indigo-800/30 rounded-xl border border-indigo-200 dark:border-indigo-900 p-5 shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0 mr-4">
                        <div class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-200 w-12 h-12 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between">
                            <h4 class="text-lg font-bold text-gray-800 dark:text-white">Organización</h4>
                            <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 text-xs font-medium px-2.5 py-0.5 rounded-full">Principal</span>
                        </div>
                        <p class="text-gray-600 dark:text-gray-300 mt-1">Este es el chat principal de la organización donde participan todos los miembros.</p>
                        <div class="mt-4">
                            <a href="{{ route('chat.show', $chats->first()) }}" class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-indigo-300 font-medium">
                                Ir al chat grupal
                                <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chats de Proyectos con diseño de lista moderna -->
        <div>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider flex items-center">
                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                    Chats de Proyectos
                </h3>
                <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 text-xs font-medium px-2.5 py-0.5 rounded-full">
                    {{ $chats->where('type', 'project')->count() }} chats
                </span>
            </div>

            <ul class="space-y-4">
                @foreach ($chats as $chat)
                    @if ($chat->type === 'project')
                        <li class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-start">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-200 w-10 h-10 rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex justify-between items-start">
                                        <h4 class="font-bold text-gray-800 dark:text-white truncate">{{ $chat->name }}</h4>
                                        <!-- Indicador de actividad (opcional) -->
                                        <!-- <span class="bg-green-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                                            3
                                        </span> -->
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-300 mt-1 text-sm truncate">
                                        {{ $chat->description ?? 'Sin descripción disponible' }}
                                    </p>
                                    <div class="mt-3 flex justify-between items-center">
                                        <!-- Participantes (opcional) -->
                                        <!-- <div class="flex -space-x-2">
                                            <img class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-800" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Participante">
                                            <img class="w-6 h-6 rounded-full border-2 border-white dark:border-gray-800" src="https://randomuser.me/api/portraits/women/44.jpg" alt="Participante">
                                        </div> -->
                                        <a href="{{ route('chat.show', $chat) }}" class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300">
                                            Abrir chat
                                            <svg class="w-4 h-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
    @endif
</div>