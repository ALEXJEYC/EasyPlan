@extends('layouts.auth')

@section('title', 'Registro')

@section('content')
<div class="flex flex-col items-center justify-center min-h-screen p-4 sm:p-6">
    <!-- Contenedor principal con márgenes verticales y ancho controlado -->
    <div class="w-full max-w-md my-8 sm:my-12">
        <!-- Tarjeta del formulario con sombra y bordes redondeados -->
        <div>
            <h1 class="text-2xl font-bold text-center text-gray-800 dark:text-white mb-6">Crea tu cuenta</h1>
            
            <form method="POST" action="{{ route('register') }}" class="space-y-4" id="registerForm">
                @csrf

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nombre Completo</label>
                    <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                           class="mt-1 block w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('name')
                        <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span>
                    @enderror
                </div>


                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Correo Electrónico</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                    @error('email')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Contraseña</label>
                    <input id="password" type="password" name="password" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           oninput="validatePassword()">
                    @error('password')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    
                    <div class="mt-2 space-y-1 text-sm" id="passwordRequirements">
                        <div class="flex items-center" id="lengthRequirement">
                            <span class="mr-2">✓</span>
                            <span>Mínimo 8 caracteres</span>
                        </div>
                        <div class="flex items-center" id="uppercaseRequirement">
                            <span class="mr-2">✓</span>
                            <span>Al menos una mayúscula</span>
                        </div>
                        <div class="flex items-center" id="lowercaseRequirement">
                            <span class="mr-2">✓</span>
                            <span>Al menos una minúscula</span>
                        </div>
                        <div class="flex items-center" id="numberRequirement">
                            <span class="mr-2">✓</span>
                            <span>Al menos un número</span>
                        </div>
                        <div class="flex items-center" id="specialRequirement">
                            <span class="mr-2">✓</span>
                            <span>Al menos un carácter especial (@$!%*?&)</span>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirmar Contraseña</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" required
                           class="mt-1 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                           oninput="checkPasswordMatch()">
                    <div class="mt-1 text-sm" id="passwordMatchMessage"></div>
                </div>

                <button type="submit" id="submitButton" disabled
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-400 hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Registrarse
                </button>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400">O regístrate con</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-3">
                    <a href="{{ route('google.login') }}"
                       class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm bg-white dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <img class="h-5 w-5" src="https://www.google.com/favicon.ico" alt="Google">
                        <span class="ml-2">Google</span>
                    </a>
                </div>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    ¿Ya tienes una cuenta?
                    <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-500">
                        Inicia Sesión
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
// Estado global para controlar la validación
let passwordValid = false;
let passwordsMatch = false;

function validatePassword() {
    const password = document.getElementById('password').value;
    const submitButton = document.getElementById('submitButton');
    
    // Validar cada requisito
    const hasMinLength = password.length >= 8;
    const hasUpperCase = /[A-Z]/.test(password);
    const hasLowerCase = /[a-z]/.test(password);
    const hasNumber = /[0-9]/.test(password);
    const hasSpecialChar = /[@$!%*?&]/.test(password);
    
    // Actualizar indicadores visuales
    updateRequirement('lengthRequirement', hasMinLength);
    updateRequirement('uppercaseRequirement', hasUpperCase);
    updateRequirement('lowercaseRequirement', hasLowerCase);
    updateRequirement('numberRequirement', hasNumber);
    updateRequirement('specialRequirement', hasSpecialChar);
    
    // Actualizar estado de validación
    passwordValid = hasMinLength && hasUpperCase && hasLowerCase && hasNumber && hasSpecialChar;
    
    // Actualizar estado del botón
    updateSubmitButton();
    
    // Verificar coincidencia de contraseñas si ya hay algo en confirmación
    if (document.getElementById('password_confirmation').value.length > 0) {
        checkPasswordMatch();
    }
}

function updateRequirement(elementId, isValid) {
    const element = document.getElementById(elementId);
    if (isValid) {
        element.classList.remove('text-gray-500', 'text-red-500');
        element.classList.add('text-green-500');
        element.querySelector('span:first-child').textContent = '✓';
    } else {
        element.classList.remove('text-green-500');
        element.classList.add('text-gray-500');
        element.querySelector('span:first-child').textContent = '✗';
    }
}

function checkPasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('password_confirmation').value;
    const messageElement = document.getElementById('passwordMatchMessage');
    
    if (confirmPassword.length === 0) {
        messageElement.textContent = '';
        passwordsMatch = false;
    } else if (password === confirmPassword) {
        messageElement.textContent = 'Las contraseñas coinciden';
        messageElement.className = 'mt-1 text-sm text-green-500';
        passwordsMatch = true;
    } else {
        messageElement.textContent = 'Las contraseñas no coinciden';
        messageElement.className = 'mt-1 text-sm text-red-500';
        passwordsMatch = false;
    }
    
    updateSubmitButton();
}

function updateSubmitButton() {
    const submitButton = document.getElementById('submitButton');
    const isFormValid = passwordValid && passwordsMatch;
    
    submitButton.disabled = !isFormValid;
    submitButton.className = isFormValid 
        ? 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500'
        : 'w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-400 hover:bg-blue-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500';
}

// Inicializar validación al cargar
document.addEventListener('DOMContentLoaded', function() {
    // Establecer estado inicial de los requisitos
    const requirements = document.querySelectorAll('#passwordRequirements div');
    requirements.forEach(req => {
        req.classList.add('text-gray-500');
        req.querySelector('span:first-child').textContent = '✗';
    });
    
    // Agregar event listeners
    document.getElementById('password').addEventListener('input', validatePassword);
    document.getElementById('password_confirmation').addEventListener('input', checkPasswordMatch);
});
</script>

<style>
#passwordRequirements div {
    transition: all 0.3s ease;
    margin-bottom: 0.25rem;
}

#passwordRequirements span:first-child {
    display: inline-block;
    width: 1rem;
    text-align: center;
}

#submitButton:disabled {
    cursor: not-allowed;
    opacity: 0.7;
}

#submitButton:not(:disabled) {
    cursor: pointer;
    transition: all 0.3s ease;
}

/* Asegurar que el contenedor principal ocupe al menos el alto de la pantalla */
.min-h-screen {
    min-height: 100vh;
}

/* Mejorar el scroll si es necesario */
html {
    overflow-y: auto;
}
</style>
@endsection