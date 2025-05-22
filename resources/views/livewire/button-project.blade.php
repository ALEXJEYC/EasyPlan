<div>
    @if($this->canCreateProject)
    <!-- Botón para abrir el modal (sin cambios) -->
    <div class="group relative">
        
        <button wire:click="openModal" class="w-full h-full bg-white dark:bg-gray-800 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 p-6 border-2 border-dashed border-gray-300 dark:border-gray-600 hover:border-blue-400 min-h-[300px] flex flex-col items-center justify-center"> 
            <div class="text-center transform transition-transform group-hover:scale-110">
                <span class="text-4xl font-bold text-blue-600 dark:text-blue-400">+</span>
                <p class="mt-2 text-lg font-medium text-gray-700 dark:text-gray-300">Nuevo Proyecto</p>
            </div>
        </button>
    </div>
    @endif
    @if($isModalOpen)
        <!-- Contenedor principal del modal - Cambio clave: items-center -->
        <div class="fixed inset-0 bg-black/30 backdrop-blur-sm z-40 flex items-center justify-center p-2 sm:p-4"> <!-- Aquí el cambio -->
            <div class="relative w-full max-w-[320px] sm:max-w-[400px] md:max-w-[500px] bg-white dark:bg-gray-800 rounded-lg shadow-xl max-h-[85vh] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                
                <!-- Encabezado (sin cambios) -->
                <div class="sticky top-0 bg-white dark:bg-gray-800 z-10 flex justify-between items-center px-3 py-2 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-base font-medium text-gray-900 dark:text-white">Nuevo Proyecto</h2>
                    <button wire:click="closeModal" class="text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 text-xl p-1">
                        ✕
                    </button>
                </div>
                <!-- Contenido ajustado -->
                <div class="px-3 py-2 space-y-3">
                    <form wire:submit.prevent="createProject" class="space-y-3">
                        
                         <!-- Sección de imagen con efectos mejorados -->
                        <div x-data="{ imagePreview: null }" class="mb-3 group">
                            <input type="file" x-ref="fileInput" wire:model="image" class="hidden" accept="image/*"
                                   @change="imagePreview = URL.createObjectURL($event.target.files[0])" />
                            <div @click="$refs.fileInput.click()"
                                 class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer p-2 text-center h-28 flex items-center justify-center transition-all duration-200 hover:border-blue-400 dark:hover:border-blue-500">
                                <template x-if="imagePreview">
                                    <img :src="imagePreview" class="w-full h-full object-cover rounded-md transform transition-transform group-hover:scale-105" />
                                </template>
                                <template x-if="!imagePreview">
                                    <div class="space-y-1 transform transition-all duration-200 group-hover:scale-105">
                                        <svg class="mx-auto h-6 w-6 text-gray-400 transition-colors duration-200 group-hover:text-blue-500 dark:group-hover:text-blue-400" 
                                             fill="none" 
                                             stroke="currentColor" 
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" 
                                                  stroke-linejoin="round" 
                                                  stroke-width="1.5"
                                                  d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                        <p class="text-xs text-gray-600 dark:text-gray-300 transition-colors duration-200 group-hover:text-blue-500 dark:group-hover:text-blue-400">Subir imagen</p>
                                    </div>
                                </template>
                            </div>
                            @error('image') <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <!-- Campos compactos -->
                        <div class="space-y-3">
                            <!-- Nombre -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Nombre*</label>
                                <input type="text" wire:model="name"
                                       class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1" />
                                @error('name') <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Descripción -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Descripción</label>
                                <textarea wire:model="description" rows="2"
                                          class="w-full text-sm rounded border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white px-2 py-1"></textarea>
                            </div>

                            <!-- Miembros compactos -->
                            <div>
                                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200 mb-1">Miembros*</label>
                                <div class="mt-1 space-y-1 max-h-[120px] overflow-y-auto scrollbar-thin scrollbar-thumb-gray-300 dark:scrollbar-thumb-gray-600">
                                    @foreach($this->organizationMembers as $member)
                                        <div class="flex items-center p-1 hover:bg-gray-50 dark:hover:bg-gray-700 rounded">
                                            <input type="checkbox" id="member-{{ $member->id }}" value="{{ $member->id }}" wire:model="members"
                                                   class="h-3 w-3 text-blue-600 border-gray-300 dark:border-gray-600" />
                                            <label for="member-{{ $member->id }}" class="ml-2 text-xs text-gray-700 dark:text-gray-200">{{ $member->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                                @error('members') <span class="block mt-1 text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <!-- Botón compacto -->
                        <div class="pt-3">
                            <button type="submit"
                                    class="w-full px-3 py-1.5 text-xs sm:text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded flex items-center justify-center gap-1"
                                    wire:loading.attr="disabled" wire:target="createProject">
                                <svg wire:loading wire:target="createProject" class="animate-spin h-3 w-3" xmlns="http://www.w3.org/2000/svg"
                                     fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10"
                                            stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor"
                                          d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
                                </svg>
                                <span>Crear</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>