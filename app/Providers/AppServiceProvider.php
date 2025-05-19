<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Livewire\CreateProject;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */



    public function boot(): void
        {
        Livewire::component('create-project', CreateProject::class);
    

    Blade::if('permissionInOrg', function ($permission, $organization) {
        $user = auth()->user();
        
        // Verificar si es owner
        if ($organization->isOwner($user)) {
            return true;
        }
        
        // Verificar permiso específico
        return $user->hasPermissionInOrganization($permission, $organization->id);
    });
        }
    }