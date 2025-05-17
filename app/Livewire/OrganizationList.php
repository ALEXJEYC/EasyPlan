<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\Organization;


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
        $user = auth()->user();

        $ownerOrganizations = Organization::whereHas('ownerRelation', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->get();

        $memberOrganizations = $user->memberships()->with('organization')->get()->map(function ($membership) {
            return $membership->organization;
        });


        $this->organizations = $ownerOrganizations->merge($memberOrganizations);
    }


    public function render()
    {
        return view('livewire.organization-list', [
            'organizations' => $this->organizations,
        ]);
    }
}