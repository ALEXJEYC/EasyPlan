<div>
    <form wire:submit.prevent="createProject">
        <div class="mb-4">
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre del Proyecto</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700">Descripción</label>
                    <textarea id="description" wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                    @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-sm font-medium text-gray-700">Imagen</label>
                    <input type="file" id="image" wire:model="image" class="mt-1 block w-full">
                    @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700">Miembros</label>
                    <div class="mt-2 space-y-2">
                        @foreach ($organizationMembers as $member)
                            <div class="flex items-center">
                                <input type="checkbox" id="member-{{ $member->id }}" value="{{ $member->id }}" wire:model="members" class="h-4 w-4 text-blue-600 border-gray-300 rounded">
                                <label for="member-{{ $member->id }}" class="ml-2 text-sm text-gray-700">{{ $member->name }}</label>
                            </div>
                        @endforeach
                    </div>
                    @error('members') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Crear Proyecto</button>
                </div>
            </form>
        </div>
    </div>
</div>