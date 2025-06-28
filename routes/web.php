<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Login;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\SettingsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Ruta raíz y login personalizado con Livewire
Route::get('/', Login::class)->name('login')->middleware('guest');

// Rutas de autenticación
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    
    // Rutas de proyectos
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
    Route::patch('/projects/{project}/archive', [ProjectController::class, 'archive'])->name('projects.archive');
    Route::patch('/projects/{project}/restore', [ProjectController::class, 'restore'])->name('projects.restore');
    
    // Rutas de chats
    Route::get('/chats', [ChatController::class, 'userChats'])->name('chats.index');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chats/with/{user}', [ChatController::class, 'chatWith'])->name('chat.chatWith');
    
    // Rutas de mensajes
    Route::post('/message/sent', [MessageController::class, 'sent'])->name('message.sent');
    Route::get('/chats/{chat}/get-messages', [MessageController::class, 'getMessages'])->name('getMessages');
    Route::get('/chats/{chat}/get-user', [ChatController::class, 'getUser'])->name('chat.get-user');
    
    // Rutas de organizaciones
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{organization}/chats', [ChatController::class, 'index'])->name('chats.org.index');
    Route::post('/organizations/{organization}/sync-chat-members', [OrganizationController::class, 'syncChatMembers'])->name('organizations.syncChatMembers');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
    Route::put('/organizations/{organization}/update-role/{user}', [OrganizationController::class, 'updateMemberRole'])->name('organizations.updateMemberRole');
    
    // Rutas de configuración
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/account', [SettingsController::class, 'account'])->name('settings.account');
    Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account.update');
    Route::get('/settings/account/password', [SettingsController::class, 'showPasswordForm'])->name('settings.account.password');
    Route::put('/settings/account/password', [SettingsController::class, 'updatePassword'])->name('settings.account.password.update');
});

Route::get('/auth/user', function () {
    if (auth()->check()) {
        return response()->json([
            'authUser' => auth()->user()
        ]);
    }
    return null;
});