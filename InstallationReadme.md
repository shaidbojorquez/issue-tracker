<p align="center"><img src="https://res.cloudinary.com/dtfbvvkyp/image/upload/v1566331377/laravel-logolockup-cmyk-red.svg" width="400"></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/d/total.svg" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://poser.pugx.org/laravel/framework/license.svg" alt="License"></a>
</p>

## Manual de instalación de API

- Nombre del host de la API:
    localhost
- Nombre del repositorio:
    shaidbojorquez/issue-tracker

- Nombre de la base de datos:
    issue_tracker

- Versión de PHP a Utilizar:
    Php-7.2.19


## PASOS

1. Primero clonar la api con git clone y el url del repositorio.
2. Moverse a la carpeta del proyecto clonado e ir a master.
3. Ejecuto composer install.
4. Crear base de datos issue_tracker.
5. Copiar .env.example en .env con cp .env.example .env Y modificar el .env con el nombre de la base de datos, usuario y contraseña
6. Ejecutar las migraciones php artisan migrate.
7. Ejecutar php artisan passport:install


## Para ejecutar pruebas funcionales
1. Crear base de datos test: issue_tracker_test.
2. Correr las migraciones para .env.testing: php artisan migrate --env=testing

