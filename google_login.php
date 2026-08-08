<?php
// google_login.php
// Este archivo no muestra nada en pantalla: solo arma la URL de Google
// y manda al usuario para allá. Se llega aquí al hacer clic en el botón
// "Iniciar sesión con Google" desde login.php.

require_once "config_google.php";

// Parámetros que Google necesita para saber quién eres y qué le pides
$parametros = [
    "client_id"     => GOOGLE_CLIENT_ID,
    "redirect_uri"  => GOOGLE_REDIRECT_URI,
    "response_type" => "code",          // le pedimos a Google un "código" para intercambiar después
    "scope"         => "openid email profile", // qué datos del usuario queremos: correo, nombre, foto
    "access_type"   => "online",
    "prompt"        => "select_account", // fuerza a mostrar el selector de cuentas de Google
];

// http_build_query() convierte el array en el formato "clave=valor&clave2=valor2"
// que necesita una URL.
$url_google = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query($parametros);

// Mandamos al navegador del usuario hacia esa URL de Google
header("Location: " . $url_google);
exit;
?>
