<?php
session_start();
include 'includes/autoloader.inc.php';
if(isset($_SESSION['dentist'])){
$cita = new Cita();   
$date = date('Y-m-d');
$citas = $cita->fechaCitasDent($_SESSION['dentist_id'], $date)? $cita->fechaCitasDent($_SESSION['dentist_id'], $date) : "";
$historico = $cita->historicoCitas($_SESSION['dentist_id']);


if(isset($_POST['accion'])){
    $_SESSION=[];
    session_destroy();
    header('Location: loginDentist.php');
    exit();
}

}else{
    //SI NO HAY SESION ACTIVA TE REDIRIGE
    header('location: loginDentist.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/perfiles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Área Dentista SmileDental</title>
</head>
<body>
<header class="container-fluid bg-light">
 <div class="row align-items-center p-3">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center d-block "> 
     <h1> Área Dentista Smile Dental</h1>
     <h3>Bienvenido Dr. <?php echo $_SESSION['dentist'];?></h3>
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <input type="hidden" name="accion">
        <input type="submit" value="Volver">
     </form>
    
    </div>
 </div>
</header>
<div class="container-fluid">
    <div class="row bg-primary text-center border border-dark" id="main">
            <a href="#"><h3>Citas</h3></a>
    </div>
    <div id="citas">
       <div class="row border border-dark text-center bg-info lista" >
       <a href="#"><h3>Próximas Citas</h3></a>
       </div>
       <div class="row border border-dark text-center bg-info lista" >
       <a href="#"><h3>Histórico Citas</h3></a>
       </div>
       <div class="row border border-dark text-center bg-info lista" >
       <a href="#"><h3>Citas por paciente</h3></a>
       </div>
    </div>
</div>

<section class="principal position-relative p-5">
    <!-- Para ver las próximas citas  -->
<div class="container-fluid" id="proximo">
    <div class="row justify-content-center" >
        <div class="col-6 bg-light p-3 mt-5 text-center">
  <h2 class="text-center">Próximas Citas para <?php echo $_SESSION['dentist'];?></h2>
        <?php
if (!empty($citas)) {
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>#</th>";
    echo "<th scope='col'>ID Cita</th>";
    echo "<th scope='col'>Fecha</th>";
    echo "<th scope='col'>Hora</th>";
    echo "<th scope='col'>Paciente</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $counter = 1;
    foreach ($citas as $cita) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>\n";
        echo "<td>" . $cita['id_cita'] . "</td>\n";
        echo "<td>" . $cita['fecha'] . "</td>\n";
        echo "<td>" . $cita['hora'] . ":00</td>\n";
        echo "<td>" . $cita['id_paciente'] . "</td>\n";
        echo "</tr>";
        $counter++;
    }

    echo "</tbody>";
    echo "</table>";
}
?>

        </div>
    </div>
</div>


<div class="container-fluid" id="historico">
    <!-- Para ver el histórico citas  -->
    <div class="row justify-content-center" >
        <div class="col-8 bg-light p-3 mt-5 text-center">
  <h2 class="text-center">Histórico de citas de <?php echo $_SESSION['dentist'];?></h2>
        <?php
if (!empty($historico)) {
    echo "<table class='table'>";
    echo "<thead>";
    echo "<tr>";
    echo "<th scope='col'>#</th>";
    echo "<th scope='col'>ID Cita</th>";
    echo "<th scope='col'>Fecha</th>";
    echo "<th scope='col'>Hora</th>";
    echo "<th scope='col'>Paciente</th>";
    echo "<th scope='col'>Subir Documentación</th>";
    echo "<th scope='col'>Obtener Documentación</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    $citas = new Cita();
    $counter = 1;
    foreach ($historico as $hist) {
        echo "<tr>";
        echo "<td>" . $counter . "</td>\n";
        echo "<td>" . $hist['id_cita'] . "</td>\n";
        echo "<td>" . $hist['fecha'] . "</td>\n";
        echo "<td>" . $hist['hora'] . ":00</td>\n";
        echo "<td>" . $hist['id_paciente'] . "</td>\n";
         
        // Verificar si la cita tiene un informe adjunto
        $informeAdjunto = $citas->selectAllFromCitas($hist['id_cita']);
        if ($informeAdjunto && !empty($informeAdjunto['archivo'])) {
            echo "<td><p>Esta cita ya tiene informe</p></td>\n";
        } else {
            echo "<td><a href='infoCitas.php?id_cita=" . $hist['id_cita'] . "'>Adjuntar Información</a></td>\n";
        }
        
        echo "<td><a href='descargaCitas.php?id_cita=" . $hist['id_cita'] . "' style='color: blue;'>Descargar</a></td>\n";
        echo "</tr>";
        $counter++;
    }

    echo "</tbody>";
    echo "</table>";
}
?>

        </div>
    </div>
</div>


    <div id="main-pacientes">
      <div class="container-fluid" id="pacientes">
        <div class="row justify-content-center text-center" >
         <div class="col-md-6 bg-light mt-4">
           <h2>Buscador de Citas por DNI de paciente registrado</h2>
           <div>
            <label for="dni-paciente">DNI del paciente</label>
            <input type="text" name="dni-paciente" id="dniMainPacientes">
            <button id ="verificaMainPacientes">Verificar</button>
            <p id="parrafoVerifica"></p>
           </div>
           <div id ="tabla" class="d-flex justify-content-center align-items-center">
              <div id="contenedorTabla">

              </div>
           </div>
        </div>
       </div>
      </div>
    </div>

</section>
<script>
    // Para que las opciones aparezcan al presionar el boton
let lista = document.querySelectorAll(".lista");
let proximo = document.getElementById("proximo");
   proximo.style.display="none";
let historico = document.getElementById("historico");
  historico.style.display ="none";
let pacientes = document.getElementById("pacientes");
   pacientes.style.display="none";

let opciones = [proximo, historico, pacientes];

lista.forEach((elemento, index) => {
    elemento.addEventListener("click", function() {
        opciones.forEach((opcion, i) => {
            if (i === index) {
                opcion.style.display = "block";
            } else {
                opcion.style.display = "none";
            }
        });
    });
});
 
let main = document.getElementById("main");
let citas = document.getElementById("citas");
citas.style.display = "none";
// Esta es la lógica de esa funcion
let contador = 0;
main.addEventListener("click", function(){
  contador % 2==0?citas.style.display="block" : citas.style.display="none";
  contador++;
});

    //VER CITAS POR PACIENTES__________________________________________________
    let dniMainPacientes = document.getElementById("dniMainPacientes");
    let verificaMainPacientes = document.getElementById("verificaMainPacientes");
    let parrafoMainPacientes = document.getElementById("parrafoVerifica");
    let tabla = document.getElementById("tabla");
    tabla.style.display ="none";
    let registered = false;


    verificaMainPacientes.addEventListener("click", function(){
      var dniVerificado = dniMainPacientes.value;
      parrafoMainPacientes.textContent="";
      if(validarDNI(dniVerificado)===true){
        var xhrV = new XMLHttpRequest();
        xhrV.open('POST', '../Ajax/getPacientes.php', true);
        xhrV.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhrV.onreadystatechange = function(){
          if(xhrV.readyState == 4 && xhrV.status == 200){
              var responseVe = JSON.parse(xhrV.responseText);
              if (responseVe.registrado) {
                  parrafoMainPacientes.innerHTML = '<p style="color: green;">El paciente está registrado.</p>';
                  tabla.style.display = "block";
                  registered = true;
  
                  if(registered === true){
                      var xhrTabla = new XMLHttpRequest();
                      xhrTabla.open('POST', '../Ajax/getCitas.php', true);
                      xhrTabla.onreadystatechange = function(){
                          if(xhrTabla.readyState == 4 && xhrTabla.status == 200){
                              var responseTabla = JSON.parse(xhrTabla.responseText);
                              if (responseTabla.registrado) { // Aquí se usa el atributo 'registrado' de la respuesta de getCitas.php
                                  var tablaGenerada = responseTabla.fechas;
                                  var contenedorTabla = document.getElementById('contenedorTabla');
                                  contenedorTabla.innerHTML = '';
                                  if(tablaGenerada && tablaGenerada.length > 0){
                                      document.getElementById('contenedorTabla').innerHTML = JSON.stringify(tablaGenerada);
                                     
                                      var tableHTML = '<table class="m-2"><thead><tr><th class="p-2">ID Cita</th><th class="p-2">ID Paciente</th><th class="p-2">ID Dentista</th><th class="p-2">Fecha</th><th class="p-2">Hora</th><th class="p-2">Descargar</th></tr></thead><tbody>';
                                        for (var i = 0; i < tablaGenerada.length; i++) {
                                        var cita = tablaGenerada[i];
                                        tableHTML += '<tr>';
                                        tableHTML += '<td class="border border-primary border-2">' + cita.id_cita + '</td>';
                                        tableHTML += '<td class="border border-primary border-2">' + cita.id_paciente + '</td>';
                                        tableHTML += '<td class="border border-primary border-2">' + cita.id_dentista + '</td>';
                                        tableHTML += '<td class="border border-primary border-2">' + cita.fecha + '</td>';
                                        tableHTML += '<td class="border border-primary border-2">' + cita.hora + '</td>';
                                        tableHTML += '<td class="border border-primary border-2"><a href="descargaCitas.php?id_cita=' + cita.id_cita + '" style ="color: blue">Descargar</a></td>';
                                        tableHTML += '</tr>';
                                    }
                                      
                                      tableHTML += '</tbody></table>';
                                      contenedorTabla.innerHTML = tableHTML;
                                  }else{
  
                                  }
                                
                              }
                          }
                      };
                      xhrTabla.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                      xhrTabla.send('dni='+ encodeURIComponent(dniVerificado));
                  }
              } else {
                  parrafoMainPacientes.innerHTML = '<p style="color: red;">El paciente no está registrado.</p>';
                  tabla.style.display = "none";
                  registered = false;
              }
          }
        }
        xhrV.send('dni=' + encodeURIComponent(dniVerificado));
        
      }else{
      
        parrafoMainPacientes.textContent="El DNI no tiene formato válido"
        tabla.style.display = "none";
        contenedorTabla.innerHTML = '';
        verificaMainPacientes.preventDefault();
      }
  
    });

    function validarDNI(dni) {
    const formatoDNI = /^[0-9]{8}[a-zA-Z]$/;
    const formatoNIE =/^[YXYZ]\d{7}[A-Za-z]$/;
    if(formatoDNI.test(dni)){
        return true;
    }else{
        return formatoNIE.test(dni);
    }
}

</script>
</body>
</html>