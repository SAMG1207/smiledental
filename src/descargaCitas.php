<?php
session_start();
include 'includes/autoloader.inc.php';
if(!isset($_SESSION['dentist'])){
    header('location: loginDentist.php');
    exit();
}
/**
 * Este documento simplemente existe para poder descargar la información requerida en Excel
 * This document simply exists to make posible to the user to download the required information in Excel
 */
if (isset($_GET['id_cita'])) {
    $cita = new Cita();
    $resultado = $cita->selectAllFromCitas($_GET['id_cita']);

    if ($resultado && !empty($resultado['archivo'])) {
        $nombreArchivo = 'cita' . $_GET['id_cita'] . '.pdf';
        $datosArchivo = $resultado['archivo'];

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $nombreArchivo . '"');
        header('Content-Length: ' . strlen($datosArchivo));

        echo $datosArchivo;
        exit(); // Detener la ejecución después de enviar el archivo
    } else {
        echo '<script>alert("Archivo no encontrado."); window.history.back();</script>';
        exit();
    }
}
