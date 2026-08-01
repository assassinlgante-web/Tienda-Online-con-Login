-- schema_estandar.sql
-- Base de datos corregida siguiendo el Estándar de Programación GSP-002-2026
-- Ejecutar esto reemplaza las tablas anteriores con los nombres correctos.

DROP DATABASE IF EXISTS proyecto_ecommerce;
CREATE DATABASE db_tiendaonline;
USE db_tiendaonline;


CREATE TABLE USUARIO (
  usu_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_nombre VARCHAR(100) NOT NULL,
  usu_email VARCHAR(150) NOT NULL UNIQUE,
  usu_password VARCHAR(255) NOT NULL,
  usu_rol ENUM('cliente', 'admin') DEFAULT 'cliente',
  usu_fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE CATEGORIA (
  cat_id INT AUTO_INCREMENT PRIMARY KEY,
  cat_nombre VARCHAR(80) NOT NULL UNIQUE
);


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


CREATE TABLE DETALLE_CARRITO (
  dca_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_id INT NOT NULL,
  pro_id INT NOT NULL,
  dca_cantidad INT NOT NULL DEFAULT 1,
  dca_agregado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usu_id) REFERENCES USUARIO(usu_id),
  FOREIGN KEY (pro_id) REFERENCES PRODUCTO(pro_id)
);


CREATE TABLE PEDIDO (
  ped_id INT AUTO_INCREMENT PRIMARY KEY,
  usu_id INT NOT NULL,
  ped_total DECIMAL(10,2) NOT NULL,
  ped_estado ENUM('pendiente', 'enviado', 'entregado', 'cancelado') DEFAULT 'pendiente',
  ped_creado_en TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (usu_id) REFERENCES USUARIO(usu_id)
);


CREATE TABLE DETALLE_PEDIDO (
  dpe_id INT AUTO_INCREMENT PRIMARY KEY,
  ped_id INT NOT NULL,
  pro_id INT NOT NULL,
  dpe_cantidad INT NOT NULL,
  dpe_precio_unitario DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (ped_id) REFERENCES PEDIDO(ped_id),
  FOREIGN KEY (pro_id) REFERENCES PRODUCTO(pro_id)
);
