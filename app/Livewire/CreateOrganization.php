<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Organization;

class CreateOrganization extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|string|max:255',
    ];

    public function createOrganization()
    {
    // Validar entrada
    $this->validate([
        'name' => 'required|string|max:255',
    ]);

    // Crear organización
    $organization = Organization::create([
        'name' => $this->name,
        'owner_id' => auth()->id(), // Establecer el propietario en la tabla organizations
    ]);

    // Asociar al usuario autenticado con la organización en la tabla memberships
    $organization->members()->attach(auth()->id(), ['role' => 'owner']);

    // Limpiar campo del formulario
    $this->reset('name');

    // Mensaje flash de éxito
    session()->flash('message', 'Organización creada exitosamente.');

        // Opcional: emitir evento para notificar a otros componentes
    // $this->dispatch('organization-created');
    $this->dispatch('organization-created', $organization->id);
    }

    public function render()
    {
        return view('livewire.create-organization');
    }
}