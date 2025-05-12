<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrganizationController;
use Illuminate\Support\Facades\Route;
use App\Livewire\Login;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

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

// Ruta raíz y login personalizado con Livewire
Route::get('/', Login::class)->name('login')->middleware('guest');

// Rutas para usuarios invitados (guest)
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::post('/', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

// Rutas protegidas por autenticación (auth)
Route::middleware('auth')->group(function () {
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('verify-email', EmailVerificationPromptController::class)->name('verification.notice');
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
    Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

    Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])->name('password.confirm');
    Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    Route::put('password', [PasswordController::class, 'update'])->name('password.update');

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
Route::get('/organization/{organization}', [OrganizationController::class, 'show'])->name('organization.show');
// Rutas adicionales para chats y mensajes
Route::middleware(['auth'])->group(function () {
    Route::get('/chats/{chat}/get-user', [ChatController::class, 'getUser'])->name('chat.get-user');
    Route::get('/chats/{chat}', [ChatController::class, 'show'])->name('chat.show');
    Route::get('/chats/with/{user}', [ChatController::class, 'chatWith'])->name('chat.chatWith');
    Route::post('/message/sent', [MessageController::class, 'sent'])->name('message.sent');
    Route::get('/chats/{chat}/get-messages', [MessageController::class, 'getMessages'])->name('getMessages');
});