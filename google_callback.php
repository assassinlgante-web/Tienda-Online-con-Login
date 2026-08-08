<?php
// google_callback.php
// Google manda al usuario de vuelta aquí después de que confirma su
// identidad. Este archivo intercambia el "código" por los datos reales
// del usuario, y lo loguea (o le crea la cuenta si es su primera vez).

session_start();
require_once "config_google.php";
require_once "conexion.php";

// Si Google no mandó un "code", algo salió mal o el usuario canceló
if (!isset($_GET["code"])) {
    header("Location: login.php?error=google_cancelado");
    exit;
}

$code = $_GET["code"];

// --- PASO 1: Intercambiar el "code" por un token de acceso real ---
// Esto se hace con una petición POST directa al servidor de Google
// (no es algo que el usuario ve, pasa "por detrás" entre tu servidor y Google).
$ch = curl_init("https://oauth2.googleapis.com/token");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
    "code"          => $code,
    "client_id"     => GOOGLE_CLIENT_ID,
    "client_secret" => GOOGLE_CLIENT_SECRET,
    "redirect_uri"  => GOOGLE_REDIRECT_URI,
    "grant_type"    => "authorization_code",
]));
$respuesta_token = curl_exec($ch);
curl_close($ch);

$datos_token = json_decode($respuesta_token, true);

if (!isset($datos_token["access_token"])) {
    // Algo falló al intercambiar el código (código expirado, credenciales mal puestas, etc.)
    die("Error al autenticar con Google. Intenta de nuevo.");
}

$access_token = $datos_token["access_token"];

// --- PASO 2: Usar el access_token para pedirle a Google los datos del usuario ---
$ch = curl_init("https://www.googleapis.com/oauth2/v2/userinfo?access_token=" . urlencode($access_token));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$respuesta_usuario = curl_exec($ch);
curl_close($ch);

$datos_usuario = json_decode($respuesta_usuario, true);

// $datos_usuario ahora tiene algo como:
// ["id" => "10982...", "email" => "juan@gmail.com", "name" => "Juan Pérez", "picture" => "https://..."]

$google_id = $datos_usuario["id"];
$email     = $datos_usuario["email"];
$nombre    = $datos_usuario["name"];

// --- PASO 3: Buscar si ya existe un usuario con ese google_id ---
$stmt = mysqli_prepare($conexion, "SELECT usu_id, usu_nombre, usu_rol FROM USUARIO WHERE usu_google_id = ?");
mysqli_stmt_bind_param($stmt, "s", $google_id);
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$usuario = mysqli_fetch_assoc($resultado);
mysqli_stmt_close($stmt);

if ($usuario) {
    // Ya existía una cuenta de Google con ese id: solo lo logueamos
    $usu_id = $usuario["usu_id"];
    $usu_nombre = $usuario["usu_nombre"];
    $usu_rol = $usuario["usu_rol"];

} else {
    // No existe todavía. Puede ser la primera vez que usa Google,
    // o puede que ya tenga una cuenta LOCAL con ese mismo correo.
    $stmt = mysqli_prepare($conexion, "SELECT usu_id, usu_nombre, usu_rol FROM USUARIO WHERE usu_email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $resultado = mysqli_stmt_get_result($stmt);
    $usuario_existente = mysqli_fetch_assoc($resultado);
    mysqli_stmt_close($stmt);

    if ($usuario_existente) {
        // Ya tenía cuenta local con ese correo: le vinculamos el google_id
        // a esa cuenta existente, en vez de crear una cuenta duplicada.
        $stmt = mysqli_prepare($conexion, "UPDATE USUARIO SET usu_google_id = ? WHERE usu_id = ?");
        mysqli_stmt_bind_param($stmt, "si", $google_id, $usuario_existente["usu_id"]);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);

        $usu_id = $usuario_existente["usu_id"];
        $usu_nombre = $usuario_existente["usu_nombre"];
        $usu_rol = $usuario_existente["usu_rol"];

    } else {
        // Primera vez de verdad: creamos una cuenta nueva.
        // usu_password queda NULL a propósito (nunca escribió una).
        $stmt = mysqli_prepare($conexion, "INSERT INTO USUARIO (usu_nombre, usu_email, usu_proveedor, usu_google_id) VALUES (?, ?, 'google', ?)");
        mysqli_stmt_bind_param($stmt, "sss", $nombre, $email, $google_id);
        mysqli_stmt_execute($stmt);

        $usu_id = mysqli_insert_id($conexion);
        $usu_nombre = $nombre;
        $usu_rol = "cliente"; // rol por defecto para cuentas nuevas

        mysqli_stmt_close($stmt);
    }
}

// --- PASO 4: Iniciar sesión, igual que en login.php ---
$_SESSION["usuario_id"] = $usu_id;
$_SESSION["usuario_nombre"] = $usu_nombre;
$_SESSION["usuario_rol"] = $usu_rol;

header("Location: perfil.php");
exit;
?>
