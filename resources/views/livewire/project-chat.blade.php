<div>
    @if ($chat)
        <div class="bg-white dark:bg-gray-800 p-6 rounded shadow">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-gray-200">Chat del Proyecto</h2>
            <a href="{{ route('chat.show', $chat) }}" class="text-blue-600 underline">Ir al chat del proyecto</a>
        </div>
    @else
        <p class="text-gray-500 dark:text-gray-300">Este proyecto no tiene un chat asociado.</p>
    @endif
</div>
