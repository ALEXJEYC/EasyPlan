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
|
| Aquí puedes registrar las rutas de tu aplicación. Estas rutas están
| cargadas por el RouteServiceProvider y todas estarán dentro del grupo
| "web".
|
*/
//ruta configuraciones
Route::middleware(['auth'])->group(function () {
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
    Route::get('/settings/account', [SettingsController::class, 'account'])->name('settings.account');
    Route::put('/settings/account', [SettingsController::class, 'updateAccount'])->name('settings.account.update');
    Route::get('/settings/account/password', [SettingsController::class, 'showPasswordForm'])->name('settings.account.password');
    Route::put('/settings/account/password', [SettingsController::class, 'updatePassword'])->name('settings.account.password.update');
});

// Ruta raíz y login personalizado con Livewire
Route::get('/', Login::class)->name('login')->middleware('guest');
Route::get('/projects/{project}', [ProjectController::class, 'show'])->name('projects.show');
Route::patch('/projects/{project}/archive', [ProjectController::class, 'archive'])->name('projects.archive');
Route::patch('/projects/{project}/unarchive', [ProjectController::class, 'unarchive'])->name('projects.unarchive');
// Rutas para la creación de organizaciones
Route::middleware(['auth'])->group(function () {
    Route::get('/organizations/{organization}', [OrganizationController::class, 'show'])->name('organizations.show');
    Route::get('/organizations/{organization}/chats', [ChatController::class, 'index'])->name('chats.index');
    Route::post('/organizations/{organization}/sync-chat-members', [OrganizationController::class, 'syncChatMembers'])->name('organizations.syncChatMembers');
    Route::delete('/organizations/{organization}', [OrganizationController::class, 'destroy'])->name('organizations.destroy');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::put('/organizations/{organization}/update-role/{user}', [OrganizationController::class, 'updateMemberRole'])->name('organizations.updateMemberRole');
});

// Rutas adicionales para chats y mensajes
Route::middleware(['auth'])->group(function () {
    Route::get('/chats/{chat}/get-user', [ChatController::class, 'getUser'])->name('chat.get-user');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chats/with/{user}', [ChatController::class, 'chatWith'])->name('chat.chatWith');
    Route::post('/message/sent', [MessageController::class, 'sent'])->name('message.sent');
    Route::get('/chats/{chat}/get-messages', [MessageController::class, 'getMessages'])->name('getMessages');
});

// Rutas protegidas por autenticación (auth)
Route::middleware('auth')->group(function () {
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});
Route::get('/auth/user', function () {
    if (auth()->check()) {
        return response()->json([
            'authUser' => auth()->user()
        ]);
    }
    return null;
});


