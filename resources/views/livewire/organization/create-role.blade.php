<div class="space-y-10">

    {{-- Crear nuevo rol --}}
    <div class="p-6 bg-white shadow rounded">
        <h2 class="text-lg font-semibold mb-4">Crear nuevo rol</h2>

        @if (session()->has('message'))
            <div class="text-green-600 mb-2">{{ session('message') }}</div>
        @endif

        <form wire:submit.prevent="createRole">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Nombre del Rol</label>
                <input type="text" wire:model.defer="newRoleName" class="mt-1 block w-full rounded border-gray-300 shadow-sm" />
                @error('newRoleName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Permisos</label>
                <div class="grid grid-cols-2 gap-2 mt-2">
                    @foreach ($allPermissions as $permission)
                        <label class="flex items-center">
                            <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}" class="mr-2">
                            {{ $permission->name }}
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Crear Rol</button>
        </form>
    </div>

    {{-- Asignar miembro --}}
    <div class="p-6 bg-white shadow rounded">
        <h2 class="text-lg font-semibold mb-4">Agregar miembro a la organización</h2>

        <form wire:submit.prevent="addMember">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Usuario</label>
                <select wire:model="user_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    <option value="">Seleccionar usuario</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700">Rol</label>
                <select wire:model="custom_role_id" class="mt-1 block w-full rounded border-gray-300 shadow-sm">
                    <option value="">Sin rol</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('custom_role_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Agregar miembro</button>
        </form>
    </div>
    {{-- Miembros actuales --}}
    <div>
        <h3 class="text-lg font-semibold mb-4">Miembros actuales</h3>
        <div class="space-y-4">

            @forelse ($memberships as $membership)
                <div 
                    class="p-4 rounded shadow flex justify-between items-center 
                    @if($membership->user->id === $ownerId) bg-black text-white @else bg-white text-gray-900 @endif"
                >
            
                        {{-- Crear nuevo rol --}}


                    <div>
                        <p class="font-semibold">{{ $membership->user->name }}</p>
                        <p class="text-sm">Rol: {{ $membership->customRole->name ?? 'Sin rol' }}</p>
                        @if($membership->user->id === $ownerId)
                            <p class="text-xs italic mt-1">Dueño de la organización</p>
                        @endif
                    </div>
                     @if ($canManageMembers)
                    <div class="space-x-2">
                        <button 
                            wire:click="editMember({{ $membership->id }})"
                            class="bg-yellow-400 hover:bg-yellow-500 text-black px-3 py-1 rounded"
                        >Editar</button>
                        <button 
                            wire:click="deleteMember({{ $membership->id }})"
                            class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded"
                            onclick="confirm('¿Estás seguro de eliminar este miembro?') || event.stopImmediatePropagation()"
                        >Eliminar</button>

                    </div>
                      @endif
                </div>
            @empty
                <p>No hay miembros aún.</p>
            @endforelse
        </div>
    </div>
</div>
