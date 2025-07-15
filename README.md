# EasyPlan

**EasyPlan** es un sistema web desarrollado con Laravel y Vue/Alpine que permite gestionar organizaciones territoriales como juntas de vecinos. Incluye funcionalidades de autenticación, gestión de usuarios y un sistema de mensajería en tiempo real mediante Laravel WebSockets.

## 🚀 Tecnologías Usadas

- Laravel 10
- Laravel WebSockets
- Laravel Echo
- Alpine.js
- Vite
- Livewire
- MySQL
- Tailwind CSS

---

## 📦 Requisitos Previos

Antes de comenzar, asegúrate de tener instalado:

- PHP >= 8.2
- Composer
- Node.js y npm
- MySQL o MariaDB
- Opcional para pruebas de correo: MailHog o Mailtrap

---

## ⚙️ Instalación y Despliegue Local

Sigue estos pasos para levantar el proyecto localmente:

1. **Clona el repositorio:**

```bash
git clone https://github.com/ALEXJEYC/EasyPlan.git
cd EasyPlan
```

2. **Instala las dependencias de PHP y JavaScript:**

```bash
composer install
npm install
```

3. **Copia el archivo de entorno y configura tus variables:**

```bash
cp .env.example .env
```

Edita el archivo `.env` y asegúrate de configurar:

- Conexión a base de datos
- Variables de correo (MAIL_...)
- Pusher o configuración para WebSockets (si aplica)

4. **Ejecuta las migraciones:**

```bash
php artisan migrate
```

5. **Levanta los servidores de desarrollo:**

En tres terminales distintas, ejecuta:

```bash
php artisan serve
npm run dev
php artisan websockets:serve
```

---

## 👥 Crear Usuarios y Probar el Chat

1. Regístrate desde la vista **/register** (mínimo 2 usuarios).
2. Inicia sesión con uno de los usuarios.
3. Dirígete a `/chats/with/{user_id}` reemplazando `{user_id}` por el ID del segundo usuario para iniciar un chat privado.

---

## 💬 Sistema de Chat

Este proyecto incluye un sistema de mensajería en tiempo real utilizando:

- **Laravel WebSockets** para conexiones en tiempo real.
- **Laravel Echo** en el frontend para escuchar eventos.
- **Canales Privados** para la seguridad de los mensajes.

---

## 🧪 Correo de Pruebas

Puedes usar herramientas como [MailHog](https://github.com/mailhog/MailHog) o [Mailtrap](https://mailtrap.io/) para probar el envío de correos. Configura tus variables en el archivo `.env`:

```env
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
```

---

## 🧠 Desarrollo

El paso a paso del desarrollo de EasyPlan está descrito en los *commits* del repositorio, los cuales siguen una estructura clara y progresiva basada en metodología ágil (*Scrum*).

---

## 📄 Licencia

Este proyecto está bajo licencia MIT.

---

## ✨ Disfruta de la aplicación

Cualquier aporte, sugerencia o mejora es bienvenida. ¡Gracias por visitar **EasyPlan**!
