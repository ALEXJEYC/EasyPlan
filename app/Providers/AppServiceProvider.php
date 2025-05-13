<?php

namespace App\Providers;

use Livewire\Livewire;
use App\Livewire\CreateProject;
use Illuminate\Support\ServiceProvider;

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
    }
}
