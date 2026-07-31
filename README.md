# Tienda Online con Login — Proyecto de Equipo

Proyecto de práctica: tienda online completa con login, catálogo, carrito y panel de administración. Construido en equipo para aprender a colaborar en GitHub con ramas y Pull Requests.

Repositorio: https://github.com/assassinlgante-web/Tienda-Online-con-Login

## Stack
- Frontend: HTML, CSS, JavaScript
- Backend: Node.js + Express
- Base de datos: MySQL

## Módulos y responsables

| Módulo | Responsable | Rama | Estado |
|--------|-------------|------|--------|
| Login (autenticación) | @usuario1 | `login` | 🔲 Pendiente |
| Catálogo de productos | @usuario2 | `catalogo` | 🔲 Pendiente |
| Carrito y checkout | @usuario3 | `carrito` | 🔲 Pendiente |
| Panel de administración | @usuario4 | `admin` | 🔲 Pendiente |
| Diseño + integración + deploy | @usuario5 | `diseno` | 🔲 Pendiente |

## Cómo trabajar en este repo

1. Clona el repositorio:
   ```
   git clone https://github.com/assassinlgante-web/Tienda-Online-con-Login.git
   ```
2. Entra a la carpeta:
   ```
   cd Tienda-Online-con-Login
   ```
3. Crea tu propia rama (no trabajes directo en `main`):
   ```
   git checkout -b nombre-de-tu-modulo
   ```
4. Programa tu módulo. Guarda tu avance seguido:
   ```
   git add .
   git commit -m "descripción clara del cambio"
   git push origin nombre-de-tu-modulo
   ```
5. Cuando tu módulo funcione, abre un Pull Request en GitHub hacia `main` para que el equipo lo revise antes de unirlo.
6. Antes de empezar a programar cada día, hacer `git pull` para traer los últimos cambios del equipo.

## Reglas del equipo

- Nadie sube directo a `main`.
- Un commit, un cambio claro (evitar "arreglos varios").
- Avisar en el grupo si van a tocar el mismo archivo que otro.
- Revisar el Pull Request de un compañero antes de aprobarlo, no solo por cortesía.

## Estructura de carpetas

```
/frontend      → HTML, CSS, JS del sitio
/backend       → servidor Node.js + Express
  /routes      → rutas de la API (login, productos, carrito, etc.)
  /middleware  → funciones de protección de rutas
/database      → scripts SQL (esquema de tablas)
```

## Objetivo del proyecto

Aprender a trabajar en equipo con Git y GitHub como en un entorno profesional, y terminar con un proyecto completo que sirva como portafolio para aplicar a trabajos freelance.
