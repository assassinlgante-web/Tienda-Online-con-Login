-- datos_prueba_catalogo.sql
-- Inserta categorías y productos de ejemplo para probar el catálogo.
-- Ejecutar sobre la base db_tiendaonline ya creada.

USE db_tiendaonline;

-- Categorías
INSERT INTO CATEGORIA (cat_nombre) VALUES
  ('Ropa'),
  ('Tecnología'),
  ('Hogar');

-- Productos (cat_id se relaciona con el orden en que se insertaron arriba: 1=Ropa, 2=Tecnología, 3=Hogar)
INSERT INTO PRODUCTO (cat_id, pro_nombre, pro_descripcion, pro_precio, pro_stock, pro_imagen_url) VALUES
  (1, 'Polo básico algodón', 'Polo de algodón 100%, disponible en varios colores.', 35.00, 50, 'https://placehold.co/300x300?text=Polo'),
  (1, 'Casaca deportiva', 'Casaca ligera resistente al agua, ideal para entrenar.', 89.90, 20, 'https://placehold.co/300x300?text=Casaca'),
  (2, 'Audífonos inalámbricos', 'Audífonos bluetooth con cancelación de ruido básica.', 129.00, 15, 'https://placehold.co/300x300?text=Audifonos'),
  (2, 'Cargador rápido 20W', 'Cargador compatible con USB-C, carga rápida.', 45.00, 40, 'https://placehold.co/300x300?text=Cargador'),
  (3, 'Set de tazas x4', 'Set de 4 tazas de cerámica, 300ml cada una.', 39.90, 25, 'https://placehold.co/300x300?text=Tazas'),
  (3, 'Lámpara de escritorio LED', 'Lámpara regulable con 3 niveles de intensidad.', 59.00, 18, 'https://placehold.co/300x300?text=Lampara');
