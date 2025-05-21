<style>
    /* Scrollbar base para Webkit (Chrome, Edge, Safari) */
.scrollbar-custom::-webkit-scrollbar {
  width: 8px;
  height: 8px;
  background: transparent;
}

.scrollbar-custom::-webkit-scrollbar-track {
  background: transparent;
}

.scrollbar-custom::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.6);
  border-radius: 9999px;
  transition: background-color 0.3s;
}

/* Thumb cambia al hover */
.scrollbar-custom:hover::-webkit-scrollbar-thumb {
  background-color: rgba(0, 0, 0, 0.9);
}

/* Para Firefox */
.scrollbar-custom {
  scrollbar-width: thin;
  scrollbar-color: rgba(0, 0, 0, 0.6) transparent;
  transition: scrollbar-color 0.3s;
}

.scrollbar-custom:hover {
  scrollbar-color: rgba(0, 0, 0, 0.9) transparent;
}

/* Ocultar scrollbar cuando no se está haciendo scroll (solo en Webkit) */
.scrollbar-custom::-webkit-scrollbar {
  opacity: 0;
  transition: opacity 0.3s;
}

.scrollbar-custom:hover::-webkit-scrollbar {
  opacity: 1;
}

</style>

<div class="max-h-[75vh] overflow-auto p-4 sm:max-h-[80vh] md:max-h-[70vh] scrollbar-custom">
    <form wire:submit.prevent="createProject" class="space-y-4">
    <!-- Campo de imagen -->
    <div x-data="{ imagePreview: null }" class="mt-1">
      <input type="file" x-ref="fileInput" wire:model="image" class="hidden" accept="image/*"
             @change="imagePreview = URL.createObjectURL($event.target.files[0])" />
      <div @click="$refs.fileInput.click()"
           class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer p-4 text-center h-40 sm:h-56 flex items-center justify-center relative transition hover:border-blue-400">
        <template x-if="imagePreview">
          <img :src="imagePreview" class="w-full h-full object-cover rounded-md" />
        </template>
        <template x-if="!imagePreview">
          <div class="text-center">
            <svg class="mx-auto h-10 w-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" 
                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">Haz clic para subir una imagen</p>
          </div>
        </template>
      </div>
      @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>


    <!-- Nombre -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre*</label>
      <input type="text" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm" />
      @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Descripción -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
      <textarea wire:model="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
    </div>

    <!-- Miembros -->
    <div>
      <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Miembros*</label>
      <div class="mt-2 space-y-2 max-h-48 overflow-auto">
        @foreach($this->organizationMembers as $member)
          <div class="flex items-center">
            <input type="checkbox" id="member-{{ $member->id }}" value="{{ $member->id }}" wire:model="members" class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded" />
            <label for="member-{{ $member->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-200">{{ $member->name }}</label>
          </div>
        @endforeach
      </div>
      @error('members') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
    </div>

    <!-- Botones -->
    <div class="flex flex-col sm:flex-row justify-end space-y-3 sm:space-y-0 sm:space-x-3 pt-4">
<button type="submit" 
        class="px-4 py-2 text-sm text-white bg-blue-600 rounded-lg hover:bg-blue-700 flex items-center gap-2"
        wire:loading.attr="disabled" wire:target="createProject">
    <svg wire:loading wire:target="createProject" class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z"></path>
    </svg>
    <span>Crear Proyecto</span>
</button>

    </div>
  </form>
</div>