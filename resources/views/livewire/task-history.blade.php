<div>
    <h2 class="text-xl font-semibold mb-4">Historial de Tareas Completadas</h2>
    <table class="w-full">
        <thead>
            <tr class="border-b">
                <th class="text-left py-2">Título</th>
                <th class="text-left py-2">Usuario</th>
                <th class="text-left py-2">Fecha de Aprobación</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="border-b">
                    <td class="py-2">{{ $task->task->title }}</td>
                    <td class="py-2">{{ $task->user->name }}</td>
                    <td class="py-2">{{ $task->updated_at->format('d/m/Y') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>