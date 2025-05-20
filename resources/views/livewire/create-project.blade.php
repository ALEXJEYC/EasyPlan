<div class="bg-white dark:bg-gray-800 dark:text-gray-100 p-6 rounded-lg shadow-md">
    <form wire:submit.prevent="createProject" x-data="imageUpload()" x-init="init()">
        <!-- ... otros campos ... -->
        
        <!-- Sección de Imagen con Previsualización -->
        <div class="mb-4">
            <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Imagen del Proyecto</label>
            
            <!-- Input de archivo oculto con referencia Alpine -->
            <input x-ref="fileInput" type="file" id="image" wire:model="image" class="hidden" accept="image/*">
            
            <!-- Área de arrastrar y soltar -->
<div
  x-data="{ imagePreview: null }"
  x-init="
    Livewire.on('imageUpdated', url => {
      imagePreview = url;
    })
  "
>
    <!-- Input oculto -->
  <input type="file" wire:model="image" @change="
    const reader = new FileReader();
    reader.onload = e => imagePreview = e.target.result;
    reader.readAsDataURL($event.target.files[0]);
  ">

    <!-- Previsualización -->

    <!-- Contenido cuando no hay imagen -->
  <template x-if="imagePreview">
    <img :src="imagePreview" alt="Previsualización" class="mb-4 h-40 object-cover rounded-lg shadow-sm" />
            <div class="text-center">
            <i class="fas fa-cloud-upload-alt text-4xl text-gray-400 mb-2"></i>
            <p class="text-sm text-gray-500">
                Arrastra una imagen aquí o haz clic para seleccionar
            </p>
            <p class="text-xs text-gray-400 mt-1">
                Formatos soportados: JPG, PNG, GIF (Max. 1MB)
            </p>
        </div>
  </template>

    <!-- Barra de progreso -->
    <div x-show="isUploading" class="w-full bg-gray-200 rounded-full h-2.5 mt-4">
        <div class="bg-blue-600 h-2.5 rounded-full" :style="`width: ${progress}%`"></div>
    </div>
</div>

            @error('image') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre del Proyecto</label>
            <input type="text" id="name" wire:model="name" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
            @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Descripción -->
        <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción</label>
            <textarea id="description" wire:model="description" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm"></textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Imagen -->

        <!-- Miembros -->
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Miembros</label>
            <div class="mt-2 space-y-2">
                @foreach ($organizationMembers as $member)
                    <div class="flex items-center">
                        <input type="checkbox" id="member-{{ $member->id }}" value="{{ $member->id }}" wire:model="members" class="h-4 w-4 text-blue-600 border-gray-300 dark:border-gray-600 rounded">
                        <label for="member-{{ $member->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-200">{{ $member->name }}</label>
                    </div>
                @endforeach
            </div>
            @error('members') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>
        <!-- Botón -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Crear Proyecto</button>
        </div>



    </form>
</div>
<script>
document.addEventListener('livewire:init', () => {
    Livewire.on('resetImagePreview', () => {
        const input = document.getElementById('image');
        if (input) {
            input.value = '';
        }
    });
});

</script>