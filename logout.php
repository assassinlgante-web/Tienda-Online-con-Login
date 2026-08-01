<?php
// logout.php
// Cierra la sesión del usuario.
session_start();

// session_unset() borra todas las variables guardadas en la sesión.
session_unset();
// session_destroy() elimina la sesión por completo del servidor.
session_destroy();

header("Location: login.php");
exit;
?>
