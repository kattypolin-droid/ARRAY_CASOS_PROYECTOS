<?php
$secciones = array(
	"Ejemplos" => array(
		array("archivo" => "ejemplo01.php", "titulo" => "Arreglos y colores", "detalle" => "Acceso por índice, recorridos y arreglos asociativos."),
		array("archivo" => "ejemplo02.php", "titulo" => "Recorrer frutas", "detalle" => "Recorrido de un arreglo con foreach."),
		array("archivo" => "ejemplo03.php", "titulo" => "Los dedos de la mano", "detalle" => "Arreglo numérico recorrido con un ciclo for."),
	),
	"Casos" => array(
		array("archivo" => "caso01.php", "titulo" => "Días de la semana", "detalle" => "Recorrido con for y foreach."),
		array("archivo" => "caso02.php", "titulo" => "Países", "detalle" => "Lista de países de América del Sur."),
		array("archivo" => "caso03.php", "titulo" => "Meses del año", "detalle" => "Arreglo de meses recorrido con foreach."),
		array("archivo" => "caso04.php", "titulo" => "Artículos", "detalle" => "Cantidad de elementos de un arreglo."),
		array("archivo" => "caso05.php", "titulo" => "Datos de un empleado", "detalle" => "Acceso por índice y recorridos de un arreglo."),
	),
	"Proyectos" => array(
		array("archivo" => "proyecto01.php", "titulo" => "Inventario", "detalle" => "Catálogo con búsqueda, categorías y control de stock."),
		array("archivo" => "proyecto02.php", "titulo" => "Planilla de empleados", "detalle" => "Listado de empleados y cálculo de boletas de pago."),
	),
);

$paginas = array_merge(...array_values($secciones));
$archivoSolicitado = is_string($_GET["archivo"] ?? null) ? $_GET["archivo"] : "";
$paginaActual = null;
$indiceActual = false;

foreach ($paginas as $indice => $pagina) {
	if ($pagina["archivo"] === $archivoSolicitado) {
		$paginaActual = $pagina;
		$indiceActual = $indice;
		break;
	}
}

$esProyecto = $paginaActual !== null && in_array(
	$paginaActual["archivo"],
	array_column($secciones["Proyectos"], "archivo"),
	true
);
$codigo = "";
$codigoHtml = "";
$salida = "";
$resaltarCodigo = static function (string $codigo): string {
	$clases = array(
		T_OPEN_TAG => "tok-meta",
		T_CLOSE_TAG => "tok-meta",
		T_COMMENT => "tok-comment",
		T_DOC_COMMENT => "tok-comment",
		T_CONSTANT_ENCAPSED_STRING => "tok-string",
		T_ENCAPSED_AND_WHITESPACE => "tok-string",
		T_VARIABLE => "tok-variable",
		T_LNUMBER => "tok-number",
		T_DNUMBER => "tok-number",
		T_STRING => "tok-name",
		T_IF => "tok-keyword",
		T_ELSE => "tok-keyword",
		T_FOREACH => "tok-keyword",
		T_FOR => "tok-keyword",
		T_AS => "tok-keyword",
		T_ECHO => "tok-keyword",
		T_ARRAY => "tok-keyword",
		T_FUNCTION => "tok-keyword",
		T_RETURN => "tok-keyword",
		T_DECLARE => "tok-keyword",
	);
	$html = "";

	foreach (token_get_all($codigo) as $token) {
		if (is_array($token)) {
			[$tipo, $texto] = $token;
			$clase = $clases[$tipo] ?? "";
		} else {
			$texto = $token;
			$clase = "";
		}

		$texto = htmlspecialchars($texto, ENT_QUOTES | ENT_SUBSTITUTE, "UTF-8");
		$html .= $clase === "" ? $texto : '<span class="' . $clase . '">' . $texto . '</span>';
	}

	return $html;
};

