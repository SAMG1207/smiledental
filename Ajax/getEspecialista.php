<?php
session_start();
include '../src/includes/autoloader.inc.php';
if($_SERVER['REQUEST_METHOD']==='POST'&& isset($_POST['fecha']) && isset($_POST["id_dentista"])){
    $fecha = $_POST['fecha'];
    $id= $_POST['id_dentista'];

    // imprime los valores para verificar
    error_log('Fecha: ' . $fecha);
    error_log('ID Dentista: ' . $id);

    $cita = new Cita();
    $horasOcupadas = $cita->buscaCitaEspecialista($id, $fecha);
    echo json_encode($horasOcupadas);
} else {
    echo json_encode(["error" => "No se proporcionó la fecha en la solicitud."]);
}