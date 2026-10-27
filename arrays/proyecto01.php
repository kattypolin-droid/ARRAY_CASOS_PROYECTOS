<?php
declare(strict_types=1);

$config = require __DIR__ . "/config/app.php";
require_once __DIR__ . "/lib/inventario.php";

$productos = obtenerProductos();
$categorias = obtenerCategorias($productos);
$busqueda = is_string($_GET["buscar"] ?? null) ? trim($_GET["buscar"]) : "";
$categoria = is_string($_GET["categoria"] ?? null) ? $_GET["categoria"] : "";
$categoria = in_array($categoria, $categorias, true) ? $categoria : "";
$resultados = filtrarProductos($productos, $busqueda, $categoria);
$escapar = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
$moneda = static fn(float $valor): string => $config["simbolo_moneda"] . " " . number_format($valor, 2, ",", ".") . " " . $config["moneda"];
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?php echo $escapar($config["nombre_sistema"]); ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		:root { --mint: #f1f7f3; --teal: #21828a; --teal-dark: #17656d; --coral: #e87345; --ink: #263b3d; --line: #d8e5df; }
		body { background: var(--mint); color: var(--ink); }
		header { background: var(--teal); color: white; }
		.panel, .metric { background: white; border: 1px solid var(--line); border-radius: 6px; }
		.metric { height: 100%; padding: 16px; }
		.metric small { color: #647779; }
		.metric strong { color: var(--teal-dark); font-size: 1.2rem; }
		h1, h2 { color: var(--teal-dark); }
		.btn-success { --bs-btn-bg: var(--coral); --bs-btn-border-color: var(--coral); --bs-btn-hover-bg: #d65f35; --bs-btn-hover-border-color: #d65f35; }
		.form-control:focus, .form-select:focus { border-color: var(--teal); box-shadow: 0 0 0 .2rem rgb(33 130 138 / 18%); }
		.table thead th { background: #e7f1ec; color: var(--teal-dark); }
	</style>
</head>
<body>
	<header class="py-3">
		<div class="container"><strong><?php echo $escapar($config["nombre_sistema"]); ?></strong></div>
	</header>
	<main class="container py-4">
		<h1 class="h2">Catálogo de productos</h1>
		<p class="text-secondary">Consulta existencias y valor del inventario.</p>

		<div class="row g-3 mb-4">
			<div class="col-md-4"><div class="metric"><small>Artículos</small><br><strong><?php echo count($productos); ?></strong></div></div>
			<div class="col-md-4"><div class="metric"><small>Valor total</small><br><strong><?php echo $escapar($moneda(calcularValorTotalInventario($productos))); ?></strong></div></div>
			<div class="col-md-4"><div class="metric"><small>Productos con bajo stock</small><br><strong><?php echo contarProductosBajoStock($productos); ?></strong></div></div>
		</div>

		<form class="panel row g-3 align-items-end p-3 mb-4" method="get">
			<div class="col-md-5">
				<label for="buscar" class="form-label">Buscar producto</label>
				<input class="form-control" type="search" id="buscar" name="buscar" value="<?php echo $escapar($busqueda); ?>" placeholder="Nombre, código o categoría">
			</div>
			<div class="col-md-4">
				<label for="categoria" class="form-label">Categoría</label>
				<select class="form-select" id="categoria" name="categoria">
					<option value="">Todas</option>
					<?php foreach ($categorias as $opcion) { ?>
						<option value="<?php echo $escapar($opcion); ?>" <?php echo $categoria === $opcion ? "selected" : ""; ?>><?php echo $escapar($opcion); ?></option>
					<?php } ?>
				</select>
			</div>
			<div class="col-md-3 d-flex gap-2">
				<button class="btn btn-success flex-grow-1" type="submit">Filtrar</button>
				<a class="btn btn-outline-secondary" href="index.php">Limpiar</a>
			</div>
		</form>

		<section class="panel overflow-hidden">
			<div class="p-3 border-bottom d-flex justify-content-between">
				<h2 class="h5 mb-0">Productos</h2>
				<span><?php echo count($resultados); ?> resultados</span>
			</div>
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead><tr><th>Código</th><th>Producto</th><th>Categoría</th><th class="text-end">Precio</th><th class="text-end">Stock</th><th>Estado</th></tr></thead>
					<tbody>
						<?php if (!$resultados) { ?><tr><td colspan="6" class="text-center py-4">No se encontraron productos.</td></tr><?php } ?>
						<?php foreach ($resultados as $producto) {
							$bajoStock = $producto["stock"] < 10;
						?>
							<tr>
								<td><?php echo $escapar($producto["codigo"]); ?></td>
								<td><?php echo $escapar($producto["nombre"]); ?></td>
								<td><?php echo $escapar($producto["categoria"]); ?></td>
								<td class="text-end text-nowrap"><?php echo $escapar($moneda((float) $producto["precio"])); ?></td>
								<td class="text-end"><?php echo (int) $producto["stock"]; ?></td>
								<td><span class="badge <?php echo $bajoStock ? "text-bg-warning" : "text-bg-success"; ?>"><?php echo $bajoStock ? "Bajo" : "Disponible"; ?></span></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</section>
	</main>
</body>
</html>
