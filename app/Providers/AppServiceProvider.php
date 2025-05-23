<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Livewire\CreateProject;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageServiceProvider; 
use App\Services\Task\TaskStatusService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TaskStatusService::class, function ($app) {
            return new TaskStatusService();
        });
    
    }
    

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    $paths = [
        public_path('images'),
        storage_path('app/public/projects'),
        storage_path('app/public/temp')
    ];

        foreach ($paths as $path) {
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
    }
        // Crear directorios si no existen
        $publicImages = public_path('images');
        $storage = storage_path('app/public/images');     
        
        if (!File::exists($publicImages)) {
            File::makeDirectory($publicImages, 0755, true);
        }
        
        if (!File::exists($storage)) {
            File::makeDirectory($storage, 0755, true);
        }   
        
        // Registrar componente Livewire
        Livewire::component('create-project', CreateProject::class);
        
        // Directiva Blade personalizada
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