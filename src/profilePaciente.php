<?php
session_start();
include 'includes/autoloader.inc.php';
$paciente = new Paciente();
$cita = new Cita();
$dentist = new Dentist();
$hours = new Hora();
if(!isset($_SESSION['username'])){
    //SI no ha iniciado sesión será reedirigido si intenta acceder
    echo"No ha iniciado sesión, no puede acceder";
    header('Location: loginPaciente.php');
    exit();
}else{
    if(isset($_POST['accion'])){
        $_SESSION=[];
        session_destroy();
        header('Location: loginPaciente.php');
        exit();
    }
   
    $correo = $_SESSION['email'];
    
     $id_paciente = $paciente->getIdPaciente($correo);
    
    if ($_SERVER['REQUEST_METHOD'] === "GET" && isset($_GET['fecha'], $_GET['hora'])){

        $fechaSolicitada = $_GET['fecha'];
        $hora = $_GET['hora'];
        $hoy = strtotime(date('Y-m-d'));
        $ultimaCita = $cita->seleccionaUltimaCita($id_paciente);
        $fechaUltimaCita = strtotime($ultimaCita['MAX(fecha)']);
        $fechaCita = $cita->getFecha($id_paciente);
        
      
     
    
        if (!$cita->tieneCitaGeneral($id_paciente) || $hoy > $fechaSolicitada || $fechaUltimaCita < $hoy) {
           
        $citasOcupadas = $hours->revisaCitas($fechaSolicitada, $hora);
      
        $odontologos = $hours->getIdGeneral($hora);
      
        $dentista= array_diff($odontologos, $citasOcupadas);
        $cita1 = new Cita();
        foreach($dentista as $dent){
            $cita1->insertCita($id_paciente, $dent, $fechaSolicitada, $hora);
            break;
        }
    
     }else{
        
        echo "<script> alert('Usted tiene una cita ya registrada el día: " . date('d-m-Y', strtotime($fechaCita)) . "')</script>";
        
       
          
        }

       }
       
    }

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/perfiles.css">
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Perfil Paciente</title>
</head>
<body>
    
<header class="container-fluid bg-light p-3">
 <div class="row align-items-center">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center d-block "> 
     <h1> Área Paciente Smile Dental</h1>
     <span>Bienvenido <?php echo $_SESSION['username'];?></span>
     <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <input type="hidden" name="accion">
        <input type="submit" value="Volver" class="bg-primary p-1 mt-1">
     </form>
    </div>
 </div>
</header>

<section class="principal">
    <p class="text-center text-white p-2 ">Bienvenido! <?php echo $_SESSION['username']?>, Estás en el apartado del paciente.</p>
    <p class="text-center text-white mx-5">En este apartado podrás reservar citas con odontólogo general, en caso de querer ser atendido por un
        especialista, será DentalSmile quien te lo asignará despues de haber sido evaluado por nuestros profesionales
    </p>
<div class="container-fluid">
    <div class="row justify-content-center">
      <div class="col-sm-4 bg-light p-3 mt-5 ">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="GET" class="form">
            <div class="form-group">
                    <h3 class="text-center">Solicitud de Cita</h3>
                    <p>El paciente sólo puede tener una cita asignada, una vez haya sido atendido podrá seleccionar la siguiente cita</p>
                    <div class="mt-1">
                    <label for="fecha">Seleccione la fecha:</label>
                    <input type="date"  name="fecha" id="fecha">
                    <p id="aviso" class="non-alert" style="color: red">Debe escoger sólo días entre semana</p>
                    </div>
                    <div id ="horarioCita">
                    <div class="mt-1">
                    <label for="hora">Seleccione el horario:</label>
                    <select name="hora" id="hora">
                    </select>
                    </div>
                    <div class="mt-2 text-center">
                        <input type="submit" value="Solicitar Cita" name="botonCita">
                    </div>
                    </div>                
            </div>
        </form>
      </div>
   </div>
</div>

