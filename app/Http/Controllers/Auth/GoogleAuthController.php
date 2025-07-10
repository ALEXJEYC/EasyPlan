<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            // Busca un usuario por su email o crea uno nuevo si no existe.
            // Esto une a los usuarios que se registraron manualmente primero.
            $user = User::firstOrCreate(
                [
                    'email' => $googleUser->getEmail(),
                ],
                [
                    'name' => $googleUser->getName(),
                    'password' => Hash::make(Str::random(24)), // Contraseña aleatoria solo en la creación
                ]
            );
 
            // Actualiza el google_id y el avatar en cada login con Google.
            // Esto asegura que la cuenta quede vinculada y el avatar actualizado.
            $user->google_id = $googleUser->getId();
            $user->avatar = $googleUser->getAvatar();
            $user->save();
 
            Auth::login($user);
 
            return redirect('/dashboard');
 
        } catch (\Exception $e) {
            // ¡Aquí está la magia! Registramos el error detallado en el log.
            Log::error('Error en la autenticación con Google: ' . $e->getMessage());
        }
    }
}