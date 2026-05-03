# Sistema de préstamo de libros

## Descripción

Aplicación web desarrollada en PHP para la gestión de préstamos de libros entre usuarios. Permite registrar libros, solicitar préstamos, gestionar solicitudes y participar mediante comentarios y valoraciones.

---

## Objetivo

Desarrollar una plataforma funcional e intuitiva que facilite el intercambio de libros entre usuarios y fomente la interacción dentro de la aplicación.

---

## Instalación paso a paso (XAMPP)

### 1. Clonar el repositorio

Accede a la carpeta `htdocs` de XAMPP y clona el proyecto:

```bash
cd C:\xampp\htdocs
git clone https://github.com/Aleja/proyecto.git
```

---

### 2. Cambiar nombre de la carpeta

Renombra la carpeta del proyecto a `prestamo_de_libros`:

```bash
mv proyecto prestamo_de_libros
```

---

### 3. Instalar dependencias de PHP

Accede a la carpeta `app` del proyecto y ejecuta:

```bash
cd prestamo_de_libros/app
composer install
```

---

### 4. Configurar variables de entorno

Crea una copia del archivo de ejemplo:

```bash
cp .env.example .env
```

En este archivo solo es necesario configurar las variables relacionadas con Google (OAuth, API, etc.).

> ⚠️ La configuración de la base de datos se encuentra en el archivo `config.json`.

---

### 5. Configurar base de datos

- Crear una base de datos en MySQL (por ejemplo: `prestamo_de_libros`)
- Importar el archivo:

```
database/database.sql
```

---

### 6. Ejecutar el proyecto

Iniciar Apache y MySQL desde XAMPP y acceder a:

```
http://localhost/prestamo_de_libros/app/public
```

---

## API y Swagger

La API REST está disponible en:

```
http://localhost/prestamo_de_libros/api/swagger
```

Desde ahí se pueden probar los endpoints de forma interactiva.

---

### Modificar Swagger

La documentación de la API se define en el archivo OpenAPI, ubicado en:

```
/api/swagger/
```

Este archivo permite modificar los endpoints, parámetros y estructura de la API.

---

## Uso básico

1. Registrarse o iniciar sesión
2. Añadir libros
3. Solicitar préstamos
4. Gestionar solicitudes
5. Valorar y comentar libros

---

## Notas

- El proyecto está preparado para ejecutarse en entorno local con XAMPP
- La base de datos utiliza claves foráneas para garantizar integridad
- Algunas funcionalidades requieren autenticación

---

## Autor

Proyecto desarrollado como trabajo de grado.
