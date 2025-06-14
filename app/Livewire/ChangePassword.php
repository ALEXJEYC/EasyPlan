<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class ChangePassword extends Component
{
    public $current_password;
    public $password;
    public $password_confirmation;

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ];
    }

    protected $messages = [
        'current_password.required' => 'Debes ingresar tu contraseña actual.',
        'password.required' => 'La nueva contraseña es obligatoria.',
        'password.confirmed' => 'La confirmación no coincide.',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
        'password.mixed' => 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
        'password.numbers' => 'La contraseña debe contener al menos un número.',
        'password.symbols' => 'La contraseña debe contener al menos un símbolo.'
    ];

    public function updatePassword()
    {
        $this->validate();

        $user = Auth::user();

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'La contraseña actual es incorrecta.');
            return;
        }

        $user->password = Hash::make($this->password);
        $user->save();

        $this->reset(['current_password', 'password', 'password_confirmation']);

        $this->dispatch('password-updated', ['message' => 'Contraseña actualizada correctamente.']);
    }

    public function render()
    {
        return view('livewire.change-password');
    }
}
