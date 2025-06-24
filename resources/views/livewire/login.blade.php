<!-- TODO: mejorar visibilidad del formulario sin romper logica y funcionalidades de las vistas  -->

<div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900">
    <div class="w-full max-w-md bg-white dark:bg-gray-800 p-6 rounded-lg shadow-md">
        <!-- Botones para alternar entre Login y Registro -->
        <div class="flex justify-between mb-6">
            <button 
                wire:click="toggleForm" 
                class="w-1/2 px-4 py-2 font-bold text-center rounded-l-lg {{ !$isRegistering ? 'bg-blue-500 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                Login
            </button>
            <button 
                wire:click="toggleForm" 
                class="w-1/2 px-4 py-2 font-bold text-center rounded-r-lg {{ $isRegistering ? 'bg-blue-500 text-white' : 'bg-gray-300 dark:bg-gray-700 text-gray-700 dark:text-gray-300' }}">
                Register
            </button>
        </div>

        @if (!$isRegistering)
            <!-- Formulario de Login -->

            <form wire:submit.prevent="login">
                @csrf

                <!-- Email Address -->
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" id="email" wire:model="email" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input type="password" id="password" wire:model="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>
                <!-- Remember Me -->
                <div class="block mt-4">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" wire:model="remember" class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800">
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Recordar sesión</span>
                    </label>
                </div>

                <!-- Botón de Login -->
                <div class="flex items-center justify-end mt-4">
                    <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Iniciar Sesión</button>
                </div>
            </form>
        @else
            <!-- Formulario de Registro -->
            <form wire:submit.prevent="register">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Registrarse</h2>

                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre</label>
                    <input type="text" id="name" wire:model="name" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input type="email" id="email" wire:model="email" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input type="password" id="password" wire:model="password" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                    <input type="password" id="password_confirmation" wire:model="password_confirmation" class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm bg-gray-50 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                    @error('password_confirmation') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                </div>

                <button type="submit" class="w-full bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">Registrarse</button>
            </form>
        @endif
    </div>
</div>