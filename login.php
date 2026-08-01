<?php
// login.php
// session_start() DEBE ir antes de cualquier salida HTML — activa el
// sistema de sesiones de PHP, que es lo que nos deja "recordar" que
// un usuario está logueado entre una página y otra.
session_start();

require_once "conexion.php";

$errores = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {
        $errores[] = "Correo y contraseña son obligatorios.";
    } else {
        // Buscamos al usuario por correo (consulta preparada, evita SQL Injection)
        $stmt = mysqli_prepare($conexion, "SELECT id, nombre, email, password, rol FROM usuarios WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        // password_verify() compara la contraseña escrita contra el HASH guardado.
        // Nunca se "desencripta" el hash, solo se compara.
        if ($usuario && password_verify($password, $usuario["password"])) {
            // Login correcto: guardamos los datos del usuario en la sesión
            $_SESSION["usuario_id"] = $usuario["id"];
            $_SESSION["usuario_nombre"] = $usuario["nombre"];
            $_SESSION["usuario_rol"] = $usuario["rol"];

            // Redirigimos a la página protegida
            header("Location: perfil.php");
            exit; // siempre usar exit después de header("Location: ...")
        } else {
            // Mensaje genérico a propósito: no decimos si falló el correo
            // o la contraseña por separado (evita dar pistas a un atacante).
            $errores[] = "Correo o contraseña incorrectos.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar sesión</title>
<style>
  body{ font-family:sans-serif; background:#F7F5F1; display:flex; justify-content:center; padding:60px 20px; }
  .box{ background:#fff; border:1px solid #ddd; border-radius:10px; padding:30px; width:100%; max-width:380px; }
  input{ width:100%; padding:10px; margin-bottom:12px; border:1px solid #ccc; border-radius:6px; box-sizing:border-box; }
  button{ width:100%; padding:12px; background:#14213D; color:#fff; border:none; border-radius:6px; cursor:pointer; }
  .error{ background:#fdecea; color:#c1121f; padding:10px; border-radius:6px; margin-bottom:12px; font-size:0.9rem; }
</style>
</head>
<body>
<div class="box">
  <h2>Iniciar sesión</h2>

  <?php if (!empty($errores)): ?>
    <div class="error">
      <?php foreach ($errores as $e): ?>
        <?= htmlspecialchars($e) ?><br>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="login.php">
    <input type="email" name="email" placeholder="Correo">
    <input type="password" name="password" placeholder="Contraseña">
    <button type="submit">Entrar</button>
  </form>
  <p style="text-align:center; margin-top:14px;"><a href="registro.php">Crear una cuenta</a></p>
</div>
</body>
</html>
