<?php
session_start();
include 'includes/autoloader.inc.php';
if(!isset($_SESSION['secretario'])){
    header("location: loginSecretario.php");
    exit();}
$dentist = new Dentist();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Lista Dentistas</title>
    <script src="javascript/table2excel.js"></script>
</head>
<body>
<div class="container-fluid bg-primary">
 <div class="row align-items-center">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center d-block "> 
     <h1> Área Secretario Smile Dental</h1>
     <span>Lista de Dentistas</span>
     <a href="profileSecretario.php" class ="d-block">Volver</a>
    </div>
 </div>
</div>
<section class="principal container-fluid ">
    <div class=" col-6 mx-auto">
    <table class="table " id="altas">
    <thead class="mt-4">
        <tr>
         <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
           <th scope="col">DNI</th>
          <th scope="col">Colegiado</th>
          <th scope="col">Telefono</th>
          <th scope="col">Especialidad</th>
          <th scope="col">Fecha de Alta</th>
        </tr>
    </thead>
    <tbody>
        <?php
       
        $row = array();
        $row = $dentist->printDentist();
       for($i = 0; $i < count($row);$i++) {
        echo "<tr>\n";
        echo "<td>" . $row[$i]['id_odontologo'] . "</td>\n";
        echo "<td>" . $row[$i]['nombre'] . "</td>\n";
        echo "<td>" . $row[$i]['apellido'] . "</td>\n";
        echo "<td>" . $row[$i]['dni'] . "</td>\n";
        echo "<td>" . $row[$i]['nro_colegiado'] . "</td>\n";
        echo "<td>" . $row[$i]['telefono'] . "</td>\n";
        echo "<td>" . $row[$i]['especialidad'] . "</td>\n";
        echo "<td>" . $row[$i]['fecha_alta'] . "</td>\n";
        echo "</tr>";
    }
        ?>
    </tbody>
   </table>
   <button id="download">Descargar Excel Altas</button>
 </div>

 <div class="col-6 mx-auto">
 <table class="table mt-3" id="bajas">
    <thead>
        <tr>
         <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
           <th scope="col">DNI</th>
          <th scope="col">Fecha de Baja</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        $row1 = array();
        $row1 = $dentist->printDentistBaja();
       for($i = 0; $i < count($row1);$i++) {
        echo "<tr>\n";
        echo "<td>" . $row1[$i]['id_odontologo'] . "</td>\n";
        echo "<td>" . $row1[$i]['nombre'] . "</td>\n";
        echo "<td>" . $row1[$i]['apellido'] . "</td>\n";
        echo "<td>" . $row1[$i]['dni'] . "</td>\n";
        echo "<td>" . $row1[$i]['fecha_baja'] . "</td>\n";
        echo "</tr>";
    }
        ?>
    </tbody>
   </table>
   <button id = "downloadBaja">Descargar Excel Bajas</button>
 </div>
</section>
<script>
 
    document.getElementById("download").addEventListener("click",function(){
           var tablaExcel = new Table2Excel();
           tablaExcel.export(document.querySelectorAll("#altas"), "Altas");
    })

    document.getElementById("downloadBaja").addEventListener("click",function(){
           var tablaExcel = new Table2Excel();
           tablaExcel.export(document.querySelectorAll("#bajas"), "Bajas");
    })
</script>
</body>
</html>