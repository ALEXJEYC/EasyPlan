<div>
    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
            {{ session('message') }}
        </div>
    @endif

    <!-- Formulario -->
    <div class="mb-4">
        <label for="organizationName" class="block text-sm font-medium text-gray-700">Nombre de la Organización</label>
        <input type="text" 
               id="organizationName" 
               wire:model="name" 
               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        
        <!-- Mostrar errores de validación -->
        @error('name') 
            <span class="text-red-500 text-xs">{{ $message }}</span> 
        @enderror
    </div>

    <!-- Botón para crear organización -->
    <button wire:click="createOrganization" 
            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
        Crear Organización
    </button>
</div>