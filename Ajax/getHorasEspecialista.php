<?php
include '../src/includes/autoloader.inc.php';

if($_SERVER['REQUEST_METHOD'] ==="GET" && isset($_GET['id'])){
$dentist = new Dentist();
$array = $dentist->selectDisponibilidadNoGeneral($_GET['id']);
echo json_encode($array);
}