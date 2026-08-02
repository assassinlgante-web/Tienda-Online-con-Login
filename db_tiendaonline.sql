-- db_tiendaonline.sql
-- Base de datos completa del proyecto, siguiendo el Estándar de
-- Programación GSP-002-2026 (entidades en singular, prefijo de 3 letras
-- por atributo). Incluye soporte para login local Y login con Google.
-- Ejecutar completo crea la base desde cero.

DROP DATABASE IF EXISTS db_tiendaonline;
CREATE DATABASE db_tiendaonline;
USE db_tiendaonline;

-- Tabla USUARIO
CREATE TABLE USUARIO (
  usu_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_nombre VARCHAR(100) NOT NULL,
  usu_email VARCHAR(150) NOT NULL UNIQUE,
  usu_password VARCHAR(255) NULL,              -- NULL permitido: cuentas de Google no tienen password propio
  usu_rol ENUM('cliente', 'admin') DEFAULT 'cliente',
  usu_proveedor ENUM('local', 'google') NOT NULL DEFAULT 'local',
  usu_google_id VARCHAR(255) NULL UNIQUE,       -- identificador único que Google asigna a la cuenta
  usu_fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tabla CATEGORIA
CREATE TABLE CATEGORIA (
  cat_id INT AUTO_INCREMENT PRIMARY KEY,
  cat_nombre VARCHAR(80) NOT NULL UNIQUE
);

-- Tabla PRODUCTO
CREATE TABLE PRODUCTO (
  pro_id INT AUTO_INCREMENT PRIMARY KEY,
  cat_id INT,
  pro_nombre VARCHAR(150) NOT NULL,
  pro_descripcion TEXT,
  pro_precio DECIMAL(10,2) NOT NULL,
  pro_stock INT DEFAULT 0,
  pro_imagen_url VARCHAR(255),
  pro_creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (cat_id) REFERENCES CATEGORIA(cat_id)
);

-- Tabla DETALLE_CARRITO
CREATE TABLE DETALLE_CARRITO (
  dca_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_id INT NOT NULL,
  pro_id INT NOT NULL,
  dca_cantidad INT NOT NULL DEFAULT 1,
  dca_agregado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usu_id) REFERENCES USUARIO(usu_id),
  FOREIGN KEY (pro_id) REFERENCES PRODUCTO(pro_id)
);

-- Tabla PEDIDO
CREATE TABLE PEDIDO (
  ped_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_id INT NOT NULL,
  ped_total DECIMAL(10,2) NOT NULL,
  ped_estado ENUM('pendiente', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
  ped_creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usu_id) REFERENCES USUARIO(usu_id)
);

-- Tabla DETALLE_PEDIDO
CREATE TABLE DETALLE_PEDIDO (
  dpe_id INT AUTO_INCREMENT PRIMARY KEY,
  ped_id INT NOT NULL,
  pro_id INT NOT NULL,
  dpe_cantidad INT NOT NULL,
  dpe_precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (ped_id) REFERENCES PEDIDO(ped_id),
  FOREIGN KEY (pro_id) REFERENCES PRODUCTO(pro_id)
);
