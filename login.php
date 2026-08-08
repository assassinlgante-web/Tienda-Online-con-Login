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
        $stmt = mysqli_prepare($conexion, "SELECT usu_id, usu_nombre, usu_email, usu_password, usu_rol FROM USUARIO WHERE usu_email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);
        $usuario = mysqli_fetch_assoc($resultado);
        mysqli_stmt_close($stmt);

        // password_verify() compara la contraseña escrita contra el HASH guardado.
        // Nunca se "desencripta" el hash, solo se compara.
        if ($usuario && password_verify($password, $usuario["usu_password"])) {
            // Login correcto: guardamos los datos del usuario en la sesión
            $_SESSION["usuario_id"] = $usuario["usu_id"];
            $_SESSION["usuario_nombre"] = $usuario["usu_nombre"];
            $_SESSION["usuario_rol"] = $usuario["usu_rol"];

            // Redirigimos a la página protegida
            header("Location: productos.php");
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
  .divisor{ display:flex; align-items:center; gap:10px; margin:16px 0; color:#999; font-size:0.85rem; }
  .divisor::before, .divisor::after{ content:""; flex:1; height:1px; background:#ddd; }
  .btn-google{ display:flex; align-items:center; justify-content:center; gap:10px; width:100%; padding:11px; background:#fff; border:1px solid #ccc; border-radius:6px; cursor:pointer; text-decoration:none; color:#333; font-size:0.95rem; }
  .btn-google:hover{ background:#f5f5f5; }
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

  <div class="divisor">o</div>

  <a href="google_login.php" class="btn-google">
    <svg width="18" height="18" viewBox="0 0 18 18"><path fill="#4285F4" d="M17.64 9.2c0-.64-.06-1.25-.16-1.84H9v3.48h4.84a4.14 4.14 0 0 1-1.8 2.72v2.26h2.92a8.78 8.78 0 0 0 2.68-6.62z"/><path fill="#34A853" d="M9 18c2.43 0 4.47-.8 5.96-2.18l-2.92-2.26c-.81.54-1.84.86-3.04.86-2.34 0-4.32-1.58-5.03-3.71H.96v2.33A9 9 0 0 0 9 18z"/><path fill="#FBBC05" d="M3.97 10.71a5.4 5.4 0 0 1 0-3.42V4.96H.96a9 9 0 0 0 0 8.08l3.01-2.33z"/><path fill="#EA4335" d="M9 3.58c1.32 0 2.5.45 3.44 1.35l2.58-2.58C13.46.89 11.43 0 9 0A9 9 0 0 0 .96 4.96l3.01 2.33C4.68 5.16 6.66 3.58 9 3.58z"/></svg>
    Continuar con Google
  </a>

  <p style="text-align:center; margin-top:14px;"><a href="registro.php">Crear una cuenta</a></p>
</div>
</body>
</html>
