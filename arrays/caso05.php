<?php
$empleado[0]=4371;
$empleado[1]="Martínez Leandro";
$empleado[2]="27643742";
$empleado[3]=1429.54;
$empleado[4]="Arquitecto";

echo "Legajo: ".$empleado[0]."<br>";
echo "Nombre: ".$empleado[1]."<br>";
echo "D.N.I: ".$empleado[2]."<br>";
echo "Sueldo: ".$empleado[3]."<br>";
echo "Profesión: ".$empleado[4]."<br>";
//Foreach
foreach($empleado as $dato){
    echo $dato."<br>";
}
//For
for ($i=0;$i<count($empleado);$i++){
    echo $empleado[$i]."<br>";
}
?>