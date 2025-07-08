<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $name = ''; // Para el registro
    public $isRegistering = false;
    public $password_confirmation = '';
    public $remember = false;

    protected function rules()
    {
        if ($this->isRegistering) {
            return [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:6|confirmed',
            ];
        }

        return [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ];
    }

public function login()
{
    $this->validate();

    if (Auth::attempt(
        ['email' => $this->email, 'password' => $this->password],
        $this->remember
    )) {
        session()->regenerate();
        return redirect()->route('dashboard');
    }

    $this->addError('email', 'Las credenciales no coinciden.');
}

    public function register()
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        session()->flash('success', 'Cuenta creada exitosamente. Ahora puedes iniciar sesión.');
        $this->isRegistering = false;

        // Limpia los campos de registro
        $this->reset(['name', 'password', 'password_confirmation']);
    }

    public function toggleForm()
    {
        $this->isRegistering = !$this->isRegistering;
        $this->resetValidation();
        $this->reset(['name', 'email', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.login')->layout('layouts.app');
    }
}
