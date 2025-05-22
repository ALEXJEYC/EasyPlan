<x-app-layout>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8" x-data="{ tab: 'description' }" x-cloak>
        <h1 class="text-2xl font-bold mb-6">{{ $organization->name }}</h1>

        <!-- Navegación de pestañas -->
        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
            <ul class="flex flex-wrap -mb-px text-sm font-medium text-center">
                <li class="mr-2">
                    <button @click="tab = 'description'" :class="{ 'border-blue-600 text-blue-600': tab === 'description' }" class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">Descripción</button>
                </li>
                <li class="mr-2">
                    <button @click="tab = 'chats'" :class="{ 'border-blue-600 text-blue-600': tab === 'chats' }" class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">Chats</button>
                </li>
                <li class="mr-2">
                    <button @click="tab = 'projects'" :class="{ 'border-blue-600 text-blue-600': tab === 'projects' }" class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">Proyectos</button>
                </li>
                <li class="mr-2">
                    <button @click="tab = 'members'" :class="{ 'border-blue-600 text-blue-600': tab === 'members' }" class="inline-block p-4 border-b-2 border-transparent hover:border-blue-600 hover:text-blue-600">Miembros</button>
                </li>
            </ul>
        </div>

        <!-- Pestaña: Descripción -->
        <div x-show="tab === 'description'" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Visión de la organización</h2>
            <p class="text-gray-600 dark:text-gray-400">Aquí puedes agregar una descripción o visión general de la organización.</p>
        </div>

        <!-- Pestaña: Chats -->
        <div x-show="tab === 'chats'" class="mb-8">
            @livewire('chats-global', ['organization' => $organization])
        </div>


        <!-- Pestaña: Proyectos -->
        <div x-show="tab === 'projects'" class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Proyectos</h2>
               
            <livewire:button-project :organization="$organization" />
        
            <livewire:project-list :organization="$organization" />

            <!-- Proyectos archivados -->
            <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mt-6">Proyectos Archivados</h3>
            <ul class="space-y-4 mt-4">
                @foreach ($organization->projects->where('status', 'archived') as $project)
                    <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded shadow">
                        <h4 class="text-lg font-bold text-gray-800 dark:text-gray-200">{{ $project->name }}</h4>
                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->description }}</p>
                        <img src="{{ $project->getFirstMediaUrl('images') }}" alt="Imagen del Proyecto" class="mt-4 w-full h-auto">
                        <div class="mt-4 flex justify-between items-center">
                            <a href="{{ route('projects.show', $project) }}" class="text-blue-600 underline">Ver Proyecto</a>
                            <form action="{{ route('projects.unarchive', $project) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="text-green-600 underline">Desarchivar</button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <!-- Pestaña: Miembros -->
        <div x-show="tab === 'members'" class="mb-8">
        @livewire('organization.create-role', [
            'organization' => $organization,
        ], key($organization->id))
        </div>
</x-app-layout>