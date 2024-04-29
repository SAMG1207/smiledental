<?php
session_start();
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET["fecha"])) {
    $fecha = $_GET['fecha'];
    $citas = new Cita();
    $fechas = $citas->getDisponibildad($fecha);
    if($fecha){
        echo json_encode(["fechas" => $fechas]);
    }
     else {
    echo json_encode(["registrado" => false, "error" => "No hay citas"]);
}

      
}