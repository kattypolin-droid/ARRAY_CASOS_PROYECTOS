<?php
$estacion[0]="Primavera";
$estacion[1]= "Verano";
$estacion[2]= "Otoño";
$estacion[3]= "Invierno";


echo$estacion[0]."<br>";
echo$estacion[3];
echo "<hr>";

$ciudad[]="Sevilla";
$ciudad[]= "Madrid";
$ciudad[]= "Barcelona";
$ciudad[]= "Valencia";

echo $ciudad[2];
echo "<hr>";

$color=array("azul","verde","negro","marron") ;
echo $color[1];
echo "<hr>";
echo " El x de ciudades principales de España son: ",count($ciudad);

echo "<hr>";
for($i= 0;$i<4;$i++){
    echo strtoupper($color[$i])," ";
}

echo "<hr>";
foreach($color as $colorcito){
   echo strtoupper($colorcito)," ";
}

echo"<hr>";
$colores_vehiculos=array("coche"=>"Rojo","moto"=>"Verde","avion"=>"Amarillo");
echo $colores_vehiculos["moto"];

?>