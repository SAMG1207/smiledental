<?php
session_start();
include '../src/includes/autoloader.inc.php';
if($_SERVER['REQUEST_METHOD'] ==="GET" && isset($_GET['fecha'])){
    $fecha = $_GET['fecha'];
    $hora = new Hora();
    $horasDisponibles = $hora->getHoraLibrePorNumero($fecha);
    echo json_encode($horasDisponibles);
}else{
    json_encode(["error" => "No se proporcionó la fecha en la solicitud."]);
}