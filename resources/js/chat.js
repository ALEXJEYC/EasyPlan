const msgerForm = get(".msger-inputarea");
const msgerInput = get(".msger-input");
const msgerChat = get(".msger-chat");
const PERSON_IMG = "https://w7.pngwing.com/pngs/981/645/png-transparent-default-profile-united-states-computer-icons-desktop-free-high-quality-person-icon-miscellaneous-silhouette-symbol-thumbnail.png";
const chatWith = get(".chatWith");
const chatStatus = get(".chatStatus");
const chatId = get("#hiddenInput").value;
let authUser;
let typingTimer = false;
let lastTypingTime = 0;

// Indicador de typing
let typingIndicator = null;
let isTypingIndicatorVisible = false;

window.onload = function () {
    axios.get('/auth/user')
        .then(r => {
            authUser = r.data.authUser;
        })
        .then(() => {
        axios.get(`/chats/${chatId}/get-user`)
            .then(r => {
            const otherUsers = r.data.users.filter(user => user.id != authUser.id);
            
            // Solo actualiza si es chat 1:1 (exactamente 1 otro usuario)
            if (otherUsers.length === 1) {
                chatWith.innerHTML = otherUsers[0].name;
            }
            // Para grupos (organización/proyecto): NO hagas nada (mantén título inicial)
            })
        })
        .then(() => {
            axios.get(`/chats/${chatId}/get-messages`)
                .then(r => {
                    appendMessages(r.data.messages);
                })
        })
        .then(() => {
            Echo.join(`chats.${chatId}`)
                .listen('MessageSent', (e) => {
                    // Ocultar el indicador de typing cuando llega un mensaje nuevo
                    hideTypingIndicator();
                    appendMessage(
                        e.message.user.name,
                        PERSON_IMG,
                        'left',
                        e.message.content,
                        formatDate(new Date(e.message.created_at))
                    );
                })
                .here(users => {
                    let result = users.filter(user => user.id != authUser.id)
                    if (result)
                        chatStatus.classList = 'chatStatus online';
                })
                .joining(user => {
                    if (user.id != authUser.id)
                        chatStatus.classList = 'chatStatus online';
                })
                .leaving(user => {
                    if (user.id != authUser.id)
                        chatStatus.classList = 'chatStatus offline';
                })
                .listenForWhisper('typing', e => {
                    // Solo mostrar si ha habido actividad reciente (últimos 3 segundos)
                    if (Date.now() - lastTypingTime < 3000) {
                        if (e > 0) {
                            showTypingIndicator();
                        } else {
                            hideTypingIndicator();
                        }
                    }
                })
        })
}

msgerForm.addEventListener("submit", event => {
    event.preventDefault();

    const msgText = msgerInput.value;
    if (!msgText) return;

    // Ocultar el indicador de typing antes de enviar
    hideTypingIndicator();
    // Enviar evento de que dejó de escribir
    sendTypingEvent(false);

    axios.post('/message/sent', {
        message: msgText,
        chat_id: chatId
    })
        .then(r => {
            appendMessage(
                r.data.user.name,
                PERSON_IMG,
                'right',
                r.data.content,
                formatDate(new Date(r.data.created_at))
            );
        })
        .catch(e => console.log(e))

    msgerInput.value = "";
});

// Utils
function get(selector, root = document) {
    return root.querySelector(selector);
}

function appendMessage(name, img, side, text, date) {
    // Si hay un indicador de typing visible, lo removemos temporalmente
    if (isTypingIndicatorVisible && typingIndicator) {
        typingIndicator.remove();
        isTypingIndicatorVisible = false;
    }

    const msgHTML = `
        <div class="msg ${side}-msg">
            <div class="msg-img" style="background-image: url(${img})"></div>
            <div class="msg-bubble">
                <div class="msg-info">
                    <div class="msg-info-name">${name}</div>
                    <div class="msg-info-time">${date}</div>
                </div>
                <div class="msg-text">${text}</div>
            </div>
        </div>
    `;

    msgerChat.insertAdjacentHTML("beforeend", msgHTML);
    
    // Si habíamos removido el indicador, lo volvemos a mostrar
    if (typingIndicator) {
        msgerChat.appendChild(typingIndicator);
        isTypingIndicatorVisible = true;
    }
    
    scrollToBottom();
}

function appendMessages(messages) {
    let side = 'left';

    messages.forEach(message => {
        side = (message.user_id == authUser.id) ? 'right' : 'left';
        appendMessage(
            message.user.name,
            PERSON_IMG,
            side,
            message.content,
            formatDate(new Date(message.created_at))
        )
    })
}

function formatDate(date) {
    const d = date.getDate();
    const mo = date.getMonth() + 1;
    const y = date.getFullYear();
    const h = "0" + date.getHours();
    const m = "0" + date.getMinutes();

    return `${d}/${mo}/${y} ${h.slice(-2)}:${m.slice(-2)}`;
}

function scrollToBottom() {
    msgerChat.scrollTop = msgerChat.scrollHeight;
}

function sendTypingEvent(isTyping = true) {
    lastTypingTime = Date.now();
    typingTimer = true;
    Echo.join(`chats.${chatId}`)
        .whisper('typing', isTyping ? 1 : 0);
}

function showTypingIndicator() {
    if (isTypingIndicatorVisible) return;
    
    // Crear el elemento de typing si no existe
    if (!typingIndicator) {
        const typingHTML = `
            <div class="msg left-msg" id="typing-indicator">
                <div class="msg-img" style="background-image: url(${PERSON_IMG})"></div>
                <div class="msg-bubble">
                    <div class="typing-message">
                        <div class="typing-dots">
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                            <div class="typing-dot"></div>
                        </div>
                    </div>
                </div>
            </div>
        `;
        msgerChat.insertAdjacentHTML("beforeend", typingHTML);
        typingIndicator = document.getElementById('typing-indicator');
    } else {
        typingIndicator.style.display = 'flex';
    }
    
    isTypingIndicatorVisible = true;
    scrollToBottom();
}

function hideTypingIndicator() {
    if (typingIndicator && isTypingIndicatorVisible) {
        typingIndicator.style.display = 'none';
        isTypingIndicatorVisible = false;
    }
}

// Manejo de eventos de typing mejorado
msgerInput.addEventListener('input', () => {
    sendTypingEvent(true);
});

msgerInput.addEventListener('blur', () => {
    sendTypingEvent(false);
    hideTypingIndicator();
});

// Verificar periódicamente si el usuario dejó de escribir
setInterval(() => {
    if (typingTimer && Date.now() - lastTypingTime > 3000) {
        sendTypingEvent(false);
        hideTypingIndicator();
        typingTimer = false;
    }
}, 1000);