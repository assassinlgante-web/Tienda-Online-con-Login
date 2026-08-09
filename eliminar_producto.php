<?php
// eliminar_producto.php
// Borra un producto. Protegido: solo admin. Solo acepta POST (nunca un
// simple link GET), para evitar que alguien borre productos por accidente
// con solo hacer clic en un link, o que un buscador lo indexe y lo "borre" solo.

session_start();
require_once "conexion.php";

if (!isset($_SESSION["usuario_id"])) {
    header("Location: login.php");
    exit;
}
if (($_SESSION["usuario_rol"] ?? "") !== "admin") {
    header("Location: productos.php?error=sin_permiso");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: productos.php");
    exit;
}

$id = $_POST["id"] ?? null;
if (!$id || !is_numeric($id)) {
    header("Location: productos.php");
    exit;
}

// Nota: si el producto ya fue comprado alguna vez, existe una fila en
// DETALLE_PEDIDO que lo referencia (FOREIGN KEY). MySQL va a RECHAZAR
// el DELETE en ese caso, para no dejar pedidos históricos "huérfanos".
// Por eso revisamos el error y avisamos con un mensaje claro.
$stmt = mysqli_prepare($conexion, "DELETE FROM PRODUCTO WHERE pro_id = ?");
mysqli_stmt_bind_param($stmt, "i", $id);

if (mysqli_stmt_execute($stmt)) {
    header("Location: productos.php?mensaje=producto_eliminado");
} else {
    header("Location: productos.php?error=no_se_pudo_eliminar");
}
mysqli_stmt_close($stmt);
exit;
?>
