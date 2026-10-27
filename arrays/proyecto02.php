<?php
declare(strict_types=1);

$config = require __DIR__ . "/config/app.php";
require_once __DIR__ . "/lib/planilla.php";

$empleados = obtenerEmpleados();
$valorLegajo = $_GET["legajo"] ?? "";
$legajoSeleccionado = is_scalar($valorLegajo) && filter_var($valorLegajo, FILTER_VALIDATE_INT) !== false
	? (int) $valorLegajo
	: 0;
$empleadoSeleccionado = null;

foreach ($empleados as $empleado) {
	if ($empleado["legajo"] === $legajoSeleccionado) {
		$empleadoSeleccionado = $empleado;
		break;
	}
}

$boleta = $empleadoSeleccionado !== null ? calcularBoletaPago($empleadoSeleccionado) : null;
$escapar = static fn($valor): string => htmlspecialchars((string) $valor, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
$moneda = static fn(float $valor): string => "$ " . number_format($valor, 2, ",", ".") . " ARS";
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Planilla de empleados</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<style>
		body { background: #f1f7f3; color: #263b3d; }
		header { background: #031b7a; color: white; }
		.panel, .metric { background: white; border: 1px solid #d8e5df; border-radius: 6px; }
		.metric { height: 100%; padding: 16px; }
		.metric small { color: #647779; }
		.metric strong, h1, h2 { color: #543393; }
		.table thead th { background: #e7f1ec; color: #880d0d; white-space: nowrap; }
	</style>
</head>
<body>
	<header class="py-3">
		<div class="container"><strong>Planilla de empleados y liquidación de sueldos</strong></div>
	</header>
	<main class="container py-4">
		<h1 class="h2">Sistema de Planilla de Empleados</h1>
		<p class="text-secondary">AFP: 10% del sueldo base. Arquitectos e ingenieros reciben una bonificación del 5%.</p>

		<div class="row g-3 mb-4">
			<div class="col-sm-6"><div class="metric"><small>Empleados registrados</small><br><strong><?php echo count($empleados); ?></strong></div></div>
			<div class="col-sm-6"><div class="metric"><small>Empleados con bonificación profesional</small><br><strong><?php echo count(array_filter($empleados, static fn($persona): bool => in_array($persona["profesion"], PROFESIONES_BONIFICADAS, true))); ?></strong></div></div>
		</div>

		<section class="panel overflow-hidden mb-4" aria-labelledby="empleados-titulo">
			<div class="p-3 border-bottom"><h2 id="empleados-titulo" class="h5 mb-0">Empleados</h2></div>
			<div class="table-responsive">
				<table class="table table-hover align-middle mb-0">
					<thead><tr><th>Legajo</th><th>Nombre</th><th>DNI</th><th>Profesión</th><th class="text-end">Sueldo base</th><th></th></tr></thead>
					<tbody>
						<?php foreach ($empleados as $persona) { ?>
							<tr>
								<td><?php echo (int) $persona["legajo"]; ?></td>
								<td><?php echo $escapar($persona["nombre"]); ?></td>
								<td><?php echo $escapar($persona["dni"]); ?></td>
								<td><?php echo $escapar($persona["profesion"]); ?></td>
								<td class="text-end text-nowrap"><?php echo $escapar($moneda((float) $persona["sueldo_base"])); ?></td>
								<td><a class="btn btn-sm btn-outline-success text-nowrap" href="?legajo=<?php echo (int) $persona["legajo"]; ?>#boleta">Ver boleta</a></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</section>

		<section id="boleta" class="panel p-3 p-md-4" aria-labelledby="boleta-titulo">
			<?php if ($boleta === null) { ?>
				<h2 id="boleta-titulo" class="h5">Boleta de pago</h2>
				<p class="text-secondary mb-0">Selecciona un empleado para calcular su sueldo neto.</p>
			<?php } else { ?>
				<div class="d-flex flex-wrap justify-content-between gap-2 mb-3">
					<div>
						<h2 id="boleta-titulo" class="h5 mb-1">Boleta de pago</h2>
						<div><?php echo $escapar($empleadoSeleccionado["nombre"]); ?> · Legajo <?php echo (int) $empleadoSeleccionado["legajo"]; ?></div>
					</div>
					<div class="text-secondary"><?php echo $escapar($empleadoSeleccionado["profesion"]); ?></div>
				</div>
				<div class="table-responsive">
					<table class="table mb-0">
						<tbody>
							<tr><th>Sueldo base</th><td class="text-end"><?php echo $escapar($moneda($boleta["sueldo_base"])); ?></td></tr>
							<tr><th>Aporte AFP (10%)</th><td class="text-end text-danger">− <?php echo $escapar($moneda($boleta["aporte_afp"])); ?></td></tr>
							<tr><th>Bonificación profesional (5%)</th><td class="text-end text-success">+ <?php echo $escapar($moneda($boleta["bonificacion"])); ?></td></tr>
							<tr class="table-success"><th>Sueldo neto</th><th class="text-end"><?php echo $escapar($moneda($boleta["sueldo_neto"])); ?></th></tr>
						</tbody>
					</table>
				</div>
			<?php } ?>
		</section>
	</main>
</body>
</html>
