<div class="space-y-6">
    <!-- Tarjeta de creación de roles -->
    {{-- solo usuario con el permiso canCreateRoles puede ver esta seccion --}}
    @if ($this->canCreateRoles)
        <div
            class="bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
            <h2 class="text-xl font-semibold mb-4 text-gray-800 dark:text-white">
                <i class="fas fa-user-tag mr-2 text-blue-500"></i> Crear nuevo rol
            </h2>


            @if (session()->has('message'))
                <div class="bg-green-100 dark:bg-green-200 border-l-4 border-green-500 text-green-700 p-4 mb-4">
                    {{ session('message') }}
                </div>
            @endif

            <form wire:submit.prevent="createRole" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre del Rol</label>
                    <input type="text" wire:model="newRoleName"
                        class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                    @error('newRoleName')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-200 mb-2">Permisos</label>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                        @foreach ($allPermissions as $permission)
                            <label
                                class="inline-flex items-center space-x-2 bg-gray-50 dark:bg-gray-700 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600">
                                <input type="checkbox" wire:model="selectedPermissions" value="{{ $permission->id }}"
                                    class="rounded text-blue-600 focus:ring-blue-500">
                                <span
                                    class="text-gray-700 dark:text-gray-200">{{ // agregamos los nombres de los permisos
                                        $permissionsnames[$permission->name] ?? $permission->name }}</span>
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
    @endif


    <!-- Tarjeta de gestión de miembros -->
    <div
        class="bg-white dark:bg-gray-800 dark:text-gray-100 rounded-lg shadow-md p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800  dark:text-white">
                <i class="fas fa-users mr-2 text-blue-500 "></i> Miembros
            </h2>

            @if ($this->canAddMembers)
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
            @if (auth()->id() === $organization->ownerRelation->user_id)
                <button wire:click="openTransferModal"
                    class="ml-4 px-4 py-2 bg-yellow-500 text-white rounded-lg hover:bg-yellow-600 transition">
                    <i class="fas fa-exchange-alt mr-1"></i> Transferir organización
                </button>
            @endif
        </div>

        <!-- Lista de miembros -->
        <div class="space-y-3">
            @foreach ($memberships as $membership)
                <div class="border rounded-lg p-4 hover:shadow-md transition-shadow flex justify-between items-center">
                    <div class="flex items-center space-x-4">
                        <div class="bg-gray-100 p-3 rounded-full">
                            <i class="fas fa-user text-gray-500"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800  dark:text-white">{{ $membership->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $membership->user->email }}</p>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        @if ($organization->isOwner($membership->user))
                            <span class="text-gray-500 dark:text-white">Owner</span>
                        @else
                            @if ($this->canManageRoles)
                                <div class="relative w-36 sm:w-44">
                                    <select wire:change="confirmChangeRole({{ $membership->id }}, $event.target.value)"
                                        class="appearance-none w-full pl-3 pr-10 py-1.5 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-sm text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 truncate">
                                        <option value="">Sin rol</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ $membership->custom_role_id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-2 flex items-center ">
                                    </div>
                                </div>
                            @else
                                <span class="text-gray-500">{{ $membership->customRole?->name ?? 'Sin rol' }}</span>
                            @endif

                            @if ($this->canRemoveMembers && $membership->user_id != $ownerId)
                                <button wire:click="confirmRemoveMember({{ $membership->id }})"
                                    class="text-red-500 hover:text-red-700 p-2 rounded-full hover:bg-red-50">
                                    <i class="fas fa-trash-alt"></i>
                                </button>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>




    <!-- Modal para agregar miembros -->
    @if ($showTransferModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md shadow-lg transition-all">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Transferir organización</h3>
                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Selecciona el nuevo
                        dueño</label>
                    <select wire:model="transferToUserId"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar usuario</option>
                        @foreach ($memberships as $membership)
                            @if ($membership->user_id != $organization->ownerRelation->user_id)
                                <option value="{{ $membership->user_id }}">{{ $membership->user->name }}
                                    ({{ $membership->user->email }})
                                </option>
                            @endif
                        @endforeach
                    </select>
                    @error('transferToUserId')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button wire:click="$set('showTransferModal', false)"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button wire:click="transferOrganization"
                        class="px-4 py-2 rounded-lg bg-yellow-600 text-white hover:bg-yellow-700 transition">
                        Transferir
                    </button>
                </div>
            </div>
        </div>
    @endif
    @push('scripts')
        <script>
            Livewire.on('confirm-transfer', () => {
                Swal.fire({
                    title: "¿Estás seguro de transferir la organización?",
                    text: "Este cambio será irreversible.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Sí, transferir",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-yellow-600 hover:bg-yellow-700 text-white font-medium py-2 px-4 rounded mx-2',
                        cancelButton: 'bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: "¿Qué deseas hacer?",
                            text: "¿Quieres retirarte de la organización o quedarte como usuario sin rol?",
                            icon: "question",
                            showCancelButton: true,
                            confirmButtonText: "Retirarme",
                            cancelButtonText: "Quedarme sin rol",
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2',
                                cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded mx-2'
                            }
                        }).then((choice) => {
                            if (choice.isConfirmed) {
                                @this.confirmTransferOrganization(true);
                            } else {
                                @this.confirmTransferOrganization(false);
                            }
                        });
                    }
                });
            });
        </script>
    @endpush
    @if ($showAddMemberModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 w-full max-w-md shadow-lg transition-all">
                <h3 class="text-xl font-bold mb-4 text-gray-800 dark:text-gray-100">Agregar miembro</h3>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Usuario</label>
                    <select wire:model="userToAdd"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Seleccionar usuario</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                    @error('userToAdd')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-4">
                    <label class="block mb-1 font-semibold text-gray-700 dark:text-gray-300">Rol (opcional)</label>
                    <select wire:model="roleToAssign"
                        class="w-full p-2 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Sin rol</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->id }}">{{ $role->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="flex justify-end space-x-2 mt-6">
                    <button wire:click="$set('showAddMemberModal', false)"
                        class="px-4 py-2 rounded-lg bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancelar
                    </button>
                    <button wire:click="addMember"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition">
                        Agregar
                    </button>
                </div>
            </div>
        </div>
    @endif
    @push('scripts')
        <script>
            Livewire.on('confirm-rol', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas crear este Rol?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, crear Rol",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded mx-2',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.createRoleConfirmed();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Operación cancelada",
                            text: "El rol no fue creado.",
                            icon: "error",
                            confirmButtonColor: "#d33",
                            buttonsStyling: false,
                            customClass: {
                                confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2'
                            }
                        });
                    }
                });
            });
            // Mensaje de éxito
            Livewire.on('show-success-message', (data) => {
                Swal.fire({
                    title: data.title,
                    text: data.message,
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                });
            });
        </script>
    @endpush
    {{-- eliminacion de usuario --}}
    @push('scripts')
        <script>
            Livewire.on('show-remove-confirmation', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas eliminar a este miembro de la organización?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "Sí, eliminar",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2',
                        cancelButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.removeMember();
                    }
                });
            });
        </script>
    @endpush
    {{-- cambiarroldeusuario --}}

    @push('scripts')
        <script>
            Livewire.on('show-role-change-confirmation', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas cambiar el rol de este usuario?",
                    icon: "question",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, cambiar",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded mx-2',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.applyRoleChange();
                    }
                });
            });
        </script>
    @endpush
{{-- notificaciones --}}
@push('scripts')
    <script>
        Livewire.on('notify', (data) => {
            Toastify({
                text: data.message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: data.type === 'success' ? '#10B981' : '#EF4444',
                className: "shadow-lg rounded-xl",
                stopOnFocus: true,
            }).showToast();
        });
    </script>
@endpush
