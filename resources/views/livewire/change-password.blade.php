

<form wire:submit.prevent="updatePassword">
        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Contraseña actual</label>
            <input type="password" wire:model.defer="current_password" class="w-full border rounded p-2" autocomplete="current-password" />
            @error('current_password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Nueva contraseña</label>
            <input type="password" wire:model.defer="password" class="w-full border rounded p-2" autocomplete="new-password" />
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-6">
            <label class="block mb-1 font-medium text-gray-700 dark:text-gray-300">Confirmar nueva contraseña</label>
            <input type="password" wire:model.defer="password_confirmation" class="w-full border rounded p-2" autocomplete="new-password" />
        </div>

        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white py-2 px-4 rounded w-full font-semibold transition duration-200">
            Cambiar contraseña
        </button>
</form>