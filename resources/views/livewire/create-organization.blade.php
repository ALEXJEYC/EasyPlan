<div>
    <!-- Formulario -->
    <div class="mb-4">
        <label for="organizationName" class="block text-sm font-medium text-gray-700">Nombre de la Organización</label>
        <input type="text" 
               id="organizationName" 
               wire:model="name" 
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        
        @error('name') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
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
            cancelButtonText: "Cancelar"
        }).then((result) => {
            if (result.isConfirmed) {
                // Llamamos directamente al método del componente
                @this.doCreateOrganization();
            }
        });
    });
    
    // Mensaje de éxito
    Livewire.on('show-success-message', (data) => {
        Swal.fire({
            title: data.title,
            text: data.message,
            icon: "success",
            confirmButtonColor: "#3085d6"
        });
    });
    
    // Mensaje de error
    Livewire.on('show-error-message', (data) => {
        Swal.fire({
            title: "Error",
            text: data.message,
            icon: "error",
            confirmButtonColor: "#d33"
        });
    });
});
</script>
@endpush