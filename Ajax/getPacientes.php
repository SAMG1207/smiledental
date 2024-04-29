<?php
session_start();
include '../src/includes/autoloader.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['dni'])) {
    $dni = $_GET['dni'];
    $paciente = new Paciente();
    $registro = $paciente->alreadyIn($dni);
    
    // Devuelve true si está registrado, false si no lo está
    echo json_encode(["registrado" => (bool)$registro]);
} else {
    echo json_encode(["error" => "No se proporcionó la fecha en la solicitud."]);
}

