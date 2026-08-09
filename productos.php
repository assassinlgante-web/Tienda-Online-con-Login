<?php
// productos.php
// Muestra todos los productos del catálogo, con búsqueda y filtro por
// categoría. No requiere sesión: cualquiera puede VER el catálogo, aunque
// no haya iniciado sesión (así funcionan la mayoría de tiendas online).

session_start();
require_once "conexion.php";

// Leemos los filtros que vienen de la URL (ej: productos.php?buscar=polo&categoria=1)
// Si no vienen, quedan vacíos por defecto.
$buscar = trim($_GET["buscar"] ?? "");
$categoria_id = $_GET["categoria"] ?? "";

// Armamos la consulta de forma dinámica según los filtros que el usuario haya puesto.
// JOIN une PRODUCTO con CATEGORIA para poder mostrar el nombre de la categoría,
// no solo su id numérico.
$sql = "SELECT p.pro_id, p.pro_nombre, p.pro_descripcion, p.pro_precio, p.pro_stock, p.pro_imagen_url, c.cat_nombre
        FROM PRODUCTO p
        LEFT JOIN CATEGORIA c ON p.cat_id = c.cat_id
        WHERE 1=1";
// "WHERE 1=1" es un truco común: siempre es verdadero, y nos deja agregar
// más condiciones abajo con "AND" sin preocuparnos de si es la primera o no.

$parametros = [];
$tipos = "";

if ($buscar !== "") {
    $sql .= " AND p.pro_nombre LIKE ?";
    $parametros[] = "%" . $buscar . "%"; // % % significa "que contenga este texto en cualquier parte"
    $tipos .= "s";
}

if ($categoria_id !== "") {
    $sql .= " AND p.cat_id = ?";
    $parametros[] = $categoria_id;
    $tipos .= "i"; // "i" = integer (número entero)
}

$sql .= " ORDER BY p.pro_creado_en DESC";

$stmt = mysqli_prepare($conexion, $sql);
if (!empty($parametros)) {
    // bind_param necesita los parámetros "desempaquetados", no como array.
    // El operador "..." (spread) hace justo eso.
    mysqli_stmt_bind_param($stmt, $tipos, ...$parametros);
}
mysqli_stmt_execute($stmt);
$resultado = mysqli_stmt_get_result($stmt);
$productos = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
mysqli_stmt_close($stmt);

