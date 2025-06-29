<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5"> 
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $chat->organization->name }} - EasyPlan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #0095f6;
            --secondary-color: #efefef;
            --bg-color: #ffffff;
            --text-color: #262626;
            --text-light: #8e8e8e;
            --border-color: #dbdbdb;
            --chat-bg: #f0f2f5;
            /* Cambiado a un gris más claro */
            --message-sent: #0095f6;
            --message-received: #ffffff;
            /* Cambiado a blanco puro */
            --online-status: #4ade80;
            --offline-status: #9ca3af;
        }

        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: var(--bg-color);
            color: var(--text-color);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .msger {
            width: 100%;
            max-width: 800px;
            height: 90vh;
            display: flex;
            flex-direction: column;
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            background-color: var(--bg-color);
        }

        .msger-header {
            padding: 16px;
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: var(--bg-color);
        }

        .msger-header-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 18px;
            font-weight: 500;
        }

        .msger-header-title i {
            color: var(--text-light);
        }

        .msger-header-options {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .msger-header-options button {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 14px;
        }

        .chatStatus {
            font-size: 14px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .chatStatus.online {
            color: var(--online-status);
        }

        .chatStatus.offline {
            color: var(--offline-status);
        }

        #chat-members {
            position: absolute;
            right: 20px;
            top: 60px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        #chat-members h3 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
        }

        #chat-members ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        #chat-members li {
            padding: 5px 0;
            font-size: 14px;
        }

        .msger-chat {
            flex: 1;
            padding: 15px;
            overflow-y: auto;
            background-color: var(--chat-bg);
            /* Eliminado completamente el fondo de patrón */
        }

        .msg {
            display: flex;
            margin-bottom: 15px;
        }

        .msg-img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-size: cover;
            background-position: center;
            margin-right: 10px;
            flex-shrink: 0;
        }

        .msg-bubble {
            max-width: 70%;
        }

        .msg-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .msg-info-name {
            color: #2d3748;
            /* Color más oscuro */
            font-weight: 600;
        }

        .msg-info-time {
            color: #718096;
            /* Color gris medio */
            font-size: 11px;
        }

        .msg-text {
            padding: 10px 15px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.4;
            word-wrap: break-word;
        }

        .left-msg .msg-text {
            background-color: var(--message-received);
            color: #000;
            /* Texto negro */
            border: 1px solid #e5e5e5;
            /* Borde sutil */
        }


        .right-msg {
            flex-direction: row-reverse;
        }

        .right-msg .msg-img {
            margin-right: 0;
            margin-left: 10px;
        }

        .right-msg .msg-text {
            background-color: var(--message-sent);
            color: #fff;
            /* Texto blanco */
        }

        .msger-inputarea {
            display: flex;
            padding: 12px;
            border-top: 1px solid var(--border-color);
            background-color: var(--bg-color);
        }

        .msger-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid var(--border-color);
            border-radius: 20px;
            font-size: 14px;
            outline: none;
            background-color: var(--secondary-color);
        }

        .msger-send-btn {
            margin-left: 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .typing {
            font-size: 13px;
            color: var(--text-light);
            font-style: italic;
            margin-left: 5px;
        }

        .typing-message {
            display: inline-flex;
            align-items: flex-end;
            background-color: var(--message-received);
            padding: 10px 15px;
            border-radius: 18px;
            border-bottom-left-radius: 4px;
            border: 1px solid #e5e5e5;
        }

        .typing-dots {
            display: flex;
            align-items: center;
            height: 20px;
        }

        .typing-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background-color: #333;
            margin: 0 2px;
            animation: typingAnimation 1.4s infinite ease-in-out;
        }

        .typing-dot:nth-child(1) {
            animation-delay: 0s;
        }

        .typing-dot:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-dot:nth-child(3) {
            animation-delay: 0.4s;
        }

        /* Botón de volver */
        .back-btn {
            background: none;
            border: none;
            color: var(--text-color);
            font-size: 18px;
            cursor: pointer;
            margin-right: 15px;
            padding: 5px;
            transition: color 0.2s;
        }

        .back-btn:hover {
            color: var(--primary-color);
        }

        /* Panel de miembros simplificado */
        .members-panel {
            position: absolute;
            top: 60px;
            right: 20px;
            background-color: var(--bg-color);
            border: 1px solid var(--border-color);
            border-radius: 8px;
            padding: 15px;
            z-index: 100;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            width: 250px;
        }

        .members-panel h3 {
            margin-top: 0;
            margin-bottom: 12px;
            font-size: 16px;
            color: var(--text-color);
        }

        .member-item {
            display: flex;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .member-item:last-child {
            border-bottom: none;
        }

        .member-avatar img {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            object-fit: cover;
            margin-right: 10px;
        }

        .member-name {
            flex: 1;
            font-size: 14px;
        }

        .member-role {
            font-size: 12px;
            padding: 2px 8px;
            border-radius: 12px;
            background: var(--secondary-color);
            color: var(--text-light);
        }

        @keyframes typingAnimation {

            0%,
            60%,
            100% {
                transform: translateY(0);
            }

            30% {
                transform: translateY(-4px);
            }
        }

        @media (max-width: 600px) {
            .msger {
                height: 100vh;
                border-radius: 0;
            }
        }
    </style>
</head>

<body>
    <section class="msger">
        <header class="msger-header">
            <button onclick="goBack()" class="back-btn">
                <i class="fas fa-arrow-left"></i>
            </button>
            <div class="msger-header-title">
                <i class="fas fa-comment-alt"></i>
                <span class="chatWith">
                    @if ($chat->type === 'organization')
                        {{ $chat->organization->name }}
                    @elseif($chat->type === 'project')
                        {{ $chat->project->name }}
                    @else
                        Chat Privado
                    @endif
                </span>
                <span class="typing" style="display:none;"></span>
            </div>
            <div class="msger-header-options">
                <button onclick="toggleMembers()" class="text-blue-600 underline">Ver Miembros</button>
                <span class="chatStatus offline"><i class="fas fa-globe"></i></span>
            </div>
            <div id="chat-members" class="members-panel" style="display: none;">
                <h3>Miembros del Chat</h3>
                <ul>
                    @foreach ($chat->users as $user)
                        <li class="member-item">
                            <div class="member-avatar">
                                <img src="{{ $user->avatar_url ?? 'https://w7.pngwing.com/pngs/981/645/png-transparent-default-profile-united-states-computer-icons-desktop-free-high-quality-person-icon-miscellaneous-silhouette-symbol-thumbnail.png' }}"
                                    alt="{{ $user->name }}">
                            </div>
                            <span class="member-name">{{ $user->name }}</span>
                            <span class="member-role">{{ $user->pivot->role }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </header>

        <div class="msger-chat"></div>

        <form class="msger-inputarea">
            <input type="text" placeholder="Enter your message..." class="msger-input">
            <input type="hidden" name="chatId" value="{{ $chat->id }}" id="hiddenInput">
            <button type="submit" class="msger-send-btn"><i class="fas fa-paper-plane"></i></button>
        </form>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <script src="{{ asset('js/chat.js') }}"></script>
    <script>
        function goBack() {
            if (window.history.length > 1) {
                window.history.back();
            } else {
                window.location.href = '/chats'; // Ruta por defecto
            }
        }

        // Función para mostrar/ocultar panel de miembros
        function toggleMembers() {
            const membersPanel = document.getElementById('chat-members');
            membersPanel.style.display = membersPanel.style.display === 'none' ? 'block' : 'none';
        }

        // Cerrar panel al hacer clic fuera
        document.addEventListener('click', (e) => {
            const membersPanel = document.getElementById('chat-members');
            const membersBtn = document.querySelector('.msger-header-options button');

            if (membersPanel.style.display === 'block' &&
                !membersPanel.contains(e.target) &&
                e.target !== membersBtn) {
                membersPanel.style.display = 'none';
            }
        });
    </script>
</body>

</html>
