# Chat Application
## Laravel Websockets y Laravel Echo

**Instrucciones para Despliegue Local**

- Ejecuta en consola en la raiz del proyecto

```bash
composer install && npm install && php artisan migrate
```

- Configura las variables de entorno en tu archivo **.env**
Este mismo lo duplicas de **.env.example**

```

```

- Correr los servidores

```bash
php artisan serve
npm run dev
php artisan websockets:serve
```

- Asegurar las credenciales de Mails en el archivo .env (puede optar por MailHog, mailtrap, entre otros)
- Crear los usuarios desde la vista **Register**, mínimo 2 users.
- Para crear una sala de chat ingrese a la siguiente ruta: /chats/with/{user_id}

## Disfruta de la aplicación

_el paso a paso del desarrollo de la aplicación está descrita en los commits del repositorio_
#   E a s y P l a n  
 