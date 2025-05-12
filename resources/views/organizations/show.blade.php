@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-2xl font-bold mb-6">{{ $organization->name }}</h1>

        <!-- Sección de Chats -->
        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Chats</h2>

            <!-- Chat Grupal Principal -->
            <div class="mb-4 p-4 border rounded bg-blue-50">
                <a href="{{ route('chat.show', $organization->chats->first()) }}">
                    {{ $organization->chats->first()?->name ?? 'Chat principal' }}
                </a>
            </div>

            <!-- Proyectos y sus Chats -->
            @foreach($organization->projects as $project)
                <div class="mb-4 p-4 border rounded bg-green-50">
                    <a href="{{ route('project.show', $project) }}">
                        {{ $project->name }}
                    </a>
                    @if($project->chats->isNotEmpty())
                        <ul class="ml-4 mt-2">
                            @foreach($project->chats as $chat)
                                <li>
                                    <a href="{{ route('chat.show', $chat) }}" class="text-blue-600 underline">
                                        {{ $chat->name }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>

        <!-- Sección de Usuarios -->
        <div>
            <h2 class="text-xl font-semibold mb-4">Miembros</h2>
            <ul>
                @foreach($organization->users as $user)
                    <li>{{ $user->name }} ({{ $user->pivot->role }})</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection