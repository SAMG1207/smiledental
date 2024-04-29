<?php
session_start();
include '../src/includes/autoloader.inc.php';
if($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['dniM'])){
    $dentist = new Dentist();
    $dni = $_POST['dniM'];
    $registro = $dentist->alreadyIn($dni);
    echo json_encode(["registrado" => (bool)$registro]);
}