// Traemos también todas las categorías, para armar el filtro desplegable
$categorias = mysqli_query($conexion, "SELECT cat_id, cat_nombre FROM CATEGORIA ORDER BY cat_nombre");
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Catálogo — Tienda Online</title>
<style>
  :root{ --ink:#14213D; --paper:#F7F5F1; --amber:#FF8C42; --slate:#5B6472; --line:rgba(20,33,61,0.12); }
  *{box-sizing:border-box; margin:0; padding:0;}
  body{ font-family:sans-serif; background:var(--paper); color:var(--ink); }
  header{ display:flex; justify-content:space-between; align-items:center; padding:18px 24px; border-bottom:1px solid var(--line); background:#fff; }
  .logo{ font-weight:700; font-size:1.2rem; }
  header nav a{ color:var(--ink); text-decoration:none; margin-left:20px; font-size:0.9rem; }
  .wrap{ max-width:1080px; margin:0 auto; padding:30px 24px; }
  .filtros{ display:flex; gap:12px; margin-bottom:24px; flex-wrap:wrap; }
  .filtros input, .filtros select{ padding:10px 12px; border:1px solid var(--line); border-radius:6px; font-size:0.9rem; }
  .filtros input{ flex:1; min-width:180px; }
  .filtros button{ padding:10px 18px; background:var(--ink); color:#fff; border:none; border-radius:6px; cursor:pointer; }
  .grid{ display:grid; grid-template-columns:repeat(auto-fill, minmax(220px, 1fr)); gap:20px; }
  .card{ background:#fff; border:1px solid var(--line); border-radius:10px; overflow:hidden; }
  .card img{ width:100%; height:160px; object-fit:cover; display:block; background:#eee; }
  .card-body{ padding:14px; }
  .card .cat{ font-size:0.72rem; color:var(--slate); text-transform:uppercase; letter-spacing:0.03em; }
  .card h3{ font-size:1rem; margin:4px 0 6px; }
  .card .precio{ font-weight:700; color:var(--ink); font-size:1.05rem; }
  .card .stock{ font-size:0.78rem; color:var(--slate); margin-top:4px; }
  .vacio{ text-align:center; color:var(--slate); padding:60px 20px; }
  .card-admin{ display:flex; gap:8px; padding:0 14px 14px; }
  .card-admin a, .card-admin button{ flex:1; padding:7px; border-radius:6px; font-size:0.8rem; text-align:center; text-decoration:none; cursor:pointer; border:1px solid var(--line); }
  .card-admin a{ color:var(--ink); background:#fff; }
  .card-admin button{ color:#C1121F; background:#fff; border-color:#f3c9cc; }
  .mensaje-top{ background:#e8f6f3; color:#2A9D8F; padding:10px 24px; text-align:center; font-size:0.9rem; }
  .error-top{ background:#fdecea; color:#c1121f; padding:10px 24px; text-align:center; font-size:0.9rem; }
  .card-nuevo{ display:flex; flex-direction:column; align-items:center; justify-content:center; min-height:260px; border:2px dashed var(--line); border-radius:10px; text-decoration:none; color:var(--slate); transition:border-color 0.15s, color 0.15s; }
  .card-nuevo:hover{ border-color:var(--amber); color:var(--amber); }
  .card-nuevo .plus{ font-size:2.2rem; line-height:1; margin-bottom:8px; }
  .card-nuevo span{ font-size:0.9rem; font-weight:600; }
</style>
</head>
<body>

<?php if (isset($_GET["mensaje"]) && $_GET["mensaje"] === "producto_eliminado"): ?>
  <div class="mensaje-top">✓ Producto eliminado correctamente.</div>
<?php endif; ?>
<?php if (isset($_GET["error"]) && $_GET["error"] === "sin_permiso"): ?>
  <div class="error-top">No tienes permiso para hacer esa acción.</div>
<?php endif; ?>
<?php if (isset($_GET["error"]) && $_GET["error"] === "no_se_pudo_eliminar"): ?>
  <div class="error-top">No se pudo eliminar: este producto ya tiene pedidos asociados.</div>
<?php endif; ?>

<header>
  <div class="logo">Tienda<span style="color:var(--amber)">Online</span></div>
  <nav>
    <a href="productos.php">Catálogo</a>
    <?php if (isset($_SESSION["usuario_id"])): ?>
      <a href="perfil.php">Mi cuenta</a>
    <?php else: ?>
      <a href="login.php">Iniciar sesión</a>
    <?php endif; ?>
  </nav>
</header>

<div class="wrap">
  <form class="filtros" method="GET" action="productos.php">
    <input type="text" name="buscar" placeholder="Buscar producto..." value="<?= htmlspecialchars($buscar) ?>">
    <select name="categoria">
      <option value="">Todas las categorías</option>
      <?php while ($cat = mysqli_fetch_assoc($categorias)): ?>
        <option value="<?= $cat["cat_id"] ?>" <?= ($categoria_id == $cat["cat_id"]) ? "selected" : "" ?>>
          <?= htmlspecialchars($cat["cat_nombre"]) ?>
        </option>
      <?php endwhile; ?>
    </select>
    <button type="submit">Filtrar</button>
  </form>

  <?php $es_admin = ($_SESSION["usuario_rol"] ?? "") === "admin"; ?>

  <?php if (empty($productos) && !$es_admin): ?>
    <div class="vacio">No se encontraron productos con esos filtros.</div>
  <?php else: ?>
    <div class="grid">
      <?php if ($es_admin): ?>
        <a href="agregar_producto.php" class="card-nuevo">
          <div class="plus">+</div>
          <span>Agregar producto</span>
        </a>
      <?php endif; ?>
      <?php foreach ($productos as $p): ?>
        <div class="card">
          <img src="<?= htmlspecialchars($p["pro_imagen_url"]) ?>" alt="<?= htmlspecialchars($p["pro_nombre"]) ?>">
          <div class="card-body">
            <div class="cat"><?= htmlspecialchars($p["cat_nombre"] ?? "Sin categoría") ?></div>
            <h3><?= htmlspecialchars($p["pro_nombre"]) ?></h3>
            <div class="precio">S/. <?= number_format($p["pro_precio"], 2) ?></div>
            <div class="stock"><?= $p["pro_stock"] > 0 ? $p["pro_stock"] . " disponibles" : "Sin stock" ?></div>
          </div>
          <?php if (($_SESSION["usuario_rol"] ?? "") === "admin"): ?>
            <div class="card-admin">
              <a href="editar_producto.php?id=<?= $p['pro_id'] ?>">Editar</a>
              <form method="POST" action="eliminar_producto.php" onsubmit="return confirm('¿Seguro que quieres eliminar este producto?');" style="flex:1; margin:0;">
                <input type="hidden" name="id" value="<?= $p['pro_id'] ?>">
                <button type="submit" style="width:100%;">Eliminar</button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>
</div>

</body>
</html>
