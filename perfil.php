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
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Mi perfil</title>
<style>
  body{ font-family:sans-serif; background:#F7F5F1; display:flex; justify-content:center; padding:60px 20px; }
  .box{ background:#fff; border:1px solid #ddd; border-radius:10px; padding:30px; width:100%; max-width:380px; }
  a.logout{ display:inline-block; margin-top:16px; color:#c1121f; }
</style>
</head>
<body>
<div class="box">
  <h2>¡Bienvenido, <?= htmlspecialchars($_SESSION["usuario_nombre"]) ?>!</h2>
  <p>Tu rol es: <strong><?= htmlspecialchars($_SESSION["usuario_rol"]) ?></strong></p>
  <p>Esta es una página protegida. Solo la puede ver alguien con sesión activa.</p>
  <a class="logout" href="logout.php">Cerrar sesión</a>
</div>
</body>
</html>
