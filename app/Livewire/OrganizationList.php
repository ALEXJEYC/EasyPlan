<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class OrganizationList extends Component
{
    // Escuchar el evento emitido por CreateOrganization
    protected $listeners = ['organization-created' => 'refreshOrganizations'];
    public $organizations;

    public function mount()
    {
        $this->loadOrganizations();
    }
    public function refreshOrganizations()
    {
        $this->loadOrganizations();
    }
    private function loadOrganizations()
    {
        $this->organizations = auth()->user()->organizations;
    }

    public function render()
    {
        return view('livewire.organization-list', [
            'organizations' => $this->organizations,
        ]);
    }
}