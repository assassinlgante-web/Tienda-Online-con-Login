<?php
// registro.php
// Recibe los datos del formulario de registro, valida, encripta la
// contraseña, y la guarda en la base de datos.

require_once "conexion.php";

$errores = [];

// Solo procesamos si el formulario fue enviado (método POST)
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // trim() quita espacios en blanco al inicio/final
    $nombre = trim($_POST["nombre"] ?? "");
    $email  = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    // --- Validaciones en el servidor (nunca confíes solo en el HTML) ---
    if (strlen($nombre) < 3) {
        $errores[] = "El nombre debe tener al menos 3 letras.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores[] = "El correo no es válido.";
    }
    if (strlen($password) < 6) {
        $errores[] = "La contraseña debe tener al menos 6 caracteres.";
    }

    // Revisar si el correo ya existe (usamos consulta preparada para evitar SQL Injection)
    if (empty($errores)) {
        $stmt = mysqli_prepare($conexion, "SELECT usu_id FROM USUARIO WHERE usu_email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email); // "s" = el parámetro es un string
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errores[] = "Ese correo ya está registrado.";
        }
        mysqli_stmt_close($stmt);
    }

    // Si no hay errores, guardamos el usuario
    if (empty($errores)) {
        // password_hash() ENCRIPTA (hashea) la contraseña. Nunca se guarda en texto plano.
        // PASSWORD_DEFAULT usa el algoritmo más seguro recomendado por PHP actualmente.
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = mysqli_prepare($conexion, "INSERT INTO USUARIO (usu_nombre, usu_email, usu_password) VALUES (?, ?, ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $passwordHash);

        if (mysqli_stmt_execute($stmt)) {
            $exito = true;
        } else {
            $errores[] = "Error al guardar el usuario: " . mysqli_error($conexion);
        }
        mysqli_stmt_close($stmt);
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro</title>
<style>
  body{ font-family:sans-serif; background:#F7F5F1; display:flex; justify-content:center; padding:60px 20px; }
  .box{ background:#fff; border:1px solid #ddd; border-radius:10px; padding:30px; width:100%; max-width:380px; }
  input{ width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
  button{ width:100%; padding:12px; background:#14213D; color:#fff; border:none; border-radius:6px; cursor:pointer; }
  .error{ background:#fdecea; color:#c1121f; padding:10px; border-radius:6px; margin-bottom:12px; font-size:0.9rem; }
  .exito{ background:#e8f6f3; color:#2A9D8F; padding:10px; border-radius:6px; margin-bottom:12px; font-size:0.9rem; }
</style>
</head>
<body>
<div class="box">
  <h2>Crear cuenta</h2>

  <?php if (!empty($errores)): ?>
    <div class="error">
      <?php foreach ($errores as $e): ?>
        <?= htmlspecialchars($e) ?><br>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <?php if (!empty($exito)): ?>
    <div class="exito">✓ Cuenta creada correctamente. <a href="login.php">Inicia sesión aquí</a>.</div>
  <?php else: ?>
    <form method="POST" action="registro.php">
      <input type="text" name="nombre" placeholder="Nombre completo" value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>">
      <input type="email" name="email" placeholder="Correo" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
      <input type="password" name="password" placeholder="Contraseña (mínimo 6 caracteres)">
      <button type="submit">Registrarme</button>
    </form>
    <p style="text-align:center; margin-top:14px;"><a href="login.php">Ya tengo cuenta</a></p>
  <?php endif; ?>
</div>
</body>
</html>
