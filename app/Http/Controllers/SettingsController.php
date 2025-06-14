<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Propaganistas\LaravelPhone\PhoneNumber;
use libphonenumber\PhoneNumberUtil;
use libphonenumber\PhoneNumberFormat;

class SettingsController extends Controller
{
    public function account()
    {
        return view('settings.account');
    }
public function updateAccount(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'country_code' => 'required|string|size:2',
        'phone' => ['nullable', 'string', 'max:20', 'phone:' . $request->input('country_code')],
        'profile_photo' => 'nullable|image|max:2048',
    ], [
        'phone.phone' => 'El número telefónico no es válido para el país seleccionado.',
        'country_code.required' => 'Debes seleccionar un código de país.',
    ]);

    $user = Auth::user();
    $user->name = $request->name;

    if ($request->phone) {
        // Guardamos el número con el código de país delante (ejemplo: +56 912345678)
        $user->phone = '+' . $this->getCountryCodePrefix($request->country_code) . $request->phone;
    } else {
        $user->phone = null;
    }
//TODO: tarea realizar un cambio en la tabla de usuarios para agregar el campo profie_photo_path
    if ($request->hasFile('profile_photo')) {
        if ($user->profile_photo_path) {
            Storage::delete('public/' . $user->profile_photo_path);
        }
        $path = $request->file('profile_photo')->store('profile-photos', 'public');
        $user->profile_photo_path = $path;
    }

    $user->save();

    return redirect()->route('settings.account')->with('success', 'Cuenta actualizada correctamente.');
}

private function getCountryCodePrefix($countryCode)
{
    $codes = [
        'CL' => '56',
        'US' => '1',
        'MX' => '52',
        // más códigos según necesites
    ];

    return $codes[$countryCode] ?? '';
}

    public function index()
    {
        return view('settings.index');
    }
}
