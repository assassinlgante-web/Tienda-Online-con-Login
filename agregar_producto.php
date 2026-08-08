<?php
// agregar_producto.php
// Formulario para crear un producto nuevo. Protegido: solo usuarios con
// sesión activa pueden verlo (mismo patrón que perfil.php).

session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$errores = [];
$exito = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nombre = trim($_POST["nombre"] ?? "");
    $descripcion = trim($_POST["descripcion"] ?? "");
    $precio = $_POST["precio"] ?? "";
    $stock = $_POST["stock"] ?? "";
    $categoria_id = $_POST["categoria_id"] ?? "";
    $imagen_url = trim($_POST["imagen_url"] ?? "");

    if (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 letras.";
    }
    if (!is_numeric($precio) || $precio <= 0) {
        $errores[] = "El precio debe ser un número mayor a 0.";
    }
    if (!is_numeric($stock) || $stock < 0) {
        $errores[] = "El stock debe ser un número igual o mayor a 0.";
    }
    if ($categoria_id === "") {
        $errores[] = "Selecciona una categoría.";
    }

    if (empty($errores)) {
        $stmt = mysqli_prepare($conexion, "INSERT INTO PRODUCTO (cat_id, pro_nombre, pro_descripcion, pro_precio, pro_stock, pro_imagen_url) VALUES (?, ?, ?, ?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "issdis", $categoria_id, $nombre, $descripcion, $precio, $stock, $imagen_url);
        // Tipos: i=categoria_id, s=nombre, s=descripcion, d=precio(decimal), i=stock, s=imagen_url

        if (mysqli_stmt_execute($stmt)) {
            $exito = true;
        } else {
            $errores[] = "Error al guardar: " . mysqli_error($conexion);
        }
        mysqli_stmt_close($stmt);
    }
}

$categorias = mysqli_query($conexion, "SELECT cat_id, cat_nombre FROM CATEGORIA ORDER BY cat_nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Agregar producto</title>
<style>
  body{ font-family:sans-serif; background:#F7F5F1; display:flex; justify-content:center; padding:50px 20px; }
  .box{ background:#fff; border:1px solid #ddd; border-radius:10px; padding:30px; width:100%; max-width:460px; }
  label{ display:block; font-size:0.85rem; font-weight:600; margin:12px 0 4px; }
  input, select, textarea{ width:100%; padding:10px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; font-family:inherit; }
  textarea{ resize:vertical; min-height:70px; }
  button{ width:100%; padding:12px; background:#14213D; color:#fff; border:none; border-radius:6px; cursor:pointer; margin-top:18px; }
  .error{ background:#fdecea; color:#c1121f; padding:10px; border-radius:6px; margin-bottom:12px; font-size:0.9rem; }
  .exito{ background:#e8f6f3; color:#2A9D8F; padding:10px; border-radius:6px; margin-bottom:12px; font-size:0.9rem; }
  a{ color:#14213D; }
</style>
</head>
<body>
<div class="box">
  <h2>Agregar producto</h2>

  <?php if (!empty($errores)): ?>
    <div class="error">
      <?php foreach ($errores as $e): ?><?= htmlspecialchars($e) ?><br><?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if ($exito): ?>
    <div class="exito">✓ Producto agregado correctamente.</div>
    <p><a href="productos.php">Ver catálogo</a> · <a href="agregar_producto.php">Agregar otro</a></p>
  <?php else: ?>
    <form method="POST" action="agregar_producto.php">
      <label>Nombre del producto</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">

      <label>Descripción</label>
      <textarea name="descripcion"><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>

      <label>Precio (S/.)</label>
      <input type="number" step="0.01" name="precio" value="<?= htmlspecialchars($_POST['precio'] ?? '') ?>">

      <label>Stock (unidades disponibles)</label>
      <input type="number" name="stock" value="<?= htmlspecialchars($_POST['stock'] ?? '') ?>">

      <label>Categoría</label>
      <select name="categoria_id">
        <option value="">Selecciona una categoría</option>
        <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
          <option value="<?= $cat["cat_id"] ?>"><?= htmlspecialchars($cat["cat_nombre"]) ?></option>
        <?php endwhile; ?>
      </select>

      <label>URL de la imagen</label>
      <input type="text" name="imagen_url" placeholder="https://..." value="<?= htmlspecialchars($_POST['imagen_url'] ?? '') ?>">

      <button type="submit">Guardar producto</button>
    </form>
  <?php endif; ?>
</div>
</body>
</html>
