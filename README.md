# Pharmacovigilance Alert System

Este proyecto es un módulo de gestión de alertas de farmacovigilancia para farmacias magistrales. El sistema permite filtrar órdenes de compra por lote y rango de fechas para notificar a los clientes sobre posibles riesgos asociados a un medicamento.

### Tecnologías utilizadas
- **Backend:** Laravel 13 / PHP 8.4
- **Frontend:** Vue.js 3.5 (Composition API)
- **Base de Datos:** MariaDB (vía XAMPP)
- **Autenticación:** Laravel Sanctum

## Prerrequisitos

Para ejecutar este proyecto, es necesario tener instalado lo siguiente:

- [PHP 8.4+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/download/)
- [Node.js & NPM](https://nodejs.org/)
- [XAMPP](https://www.apachefriends.org/download.html) (MariaDB)
- [Git](https://git-scm.com/downloads)

---

## Instalación y Configuración

Sigue estos pasos para correr el proyecto localmente:

1. **Dependencias:**
   ```bash
   composer install
   npm install
   npm run build
   ```

2. **Configuración del Entorno:**
   Para que Laravel funcione, necesita un archivo `.env` (donde se guarda la configuración local). Copia el archivo de ejemplo y genera la clave de seguridad única para tu sesión:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   **Configuración de Base de Datos:**
   Crea una base de datos vacía llamada `pharmacovigilance` en tu MySQL/MariaDB (vía XAMPP) y ajusta estas líneas en tu nuevo archivo `.env`:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pharmacovigilance
   DB_USERNAME=root
   DB_PASSWORD=
   ```

   **Configuración de Correo (Mailer):**
   Para la demostración, el sistema está configurado para escribir los correos en los logs (`storage/logs/laravel.log`). Si deseas usar un servidor de pruebas como **Mailtrap**, puedes ajustar estas variables en el `.env`:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=sandbox.smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=tu_usuario
   MAIL_PASSWORD=tu_password
   MAIL_FROM_ADDRESS="alertas@farmacia.com"
   MAIL_FROM_NAME="Farmacovigilancia"
   ```

3. **Base de Datos y Seeders:**
   He preparado seeders con datos de prueba (incluyendo el lote `951357` solicitado) para que la demo funcione de inmediato.
   ```bash
   php artisan migrate:fresh --seed
   ```

4. **Servidor:**
   ```bash
   php artisan serve
   ```

---

## Consideraciones Técnicas

Durante el desarrollo tomé las siguientes decisiones para asegurar la calidad y el rendimiento:

- **Arquitectura de SPA:** Decidí implementar una arquitectura de **SPA (Single Page Application)** utilizando Vue Router en lugar de un enfoque híbrido de Blade + Vue. Esto garantiza una experiencia de usuario mucho más fluida sin recargas de página, además de mantener un backend totalmente desacoplado mediante una **API REST**, facilitando futuras integraciones.
- **Optimización de consultas:** Utilicé *Eager Loading* (`with()`) en los controladores de las órdenes para evitar el problema de N+1, ya que las órdenes tienen relaciones pesadas con clientes y medicinas.
- **Seguridad en el Frontend:** Implementé *Navigation Guards* en Vue Router. Esto evita que un usuario no autenticado pueda ver el dashboard o que un usuario logueado regrese a la pantalla de login por error.
- **Trazabilidad:** Además de registrar las alertas en la base de datos, configuré un sistema de logs que registra quién disparó la alerta y a qué cliente se notificó.
- **Base de Datos:** Utilicé **MariaDB de XAMPP** ya que, al ser un fork de MySQL, tiene una excelente compatibilidad con Laravel y es mucho más intuitivo para tratar con la base de datos a nivel local. Esto facilita el desarrollo frente a un MySQL puro que puede resultar más rígido y menos intuitivo visualmente para entornos rápidos de prueba.
- **Alertas Masivas:** Implementé una funcionalidad de "Bulk Action" que permite seleccionar varias órdenes y disparar las alertas de un solo clic, mejorando la productividad del usuario.

## Credenciales de prueba
- **Usuario:** `admin` (o el configurado en su DB)
- **Password:** `password123`
