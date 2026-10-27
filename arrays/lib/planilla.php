<?php

declare(strict_types=1);

function obtenerEmpleados(): array
{
    return array(
        array("legajo" => 1001, "nombre" => "Ana Martínez", "dni" => "28456321", "sueldo_base" => 850000.00, "profesion" => "Arquitecto"),
        array("legajo" => 1002, "nombre" => "Luis Fernández", "dni" => "30124785", "sueldo_base" => 780000.00, "profesion" => "Ingeniero"),
        array("legajo" => 1003, "nombre" => "María Gómez", "dni" => "27654198", "sueldo_base" => 720000.00, "profesion" => "Contador"),
        array("legajo" => 1004, "nombre" => "Pablo Ruiz", "dni" => "33412876", "sueldo_base" => 690000.00, "profesion" => "Diseñador"),
        array("legajo" => 1005, "nombre" => "Sofía Torres", "dni" => "29873164", "sueldo_base" => 910000.00, "profesion" => "Ingeniero")
    );
}

function calcularAporteAfp(float $sueldoBase): float
{
    return round($sueldoBase * PORCENTAJE_AFP, 2);
}

function calcularBonificacion(array $empleado): float
{
    if (!in_array($empleado["profesion"], PROFESIONES_BONIFICADAS, true)) {
        return 0.0;
    }

    return round($empleado["sueldo_base"] * PORCENTAJE_BONIFICACION, 2);
}

function calcularBoletaPago(array $empleado): array
{
    $aporteAfp = calcularAporteAfp((float) $empleado["sueldo_base"]);
    $bonificacion = calcularBonificacion($empleado);

    return array(
        "sueldo_base" => (float) $empleado["sueldo_base"],
        "aporte_afp" => $aporteAfp,
        "bonificacion" => $bonificacion,
        "sueldo_neto" => (float) $empleado["sueldo_base"] - $aporteAfp + $bonificacion
    );
}
