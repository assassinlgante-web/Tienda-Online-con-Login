<?php
// perfil.php
// Esta es una página PROTEGIDA: solo se puede ver si hay una sesión activa.
// Este mismo patrón es el que usarán los módulos de Carrito y Admin.
session_start();

// Si no existe usuario_id en la sesión, significa que nadie inició sesión.
// Lo mandamos de vuelta al login.
if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}

$es_admin = ($_SESSION["usuario_rol"] ?? "") === "admin";
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi perfil</title>
<style>
  body{ font-family:sans-serif; background:#F7F5F1; display:flex; justify-content:center; padding:60px 20px; }
  .box{ background:#fff; border:1px solid #ddd; border-radius:10px; padding:30px; width:100%; max-width:420px; }
  a.logout{ display:inline-block; margin-top:16px; color:#c1121f; }
  a.link{ color:#14213D; }
  .admin-box{ margin-top:20px; padding-top:20px; border-top:1px solid #eee; }
  .admin-box h3{ font-size:0.9rem; color:#5B6472; text-transform:uppercase; letter-spacing:0.03em; margin-bottom:10px; }
  .admin-box p{ color:#5B6472; font-size:0.85rem; }
</style>
</head>
<body>
<div class="box">
  <h2>¡Bienvenido, <?= htmlspecialchars($_SESSION["usuario_nombre"]) ?>!</h2>
  <p>Tu rol es: <strong><?= htmlspecialchars($_SESSION["usuario_rol"]) ?></strong></p>
  <p><a class="link" href="productos.php">Ver catálogo</a></p>

  <?php if ($es_admin): ?>
    <div class="admin-box">
      <h3>Panel de administrador</h3>
      <p>Próximamente: estado de ventas, pedidos y reportes.</p>
    </div>
  <?php endif; ?>

  <a class="logout" href="logout.php">Cerrar sesión</a>
</div>
</body>
</html>
