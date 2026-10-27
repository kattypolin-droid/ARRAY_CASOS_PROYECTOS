<?php

declare(strict_types=1);

function obtenerProductos(): array
{
    return array(
        array("codigo" => "LIB-001", "nombre" => "Cien años de soledad", "categoria" => "Literatura", "precio" => 18500.00, "stock" => 12),
        array("codigo" => "LIB-002", "nombre" => "El principito", "categoria" => "Literatura", "precio" => 12000.00, "stock" => 7),
        array("codigo" => "LIB-003", "nombre" => "Matemática básica", "categoria" => "Educación", "precio" => 24900.00, "stock" => 15),
        array("codigo" => "LIB-004", "nombre" => "Cuaderno universitario", "categoria" => "Papelería", "precio" => 3500.00, "stock" => 30),
        array("codigo" => "LIB-005", "nombre" => "Bolígrafo azul", "categoria" => "Papelería", "precio" => 1200.00, "stock" => 5),
        array("codigo" => "LIB-006", "nombre" => "Diccionario escolar", "categoria" => "Educación", "precio" => 16750.00, "stock" => 9),
        array("codigo" => "LIB-007", "nombre" => "Resaltador amarillo", "categoria" => "Oficina", "precio" => 2100.00, "stock" => 18),
        array("codigo" => "LIB-008", "nombre" => "Agenda anual", "categoria" => "Papelería", "precio" => 8900.00, "stock" => 4)
    );
}

function contarArticulos(array $productos): int
{
    return count($productos);
}

function calcularValorTotalInventario(array $productos): float
{
    $valorTotal = 0.0;

    foreach ($productos as $producto) {
        $valorTotal += $producto["precio"] * $producto["stock"];
    }

    return $valorTotal;
}

function contarProductosBajoStock(array $productos): int
{
    $cantidad = 0;

    foreach ($productos as $producto) {
        if ($producto["stock"] < 10) {
            $cantidad++;
        }
    }

    return $cantidad;
}

function obtenerCategorias(array $productos): array
{
    $categorias = array_unique(array_column($productos, "categoria"));
    sort($categorias);

    return array_values($categorias);
}

function filtrarProductos(array $productos, string $busqueda, string $categoria): array
{
    $busqueda = trim($busqueda);

    return array_values(array_filter(
        $productos,
        static function (array $producto) use ($busqueda, $categoria): bool {
            $coincideTexto = $busqueda === ""
                || stripos($producto["codigo"], $busqueda) !== false
                || stripos($producto["nombre"], $busqueda) !== false
                || stripos($producto["categoria"], $busqueda) !== false;
            $coincideCategoria = $categoria === "" || $producto["categoria"] === $categoria;

            return $coincideTexto && $coincideCategoria;
        }
    ));
}
