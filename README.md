# Monkey Garage
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

## Base de datos

La base de datos `monkey_garage` tiene 7 tablas:

- **usuario**: cuentas de acceso de todos los perfiles. El campo `login` es el email del cliente o el código del personal (por ejemplo `MC01`), y el campo `rol` decide qué ve cada uno.
- **cliente**: datos personales del cliente (DNI como clave primaria).
- **vehiculo**: coches de los clientes (matrícula como clave primaria). Un cliente puede tener varios vehículos.
- **cita**: citas y reparaciones de un vehículo, con el mecánico asignado, el estado de la reparación y las horas trabajadas.
- **factura**: factura de una cita finalizada, con mano de obra, repuestos, IVA y total.
- **repuesto**: catálogo de piezas, con precio y stock.
- **cita_repuesto**: piezas usadas en cada cita, con cantidad y precio en el momento de la reparación.

## Estructura del proyecto

| Carpeta | Contenido |
|---|---|
| `backend/model` | Clases PHP (Conexion, Usuario, Cliente, Vehiculo, Cita, Factura, Repuesto) |
| `backend` | Endpoints `*_sw.php` que devuelven JSON |
| `frontend` | Páginas HTML, CSS (`css/`) y JavaScript (`js/`) |
| `db` | Scripts SQL: estructura (`monkey_garage.sql`) y datos de prueba (`datos_prueba.sql`) |
| `docs` | Memoria y documentación |

## Instalación

1. Arrancar **Apache** y **MySQL** desde el panel de XAMPP.
2. Clonar el repositorio dentro de `C:\xampp\htdocs`.
3. Importar `db/monkey_garage.sql` en MySQL (phpMyAdmin → Importar, o SQLyog → Tools → Execute SQL Script). Crea la base de datos `monkey_garage` con sus 7 tablas.
4. Importar `db/datos_prueba.sql` de la misma forma para cargar datos de ejemplo (opcional).
5. Copiar `config.example.php` como `config.php` y poner los datos de conexión. Este archivo no se sube a GitHub.

## Gestión del proyecto

- Tablero Kanban: pestaña **Projects** de este repositorio.
- Cada semana del calendario es un sprint (milestone).
- Flujo de trabajo: una rama por issue (`is<número>`), que se fusiona en `develop`. Al terminar cada día, `develop` se revisa y se fusiona en `main`.

## Autor

Carlos Velasco · Profesor: Víctor Sarabia