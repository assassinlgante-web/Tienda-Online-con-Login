# Guía de instalación — Proyecto Tienda Online con Login

Sigue estos pasos en orden la primera vez que trabajes en el proyecto. Toma
entre 30 y 45 minutos si es tu primera vez instalando estas herramientas.

## 1. Instalar Git

1. Descarga desde: https://git-scm.com/download/win
2. Instala dejando todas las opciones por defecto.
3. Abre "Git Bash" (se instaló junto con Git) y configura tu identidad:
   ```
   git config --global user.name "Tu Nombre"
   git config --global user.email "tu-correo-de-github@gmail.com"
   ```
   (usa el mismo correo con el que te registraste/conectaste a GitHub, así tus
   commits se vinculan a tu perfil)

## 2. Instalar XAMPP (trae PHP y Apache listos)

1. Descarga desde: https://www.apachefriends.org
2. Al instalar, puedes DESMARCAR el componente MySQL (vamos a usar MySQL
   Workbench por separado, no hace falta duplicar).
3. Abre "XAMPP Control Panel" y dale "Start" a **Apache** cada vez que vayas
   a trabajar en el proyecto (no hace falta dejarlo prendido todo el tiempo).
4. Verifica que PHP funcione: abre `cmd` y escribe `php -v`. Si dice que no
   reconoce el comando, hay que agregar `C:\xampp\php` a las Variables de
   Entorno (PATH) de Windows — avisa en el grupo si te pasa esto.

## 3. Instalar MySQL Workbench

1. Descarga desde: https://dev.mysql.com/downloads/workbench/
2. Al instalar MySQL Server (si no lo tienes ya), anota bien el password que
   le pongas a `root` — lo vas a necesitar más adelante.

## 4. Clonar el repositorio

En Git Bash:
```
cd /c/xampp/htdocs
git clone https://github.com/assassinlgante-web/Tienda-Online-con-Login.git
```

## 5. Crear tu base de datos local

1. Abre MySQL Workbench, conéctate a tu servidor local.
2. Abre una consulta nueva (Ctrl+T).
3. Abre el archivo `db_tiendaonline.sql` que está dentro de la carpeta que
   acabas de clonar, copia todo su contenido, pégalo en Workbench, y
   ejecútalo (ícono del rayo ⚡).
4. Esto crea la base `db_tiendaonline` con las 6 tablas del proyecto, vacía
   (sin datos, cada quien la llena probando en su computadora).

## 6. Crear tu archivo de conexión personal

1. Dentro de la carpeta del proyecto, copia el archivo `conexion.example.php`.
2. Pega la copia en la misma carpeta y renómbrala a `conexion.php`.
3. Ábrela con el Bloc de notas o VS Code, y reemplaza `TU_PASSWORD_AQUI` por
   tu propio password de MySQL.
4. Guarda. Este archivo es solo tuyo — nunca se sube a GitHub (ya está en
   `.gitignore`), porque cada quien tiene un password distinto en su compu.

## 7. Probar que todo funciona

1. Confirma que Apache esté "Running" en XAMPP Control Panel.
2. Abre tu navegador y entra a:
   ```
   http://localhost/Tienda-Online-con-Login/registro.php
   ```
3. Crea un usuario de prueba. Si te dice "Cuenta creada correctamente",
   todo quedó bien configurado.

## Sobre trabajar en equipo

- **Cada quien tiene su propia base de datos**, separada, solo en su
  computadora — es normal que no veas los usuarios que registró un
  compañero, ni él los tuyos. Es solo para practicar y probar cada módulo
  de forma independiente.
- Cuando el proyecto esté listo para subir a internet (deploy), ahí sí va
  a existir UNA sola base de datos real y compartida, en un servidor en la
  nube — pero eso es un paso posterior, no ahora.
- Antes de programar, crea tu propia rama:
  ```
  git checkout -b nombre-de-tu-modulo
  ```
- Sube tus cambios con `git add .`, `git commit -m "..."`, `git push origin
  nombre-de-tu-modulo`, y abre un Pull Request para que el equipo lo revise
  antes de unirlo a `main`.

## Estándar de nomenclatura

Este proyecto sigue el estándar `GSP-002-2026` (documento
`Estandar_Programacion_TiendaOnline.pdf` en el repo): tablas en singular y
MAYÚSCULAS (`USUARIO`, `PRODUCTO`...), columnas con prefijo de 3 letras de
la entidad (`usu_nombre`, `pro_precio`...). Revisa ese PDF antes de crear
tablas o columnas nuevas para tu módulo.
