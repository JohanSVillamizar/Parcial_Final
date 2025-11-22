# Parcial Final

## Requisitos Previos

- PHP >= 8.x
- Composer
- Node.js y npm
- PostgreSQL instalado y funcionando

## Instalación

1. Clona el repositorio:

2. Instala Jetstream con Inertia y Vue:

```bash
composer require laravel/jetstream
php artisan jetstream:install inertia
npm install
npm install @vitejs/plugin-vue@^6 --save-dev
npm install vue-cal
```

3. Configura PostgreSQL editando el archivo `.env` en la raíz del proyecto:

```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=tu_BD
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

```

4. Ejecuta las migraciones para crear las tablas necesarias:

```bash
php artisan migrate
```

5. Ejecuta los seeders

```bash
php artisan db:seed
```


