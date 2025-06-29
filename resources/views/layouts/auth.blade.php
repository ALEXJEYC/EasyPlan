<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} - @yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            overflow: hidden;
        }
        .auth-container {
            position: relative;
            z-index: 10;
        }
        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            pointer-events: none;
        }
    </style>
</head>
<body>
    <canvas id="particles" class="fixed top-0 left-0 w-full h-full"></canvas>
    
    <div class="min-h-screen flex items-center justify-center auth-container">
        <div class="w-full max-w-md bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden">
            @yield('content')
        </div>
    </div>

    <script src="{{ asset('js/auth-background.js') }}"></script>
</body>
</html>