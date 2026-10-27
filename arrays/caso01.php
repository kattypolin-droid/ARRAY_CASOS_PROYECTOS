<?php
$dia[0] = "Domingo";
$dia[1] = "Lunes";
$dia[2] = "Martes";
$dia[3] = "Miércoles";
$dia[4] = "Jueves";
$dia[5] = "Viernes";
$dia[6] = "Sábado";

for ($i =0;$i<count($dia); $i++){
    echo $dia[$i]."<br>";

}

foreach ($dia as $nombreDia) {
    echo $nombreDia . "<br>";
}
?> 