<?php
session_start();
include 'includes/autoloader.inc.php';
if(!isset($_SESSION['secretario'])){
    header("location: loginSecretario.php");
    exit();}
$paciente = new Paciente();

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/perfiles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Lista Pacientes</title>
    <script src="javascript/table2excel.js" defer></script>
</head>
<body>
<div class="container-fluid bg-primary">
 <div class="row align-items-center">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center d-block "> 
     <h1> Área Secretario Smile Dental</h1>
     <span>Lista de Pacientes</span>
     <a href="profileSecretario.php" class ="d-block">Volver</a>
    </div>
 </div>
</div>
<section class="principal">
<div class="container-fluid" id="patient">
        <div class="row">
        <div class="col-6 mx-auto">
    <table class="table mt-5" id="altas">
    <thead class="thead">
        <tr>
         <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Teléfono</th>
           <th scope="col">DNI</th>
          <th scope="col">Correo</th>
        </tr>
    </thead>
    <tbody>
        <?php
       if(isset($_SESSION['paciente'])){
        $row = array();
        $row = $paciente->searchPacienteBynName($_SESSION['paciente']);
       for($i = 0; $i < count($row);$i++) {
        echo "<tr>\n";
        echo "<td>" . $row[$i]['id_paciente'] . "</td>\n";
        echo "<td>" . $row[$i]['nombre'] . "</td>\n";
        echo "<td>" . $row[$i]['apellido'] . "</td>\n";
        echo "<td>" . $row[$i]['telefono'] . "</td>\n";
        echo "<td>" . $row[$i]['dni'] . "</td>\n";
        echo "<td>" . $row[$i]['correo'] . "</td>\n";
        echo "</tr>";
    }
       }
       
        ?>
    </tbody>
   </table>
   <button id="download">Descargar Excel Pacientes</button>
 </div>
        </div>
    
    </div>

  <button id="ver" class="align-center p-2">Ver Todos los pacientes</button>

   
    <div class="container-fluid mt-5" >
    
        <div class="row justify-content-center">
       
        <div class="col-6 mx-auto">
    <table class="table mt-5" id="todos">
    <thead class=>
        <tr>
         <th scope="col">ID</th>
          <th scope="col">Nombre</th>
          <th scope="col">Apellido</th>
          <th scope="col">Teléfono</th>
           <th scope="col">DNI</th>
          <th scope="col">Correo</th>
        </tr>
    </thead>
    <tbody>
        <?php
       
        $row = array();
        $row = $paciente->printPacientes();
       for($i = 0; $i < count($row);$i++) {
        echo "<tr>\n";
        echo "<td>" . $row[$i]['id_paciente'] . "</td>\n";
        echo "<td>" . $row[$i]['nombre'] . "</td>\n";
        echo "<td>" . $row[$i]['apellido'] . "</td>\n";
        echo "<td>" . $row[$i]['telefono'] . "</td>\n";
        echo "<td>" . $row[$i]['dni'] . "</td>\n";
        echo "<td>" . $row[$i]['correo'] . "</td>\n";
        echo "</tr>";
    }
        ?>
    </tbody>
   </table>
   <button id="download0">Descargar Excel Pacientes</button>
 </div>
        </div>
    
    </div>
    
</section>
<script>
    document.getElementById("download").addEventListener("click",function(){
           var tablaExcel = new Table2Excel();
           tablaExcel.export(document.querySelectorAll("#patient"), "Paciente");
    })
    document.getElementById("download0").addEventListener("click",function(){
           var tablaExcel = new Table2Excel();
           tablaExcel.export(document.querySelectorAll("#todos"), "AltasPacientes");
    })

    let lista = document.getElementById("todos");
    lista.style.display = "none";
    var i = 1;
    let ver = document.getElementById('ver');
    ver.addEventListener("click", function(){
        i++;
    if(i % 2 ==0){
        lista.style.display="block";
    }else{
        lista.style.display="none";
    }
    });
</script>
</body>
</html>