<div class="col-6 mx-auto mt-5">
    <?php
    $query1 = new Query();
    $correo = $_SESSION['email'];
    $id_paciente = $paciente->getIdPaciente($correo);
    $hoy = date('Y-m-d');
    $citas = $cita->fechaCitasId($id_paciente, $hoy);
   
    if (!empty($citas)) {
        ?>
        <table class="table" id="altas">
            <thead>
                <h2 class ="bg-light mb-0 text-center">Próxima Cita</h2>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                    <th scope="col">Acción</th>
                </tr>
            </thead>
            <tbody class="border border-dark">
                <?php
               
                    echo "<tr>";
                    echo "<td>" . $citas['fecha'] . "</td>\n";
                    echo "<td>" . $citas['hora'] . ":00</td>\n";
                    echo "<td><a href ='profilePaciente.php?Borrar=".$citas['id_cita']."'>Borrar Cita</a><p>Una vez borrada la cita no se puede recuperar</p>\n";
                    echo"</tr>\n";
	                if(isset($_GET['Borrar'])){
                        $id_cita_a_borrar = $_GET['Borrar'];
                        $cita->borrarCita($citas['id_cita']);
                        header('Location: profilePaciente.php');
                        exit();
                    }
                    ?>
            </tbody>

        </table>
    <?php
    } else {
        echo "<p class='text-center text-white'> No Tiene Citas Reservadas Aún <p>";
    }
    ?>
</div>

<div class="col-6 mx-auto mt-5 p-5">
    <?php
    $historico = $cita->tieneCitaGeneral($id_paciente);
   
    if (!empty($historico)) {
        ?>
        <table class="table p-5" id="altas">
            <thead>
            <h2 class ="bg-light text-center">Histórico Citas</h2>
                <tr>
                    <th scope="col">Fecha</th>
                    <th scope="col">Hora</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    for( $i = 0; $i < count($historico); $i++ ) {
                    echo "<tr class>";
                    echo "<td>" . $historico[$i]['fecha'] . "</td>\n";
                    echo "<td>" . $historico[$i]['hora'] . ":00</td>\n";
                    echo "</tr>";
                    }
                ?>
            </tbody>
        </table>
    <?php
    } else {
        echo "<p class='text-center text-white'> No Ha Tenido Citas Aún <p>";
    }
    ?>
</div>
</section>
<script>
    var dateInput = document.getElementById("fecha");
    document.addEventListener("DOMContentLoaded", function(){
        var dtToday = new Date();
      var hora=  dtToday.getHours();
      console.log(hora);
    var month = dtToday.getMonth() + 1;
    var day = dtToday.getDate();
   
    var year = dtToday.getFullYear();
    if (month < 10)
        month = '0' + month.toString();
    if (day < 10)
        day = '0' + day.toString();
    
    var minDate = year + '-' + month + '-' + day;
    
    var fecha = document.getElementById("fecha");
    fecha.setAttribute("min", minDate);
    var newmonth;
    
    if(month<=9){
      newmonth = month + 3;
    }else{
        newmonth = month - 9
        year++;

        if (newmonth < 1) {
    newmonth = 12 - (Math.abs(newmonth) % 12);
    year--;
  
    }
}     
if (newmonth < 10) {
    newmonth = '0' + newmonth.toString();
}
    var maxDate = year + '-' + newmonth + '-' + day;
    fecha.setAttribute("max", maxDate);

   var aviso = document.getElementById("aviso");
   function noWeekends(){
    dateInput.addEventListener("input", function(){
        var selectedDate = new Date(this.value);
        var diaSemana = selectedDate.getDay();

        if (diaSemana === 0 || diaSemana === 6) {
            this.value = "";
            aviso.classList.remove("non-alert");
            aviso.classList.add("alert");
        } else {
            aviso.classList.remove("alert");
            aviso.classList.add("non-alert");

            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status == 200) {
        try {
            updateHoras(JSON.parse(xhr.responseText));
        } catch (error) {
            console.error("Error al parsear JSON: " + error);
        }
    }     else if (xhr.readyState == 4) {
        console.error("Error en la solicitud AJAX. Código de estado: " + xhr.status);
    }
};

xhr.open("GET", "../Ajax/getFree.php?fecha=" + this.value, true);
xhr.send();

        }
    });
}

    function updateHoras(horasDisponibles) {
    var selectHora = document.getElementById("hora");
    selectHora.innerHTML = "";

    for (var key in horasDisponibles) {
        if (horasDisponibles.hasOwnProperty(key)) {
            var hora = horasDisponibles[key];
            var option = document.createElement("option");
            option.value = hora;
            option.textContent = hora + ":00";
            selectHora.appendChild(option);
        }
    }
}

    noWeekends();
    var hora = document.getElementById("horarioCita");
    hora.style.display="none";
     dateInput.addEventListener("change",function(){
    
    if(dateInput.value === ""){
        
        hora.style.display="none";
        
    }else{
        hora.style.display="block";
    }
    });
  })
   
</script>
</body>
</html>