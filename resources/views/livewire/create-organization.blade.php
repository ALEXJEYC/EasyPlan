<div>
    <!-- Botón para abrir el modal -->
    <div class="group relative">

        <div class="flex justify-center mb-4">
            <button wire:click="openModal"
                class="w-full bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-3 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 min-h-[100px] flex flex-col items-center justify-center">
                <div class="text-center transform transition-transform group-hover:scale-110">
                    <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">+</span>
                    <p class="mt-2 text-lg font-medium text-gray-700 dark:text-gray-300">Nueva Organización</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Modal -->
    @if ($isModalOpen)
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 flex items-center justify-center p-2 sm:p-4">
            <div
                class="relative w-full max-w-[320px] sm:max-w-[400px] md:max-w-[500px] bg-white dark:bg-gray-800 rounded-lg shadow-xl max-h-[85vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                <!-- Encabezado -->
                <div
                    class="sticky top-0 bg-white dark:bg-gray-800 z-10 flex justify-between items-center px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Nueva Organización</h2>
                    <button wire:click="closeModal"
                        class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl p-1">
                        ✕
                    </button>
                </div>

                <!-- Contenido -->
                <div class="px-3 py-2 space-y-3">
                    <form wire:submit.prevent="createOrganization" class="space-y-3">
                        <!-- Sección de imagen -->
                        <div x-data="{ imagePreview: null }" class="mb-3 group">
                            <input type="file" x-ref="fileInput" wire:model="image" class="hidden" accept="image/*"
                                @change="imagePreview = URL.createObjectURL($event.target.files[0])" />
                            <div @click="$refs.fileInput.click()"
                                class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer p-2 text-center h-28 flex items-center justify-center transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview"
                                        class="w-full h-full object-cover rounded-md transform transition-transform group-hover:scale-105" />
                                </template>
                                <template x-if="!imagePreview">
                                    <div class="space-y-1 transform transition-all duration-200 group-hover:scale-105">
                                        <svg class="mx-auto h-6 w-6 text-gray-400 transition-colors duration-200 group-hover:text-blue-500 dark:group-hover:text-blue-400"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p
                                            class="text-xs text-gray-600 dark:text-gray-300 transition-colors duration-200 group-hover:text-blue-500 dark:group-hover:text-blue-400">
                                            Subir imagen</p>
                                    </div>
                                </template>
                            </div>
                            @error('image')
                                <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Campos -->
                        <div class="space-y-3">
                            <!-- Nombre -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre*</label>
                                <input type="text" wire:model="name"
                                    class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1"
                                    placeholder="Nombre de la organización" />
                                @error('name')
                                    <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label
                                    class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción</label>
                                <textarea wire:model="description" rows="2"
                                    class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1"
                                    placeholder="Descripción opcional"></textarea>
                            </div>
                        </div>

                        <!-- Botón -->
                        <div class="pt-3">
                            <button type="submit"
                                class="w-full px-3 py-1.5 text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded flex items-center justify-center gap-1"
                                wire:loading.attr="disabled" wire:target="createOrganization">
                                <svg wire:loading wire:target="createOrganization" class="animate-spin h-3 w-3"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                        stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                        d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                                    </path>
                                </svg>
                                <span>Crear Organización</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:init', () => {
            // Diálogo de confirmación
            Livewire.on('show-confirm-dialog', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas crear esta organización?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, crear organización",
                    cancelButtonText: "Cancelar",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded mx-2',
                        cancelButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        @this.doCreateOrganization();
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: "Operación cancelada",
                            text: "La organización no fue creada.",
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

            // Mensaje de error
            Livewire.on('show-error-message', (data) => {
                Swal.fire({
                    title: "Error",
                    text: data.message,
                    icon: "error",
                    confirmButtonColor: "#d33",
                    buttonsStyling: false,
                    customClass: {
                        confirmButton: 'bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-4 rounded mx-2'
                    }
                });
            });
        });
    </script>
@endpush
