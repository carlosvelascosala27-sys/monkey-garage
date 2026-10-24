# monkey-garage
Sistema de gestión para taller mecánico (PHP + AJAX) - Práctica intermodular AD/DI

Práctica evaluable intermodular de **Acceso a Datos (AD)** y **Desarrollo de Interfaces (DI)**.
2º CFGS Desarrollo de Aplicaciones Multiplataforma (DAM).

## Descripción

Aplicación web para modernizar la gestión de un taller mecánico: clientes, vehículos, citas, reparaciones y facturación. Funciona sin recargas de página, comunicando el frontend con el backend mediante `fetch()` y respuestas JSON.

## Roles

- **Administrador:** gestión de usuarios y métricas del taller.
- **Oficina:** clientes, vehículos, citas y facturas.
- **Mecánico:** hoja de trabajo, estado de reparaciones, horas y materiales.
- **Cliente:** registro, sus vehículos, cita previa y consulta de reparaciones y facturas.

## Tecnologías

- PHP (POO, PDO con patrón Singleton, sentencias preparadas)
- MySQL / MariaDB
- HTML5, CSS3 y JavaScript (fetch / async-await)
- Autenticación por token `auth_key`
- Git y GitHub Projects para la gestión del proyecto

## Estructura del proyecto

| Carpeta | Contenido |
|---|---|
| `/modelo` | Clases PHP (Conexion, Usuario, Cliente, Vehiculo, Cita, Factura) |
| `/servicios` | Endpoints `*_sw.php` que devuelven JSON |
| `/vistas` | Páginas HTML y CSS |
| `/js` | Controladores de vista (fetch y DOM) |
| `/db` | Scripts SQL |
| `/docs` | Diagrama E-R y memoria |

## Instalación

> Pendiente de completar cuando esté lista la base de datos.

1. Clonar el repositorio.
2. Importar el script de `/db` en MySQL/MariaDB.
3. Copiar `config.example.php` como `config.php` y poner las credenciales.
4. Servir la carpeta con Apache/XAMPP y abrir el navegador.

## Gestión del proyecto

- Tablero Kanban: pestaña **Projects** de este repositorio.
- Cada semana del calendario es un sprint (milestone).
- Flujo de trabajo: una rama por tarea (`feature/<nº>-nombre`) y Pull Request a `main`.

## Autor

Carlos Velasco · Profesor: Víctor Sarabia
