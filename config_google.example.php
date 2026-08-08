<?php
// config_google.example.php
// Copia este archivo, renómbralo a "config_google.php", y pon tus propias
// credenciales de Google Cloud Console. Igual que conexion.php, el archivo
// real NUNCA se sube a GitHub (está en .gitignore) porque el Client Secret
// es información sensible, parecida a una contraseña.

define("GOOGLE_CLIENT_ID", "TU_CLIENT_ID_AQUI");
define("GOOGLE_CLIENT_SECRET", "TU_CLIENT_SECRET_AQUI");

// Debe coincidir EXACTAMENTE con la URI que registraste en Google Cloud Console
define("GOOGLE_REDIRECT_URI", "http://localhost/Tienda-Online-con-Login/google_callback.php");
?>
