<x-app-layout>
  <div x-data="{ open: false }" class="max-w-md mx-auto bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mt-10">
    <div class="flex flex-col items-center">
      
      <!-- Imagen de perfil -->
      @if (Auth::user()->profile_photo_path)
        <img src="{{ asset('storage/' . Auth::user()->profile_photo_path) }}" alt="Foto de perfil" 
          class="w-32 h-32 rounded-full border-4 border-blue-500 shadow-lg mb-4 object-cover">
      @elseif (Auth::user()->avatar)
        <img src="{{ Auth::user()->avatar }}" alt="Foto de perfil" 
          class="w-32 h-32 rounded-full border-4 border-blue-500 shadow-lg mb-4 object-cover" referrerpolicy="no-referrer">
      @else
        <div class="w-32 h-32 rounded-full bg-gray-300 flex items-center justify-center text-gray-500 mb-4">
          <i class="fas fa-user fa-4x"></i>
        </div>
      @endif

      <!-- Formulario para actualizar perfil -->
      <form method="POST" action="{{ route('settings.account.update') }}" enctype="multipart/form-data" class="w-full">
        @csrf
        @method('PUT')

        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold">Foto de perfil</label>
        <input type="file" name="profile_photo" accept="image/*" class="mb-6 w-full rounded border border-gray-300 p-2">

        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold flex items-center gap-2">
          <i class="fas fa-pencil-alt text-blue-500"></i> Nombre
        </label>
        <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" 
          class="w-full border rounded p-2 mb-6 focus:ring-2 focus:ring-blue-400" required>

        <label class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold">Correo</label>
        <input type="email" value="{{ Auth::user()->email }}" disabled
          class="w-full border rounded p-2 mb-6 bg-gray-100 dark:bg-gray-700 cursor-not-allowed">

        <label for="country_code" class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold">Código de país</label>
        <select name="country_code" id="country_code" class="w-full border rounded p-2 mb-6">
            <option value="CL" {{ old('country_code', 'CL') == 'CL' ? 'selected' : '' }}>Chile (+56)</option>
            <option value="US" {{ old('country_code') == 'US' ? 'selected' : '' }}>Estados Unidos (+1)</option>
            <option value="MX" {{ old('country_code') == 'MX' ? 'selected' : '' }}>México (+52)</option>
        </select>
        @error('country_code')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror

        <label for="phone" class="block text-gray-700 dark:text-gray-300 mb-2 font-semibold">Teléfono</label>
        <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" class="w-full border rounded p-2 mb-6" placeholder="Ingresa tu número">
        @error('phone')<span class="text-red-600 text-sm">{{ $message }}</span>@enderror


        <button type="submit"
          class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded transition duration-200 w-full">
          Guardar cambios
        </button>
      </form>

      <!-- Botón para abrir modal de contraseña -->
      <button
        @click="open = true"
        class="mt-6 bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded w-full font-semibold transition duration-200"
      >
        Cambiar contraseña
      </button>
    </div>

    <!-- Modal -->
    <div
      x-show="open"
      x-cloak
      class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
      @keydown.escape.window="open = false"
    >
      <div
        @click.away="open = false"
        class="relative bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 max-w-md w-full"
      >

        <button
        @click="open = false"
        class="absolute top-3 right-30 text-gray-500 hover:text-gray-800 dark:hover:text-gray-200 text-2xl font-bold leading-none"
        aria-label="Cerrar"
        title="Cerrar"
        >
        &times;
        </button>

        <h2 class="text-xl font-bold mb-4 text-gray-900 dark:text-gray-100">Cambiar contraseña</h2>

        @livewire('change-password')
      </div>
    </div>
  </div>
</x-app-layout>