if ($paginaActual !== null && !$esProyecto) {
	$rutaArchivo = __DIR__ . DIRECTORY_SEPARATOR . $paginaActual["archivo"];
	$codigo = file_get_contents($rutaArchivo) ?: "";
	$codigoHtml = $resaltarCodigo($codigo);
	$salida = (static function (string $ruta): string {
		ob_start();
		include $ruta;
		return (string) ob_get_clean();
	})( $rutaArchivo);
}

$anterior = $paginaActual !== null
	? $paginas[($indiceActual - 1 + count($paginas)) % count($paginas)]
	: null;
$siguiente = $paginaActual !== null
	? $paginas[($indiceActual + 1) % count($paginas)]
	: null;
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Ejercicios de PHP</title>
	<style>
		:root {
			color-scheme: light;
			--fondo: #000000;
			--papel: #9740f3;
			--texto: #fff;
			--suave: #1920eb;
			--linea: #fdd3e6;
			--acento: #182044;
			--naranja: #dad6fa;
		}
		* { box-sizing: border-box; }
		body {
			margin: 0;
			background-color: var(--fondo);
			color: var(--texto);
			font-family: "Segoe UI", Tahoma, sans-serif;
			line-height: 1.5;
		}
		main { width: min(100% - 32px, 920px); margin: 0 auto; padding: 48px 0 64px; }
		header { margin-bottom: 34px; border-bottom: 1px solid var(--linea); padding-bottom: 24px; }
		.etiqueta { color: var(--naranja); font-size: .78rem; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
		h1 { margin: 8px 0; font-size: clamp(2rem, 5vw, 3rem); line-height: 1.1; }
		.intro { max-width: 580px; margin: 0; color: var(--suave); }
		section { margin-top: 30px; }
		h2 { display: flex; align-items: baseline; gap: 10px; margin: 0 0 12px; font-size: 1.2rem; }
		.cantidad { color: var(--suave); font-size: .85rem; font-weight: 400; }
		.barra-navegacion { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin: 26px 0 0; }
		.boton { display: inline-flex; min-height: 44px; align-items: center; justify-content: center; padding: 8px 16px; border: 1px solid var(--linea); border-radius: 10px; background: var(--acento); color: #fff; font: inherit; font-weight: 600; text-decoration: none; transition: background-color .16s ease, transform .16s ease; }
		.boton:hover { transform: translateY(-1px); background: #29376a; }
		.boton-secundario { background: var(--papel); color: var(--acento); }
		.boton-secundario:hover { background: #ff83bb; }
		.visor { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1fr); gap: 16px; }
		.panel { min-width: 0; overflow: hidden; border: 2px solid var(--linea); border-radius: 14px; background: var(--papel); }
		.panel h2 { margin: 0; border-bottom: 1px solid var(--linea); padding: 12px 16px; color: var(--acento); }
		.panel pre { max-height: 68vh; margin: 12px; overflow: auto; padding: 16px; border-radius: 9px; background: #111b18; color: #e4eee8; font: .88rem/1.6 Consolas, "Courier New", monospace; tab-size: 4; }
		.tok-meta { color: #d6a5ff; }
		.tok-comment { color: #91a39a; font-style: italic; }
		.tok-string { color: #a8db8c; }
		.tok-variable { color: #f2c879; }
		.tok-number { color: #c4a0f5; }
		.tok-name { color: #82d9e3; }
		.tok-keyword { color: #ff91a4; font-weight: 600; }
		.resultado { min-height: 200px; max-height: 68vh; margin: 12px; overflow: auto; padding: 16px; border-radius: 9px; background: #ffd2e6; color: #302035; overflow-wrap: anywhere; }
		.proyecto { display: block; width: 100%; height: max(72vh, 620px); border: 1px solid var(--linea); border-radius: 14px; background: white; }
		@media (max-width: 700px) { main { padding-top: 28px; } .visor { grid-template-columns: 1fr; } .panel pre, .resultado { max-height: 52vh; } .barra-navegacion { gap: 8px; } .boton { flex: 1; padding-inline: 8px; font-size: .9rem; } .proyecto { height: 75vh; min-height: 540px; } }
		.lista { display: grid; grid-template-columns: repeat(auto-fit, minmax(min(100%, 260px), 1fr)); gap: 10px; }
		.enlace {
			display: block;
			min-height: 92px;
			padding: 15px 17px;
			border: 1px solid var(--linea);
			border-left: 4px solid #fff;
			border-radius: 11px;
			background: var(--papel);
			color: var(--acento);
			text-decoration: none;
			transition: border-color .16s ease, transform .16s ease;
		}
		.enlace:hover { border-color: #fff; transform: translateY(-2px); }
		.enlace:focus-visible { outline: 3px solid var(--naranja); outline-offset: 3px; }
		.enlace strong { display: block; margin-bottom: 3px; color: var(--acento); }
		.enlace span { color: #4b2940; font-size: .9rem; }
		@media (prefers-reduced-motion: reduce) { *, *::before, *::after { scroll-behavior: auto !important; transition-duration: .01ms !important; } }
	</style>
</head>
<body>
	<main>
		<header>
			<div class="etiqueta">PHP · Arreglos</div>
			<h1><?php echo $paginaActual === null ? "Ejercicios y proyectos" : htmlspecialchars($paginaActual["titulo"], ENT_QUOTES, "UTF-8"); ?></h1>
			<p class="intro"><?php echo $paginaActual === null ? "Selecciona un archivo para ver su contenido y recorrer la colección." : htmlspecialchars($paginaActual["detalle"], ENT_QUOTES, "UTF-8"); ?></p>
		</header>
		<?php if ($paginaActual === null) { ?>
			<?php foreach ($secciones as $nombre => $listaPaginas) { ?>
				<section aria-labelledby="seccion-<?php echo strtolower($nombre); ?>">
					<h2 id="seccion-<?php echo strtolower($nombre); ?>"><?php echo htmlspecialchars($nombre, ENT_QUOTES, "UTF-8"); ?><span class="cantidad"><?php echo count($listaPaginas); ?> archivos</span></h2>
					<div class="lista">
						<?php foreach ($listaPaginas as $pagina) { ?>
							<a class="enlace" href="index.php?archivo=<?php echo rawurlencode($pagina["archivo"]); ?>">
								<strong><?php echo htmlspecialchars($pagina["titulo"], ENT_QUOTES, "UTF-8"); ?></strong>
								<span><?php echo htmlspecialchars($pagina["detalle"], ENT_QUOTES, "UTF-8"); ?></span>
							</a>
						<?php } ?>
					</div>
				</section>
			<?php } ?>
		<?php } else { ?>
			<?php if ($esProyecto) { ?>
				<iframe class="proyecto" src="<?php echo htmlspecialchars($paginaActual["archivo"], ENT_QUOTES, "UTF-8"); ?>" title="<?php echo htmlspecialchars($paginaActual["titulo"], ENT_QUOTES, "UTF-8"); ?>"></iframe>
			<?php } else { ?>
				<div class="visor">
					<section class="panel" aria-labelledby="codigo-titulo">
						<h2 id="codigo-titulo">Código</h2>
						<pre><code><?php echo $codigoHtml; ?></code></pre>
					</section>
					<section class="panel" aria-labelledby="resultado-titulo">
						<h2 id="resultado-titulo">Resultado</h2>
						<div class="resultado"><?php echo $salida; ?></div>
					</section>
				</div>
			<?php } ?>
			<nav class="barra-navegacion" aria-label="Navegación entre archivos">
				<a class="boton boton-secundario" href="index.php?archivo=<?php echo rawurlencode($anterior["archivo"]); ?>">&larr; Anterior</a>
				<a class="boton boton-secundario" href="index.php">Inicio</a>
				<a class="boton" href="index.php?archivo=<?php echo rawurlencode($siguiente["archivo"]); ?>">Siguiente &rarr;</a>
			</nav>
		<?php } ?>
	</main>
</body>
</html>