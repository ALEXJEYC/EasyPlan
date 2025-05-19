<div class="space-y-6">
    <!-- Tarjeta de creación de roles -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <h2 class="text-xl font-semibold mb-4 text-gray-800">
            <i class="fas fa-user-tag mr-2 text-blue-500"></i> Crear nuevo rol
        </h2>
        
        @if (session()->has('message'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                {{ session('message') }}
            </div>
        @endif

        <form wire:submit.prevent="createRole" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nombre del Rol</label>
                <input type="text" wire:model="newRoleName" 
                       class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @error('newRoleName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Permisos</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach ($allPermissions as $permission)
                        <label class="inline-flex items-center space-x-2 bg-gray-50 px-3 py-2 rounded-lg hover:bg-gray-100">
                            <input type="checkbox" wire:model="selectedPermissions" 
                                   value="{{ $permission->id }}" 
                                   class="rounded text-blue-600 focus:ring-blue-500">
                            <span class="text-gray-700">{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-save mr-2"></i> Crear Rol
            </button>
        </form>
    </div>

    <!-- Tarjeta de gestión de miembros -->
    <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">
                <i class="fas fa-users mr-2 text-blue-500"></i> Miembros
            </h2>
            
            @if($this->canAddMembers)
                <button wire:click="openAddMemberModal" 
                        class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                    <i class="fas fa-user-plus mr-2"></i> Agregar Miembro
                </button>
            @endif
        </div>

        <!-- Owner -->
        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mb-4 flex justify-between items-center">
            <div>
                <p class="font-bold text-blue-800">{{ $organization->ownerRelation->user->name }}</p>
                <p class="text-sm text-blue-600">Dueño de la organización</p>
            </div>
            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">
                <i class="fas fa-crown mr-1"></i> Owner
            </span>
        </div>

        <!-- Lista de miembros -->
        <div class="space-y-3">
            @foreach($memberships as $membership)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 p-3 rounded-full">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800">{{ $membership->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $membership->user->email }}</p>
                        </div>
                    </div>
                    @if(!$organization->isOwner($membership->user))
                    <div class="flex items-center space-x-3">
                        <select wire:change="assignRoleToUser({{ $membership->id }}, $event.target.value)" 
                                class="border rounded px-3 py-1 focus:ring-2 focus:ring-blue-500"
                                @disabled(!$this->canAddMembers)>
                            <option value="">Sin rol</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" 
                                    {{ $membership->custom_role_id == $role->id ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @else
                            <span>Owner</span>
                        @endif
                        
                        @if($this->canRemoveMembers && $membership->user_id != $ownerId)
                            <button wire:click="confirmRemoveMember({{ $membership->id }})" 
                                    class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    
    
    
    <!-- Modal para agregar miembros -->
    @if($showAddMemberModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Agregar miembro</h3>
                
                <div class="mb-4">
                    <label class="form-label">Usuario</label>
                    <select wire:model="userToAdd" class="form-select">
                        <option value="">Seleccionar usuario</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('userToAdd') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                
                <div class="mb-4">
                    <label class="form-label">Rol (opcional)</label>
                    <select wire:model="roleToAssign" class="form-select">
                        <option value="">Sin rol</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showAddMemberModal', false)" class="btn btn-secondary">
                        Cancelar
                    </button>
                    <button wire:click="addMember" class="btn btn-primary">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    @endif

    <!-- Confirmación para eliminar -->
    @if($showRemoveConfirmation)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-lg p-6 w-full max-w-md">
                <h3 class="text-lg font-bold mb-4">Confirmar eliminación</h3>
                <p class="mb-4">¿Estás seguro que deseas eliminar a este miembro de la organización?</p>
                
                <div class="flex justify-end space-x-2">
                    <button wire:click="$set('showRemoveConfirmation', false)" class="btn btn-secondary">
                        Cancelar
                    </button>
                    <button wire:click="removeMember" class="btn btn-danger">
                        Eliminar
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>