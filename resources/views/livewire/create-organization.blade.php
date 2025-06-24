<!-- TODO: cambiar formulario de FORMULARIO  AGREGAR CAMPOS DE SUBIR IMAGEN DE ORGANIZAICON  -->
 <!-- TODO: rmovimiento con javascript de el titulo de campo de la imagen de organizacion para que pase del placeholder a la descripcion -->
<div>
    <!-- Formulario -->
    <div class="mb-4">
        <label for="organizationName" class="block text-sm font-medium text-gray-700">Nombre de la Organización</label>
        
        <input type="text"
        id="organizationName" wire:model="name" 
               class="block w-full px-4 py-2 mt-1 border rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm dark:bg-gray-800 dark:text-gray-200 dark:border-gray-700" 
               placeholder="Nombre de la Organización" 
               required>
        
        @error('name') 
            <span class="text-red-500 text-xs">{{ $message  ?? 'Error' }}</span> 
        @enderror
    </div>

    <button wire:click="showConfirm" 
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
        Crear Organización
    </button>

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