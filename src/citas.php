<?php
session_start();
include 'includes/autoloader.inc.php';
$citaConcertada="";
$query = new Query();
$paciente = new Paciente();
$cita = new Cita();
$dentista = new Dentist();
$regex_dni_nie = '/^([XYZ]?\d{7,8}|[A-Z]\d{7,8})[A-Z]$/';
$rows=$dentista->selectDentistGeneral();
$array_id=[];
foreach ($rows as $row) {
  
  $array_id[$row["nombre"]] = $row["id_odontologo"];
}

$rowsNotGeneral =$dentista->selectDentistNotGeneral();
$array_ng=[];
$array_noGeneral=[];
foreach ($rowsNotGeneral as $ng) {
  $array_ng[$ng["id_odontologo"]] = $ng["especialidad"];
}

foreach ($array_ng as $id_dentista => $especialidad) {
  if (!in_array($id_dentista, $array_id)) {
      $fila = $dentista->selectDisponibilidadNoGeneral($id_dentista);
      $array_noGeneral[$id_dentista] = $fila;
  }
}

$citaConcertada = false;
if(!isset($_SESSION['secretario'])){
 header("location: loginSecretario.php");
exit();}

  if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["registrar"])){
    $dni = $query->test_input($_POST['dniPaciente'])? $_POST['dniPaciente']:"";
    if(!empty($dni) && preg_match($regex_dni_nie, $dni)){
      $id_paciente = $paciente->alreadyIn($dni)["id_paciente"];
      if($id_paciente!==false || $id_paciente !== 0){
        $fecha = $_POST['fecha'];
        $value= $_POST['dentista'];
        $id_dentista = $array_id[$value];
        $hora = $_POST['hora'];
        $insercion = $cita->insertCita($id_paciente, $id_dentista, $fecha, $hora);
        if($insercion){
          $citaConcertada = "Cita Reservada con exito";
        }
      }else{
        $citaConcertada="El DNI o NIE no está registrado";
      }
    }else{
      $citaConcertada ="Error en el dni introducido, no se ha podido reservar";
    }

  }if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["reservar"])){
    $dni = $query->test_input($_POST['dni'])? $_POST['dni']:"";
     if(!empty($dni) && preg_match($regex_dni_nie, $dni)){
      $id_paciente = $paciente->alreadyIn($dni)["id_paciente"];
      if($id_paciente!==false || $id_paciente !== 0){
        $fecha = $_POST['fecha-cita'];
        $value= $_POST['tipo'];
        $hora = $_POST['hours'];
        $insercion = $cita->insertCita($id_paciente, $value, $fecha, $hora);
      }else{
        $citaConcertada="El DNI o NIE no está registrado";
      }
     }else{
      $citaConcertada ="Error en el dni introducido, no se ha podido reservar";
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head> 
    <title>SmileDental - Gestión de Citas</title>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/perfiles.css">
    <!-- <script src="javascript/citas.js" defer></script> -->
    <?php echo "<script>let correcto = " . json_encode($citaConcertada) . ";</script>"; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
<div class="container-fluid bg-light">
 <div class="row align-items-center">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center "> <h1> Área Secretario Smile Dental</h1><p>Citas</p></div>
 </div>
</div>
<nav class="navbar bg-dark text-center" data-bs-theme="dark">
<div class="container-fluid text-center">
    <a class="navbar-brand " href="#">Menú de Navegación</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!-- Menu de navegación -->
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link active lista" aria-current="page" href="#" class="lista">Nueva Cita</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active lista" aria-current="page" href="#" class="lista">Citas por Paciente</a>
        </li>
        <li class="nav-item">
          <a class="nav-link active lista" aria-current="page" href="#" class="lista">Citas por Fecha</a>
        </li>
      </ul>
    </div>
  </div>
</nav>
<div class="container-fluid" id="concertado">
  <div class="row bg bg-dark">
     <p class="text-center text-light" id="concertado1"><?php 
     if($citaConcertada!=="")echo $citaConcertada?>
     </p>
  </div>
</div>
<section class ="principal">
  <div class="container listaRef">
   <div class="row ">
       <div class="mt-3 col-6 bg-light mx-auto text-center">
         <h1>Registro de citas de emergencia</h1>
         <ul> 
            <li>Se puede registrar retroactivamente</li>
            <li>Registrar al paciente en <a href="register.php" target="_blank" class="primary">este enlace</a> si no estuviera registrado</li>
            <li>La contraseña será en estos casos: 'nombrePaciente01!!', luego él podrá cambiarla</li>
            <li>Rellene los datos de la cita:</li>
        </ul>
        <div class="datos">
          <h2>Datos de la cita:</h2>
          <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST">
            <div class="form-group">
              <label for="fecha">Fecha de la cita</label>
              <input type="date" name="fecha" id="fecha">
              <p id="avisoFecha"></p>
            </div>
            <div class="form-group my-1" id="inputDni">
              <label for="dniPaciente">DNI del paciente</label>
              <input type="text" name="dniPaciente" id="dniPaciente">
              <p id="aviso">El paciente está <span id="registrado" class="bg-danger">registrado</span></p>
            </div>
            <div class="form-group my-1" id="divHora">
              <label for="hora">Hora de la cita</label>
              <select name="hora" id="hora">
                    <?php
                    for($i = 8; $i < 21; $i++){
                        echo "<option class='h-cita' value=".$i.">".$i.":00 </option>";
                    }
                    ?>
              </select>
            </div>
            <div class="form-group my-2" id="divDentista">
              <label for="dentista">Ha sido atendido por:</label>
              <select name="dentista" id="options">
              <option disabled selected>Selecciona una opción</option>
              </select>
            </div>
            <div class="form-group my-2">
              <input type="submit" value="registrar" id="registrar" name="registrar" class="mx-auto">
            </div>
          </form>
        </div>
       </div>
    </div>
  
    <div class="row">
    <div class="col-6 mx-auto text-center">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="mt-4 p-3 bg-light overflow-hidden ">
        <h3>Cita de especialista</h3>
         <div class="input form-group">
             <label for="dni">DNI:</label>
             <input type="text" name="dni" id="dniEm" placeholder="Dni del paciente" class="">
             <p id="avisoEm">El paciente está <span id="registradoEm" class="bg-danger">registrado</span></p>
          </div>
          <div id ="espec">
            
            <select name="tipo" id="tipo">Especialidad
                <option hidden selected>Seleccione una</option>
                <?php 
                foreach($rowsNotGeneral as $rowNg){
                  echo"<option name = '".$rowNg["especialidad"]."' value='".$rowNg["id_odontologo"]."' class = 'dentista'>".$rowNg["nombre"]." ".$rowNg["apellido"]." - ".ucfirst($rowNg["especialidad"])."</option>";
                }
                ?>
            </select>
            <p id="anuncio"></p>
          </div>
          <div id="fechaEm" class="mt-1">
             <label for="fecha-cita">Seleccione la fecha:</label>
             <input type="date" name="fecha-cita" id="fecha-cita">
            <p id ="avisoEm"></p>
          </div>
          <div id="horaReserva">
             <label for="hora">Seleccione la hora</label>
             <select name="hours" id="hours"></select>
          </div>
           <input type="submit" value="reservar" name ="reservar"class="p-1 m-2" id="reservar">
          </div>
        </form>
     </div>
    </div>
  </div>
 
  <div class="container listaRef">
  <div class="row">
      <div class="mt-3 col-6 bg-light mx-auto text-center">
        <div class="d-block my-2">
        <label for="CitasPorDNI">DNI del paciente</label>
        <input type="text" id="citasPorFechas">
        </div>
        <div>
          <input type="button" value="verificar" class="mb-2">
        </div>
        <div id="noRegistrado">
          <p>El cliente no está registrado</p>
        </div>
        <div class="d-flex justify-content-center">
        <table class="table table-striped-columns" id="citasPorClientes">
          <thead>
            <tr>
              <th scope ="col">#</th>
              <th scope ="col">Id Dent.</th>
              <th scope ="col">Fecha</th>
              <th scope ="col">Hora</th>
            </tr>
          </thead>
          <tbody id="tbody">
          </tbody>
        </table>
        </div>
      </div>
    </div>
  </div>

  <div class="container listaRef">
  <div class="row">
      <div class="mt-3 col-6 bg-light mx-auto text-center">
        <div class="p-2">
          <label for="fechaCitas">Fecha de las cita</label>
          <input type="date" name="fechaCitas" id="fechasCitas">
          <button class="px-2" id="fechaCitasButton">Ver citas por fecha</button>
        </div>
        <div id="tablaFechas"></div>
      </div>
    </div>
  </div>  
</section>
<script>
  const lista = document.querySelectorAll(".lista");
  const listaRef = document.querySelectorAll(".listaRef")

  function escondeTodos() {
  listaRef.forEach(function(elemento) {
    elemento.style.display = "none";
  });
}

escondeTodos()

  lista.forEach((elemento, index)=>{
    elemento.addEventListener("click",function(){
      escondeTodos()
      listaRef[index].style.display="block"
    })
  })

  //----------Esconder el mensaje de éxito si no se ha reservado la cita-------------------
  let contertado = document.getElementById("concertado");
  concertado.style.display="none";
  document.getElementById("concertado1").innerText!==""? concertado.style.display = "block": concertado.style.display ="none";

  //------LÓGICA DE LAS RESERVAS----------------------------------------------------------
  async function getRegistrado(dniInput){
  var dni =dniInput.value
  if(validarDNI(dni)){
    var URL = "../Ajax/getPacientes.php?dni="+encodeURI(dni);
    let respuesta = await returnMeResponse(URL);
    if(dni.length > 0){
      if(respuesta["registrado"]===true){
        return true
      }else{
        return false
      }
    }else{
      return false
    }
  }
  return false
}
  //----------ESCOGER FECHA QUE NO SEA FIN DE SEMANA--------------------------------------
   let inputDni =document.getElementById("inputDni")
   inputDni.style.display = "none"
   let avisoFecha = document.getElementById("avisoFecha")
   avisoFecha.style.display="none"
   let fecha = document.getElementById("fecha")
   fecha.addEventListener("input", function(){
    
    let fechaSeleccionada = new Date(fecha.value)
    let diaSeleccionado = fechaSeleccionada.getDay()
    if(diaSeleccionado == 0 || diaSeleccionado == 6){
      avisoFecha.style.display = "block";
      avisoFecha.innerText = "Debe escoger un día de semana";
      inputDni.style.display = "none"
    }else{
      avisoFecha.style.display = "none"
      inputDni.style.display = "block"
    }
    
   })
  //_______________________________________________________________________________________
  function returnMeResponse(URL){
    return fetch(URL)
    .then(response => {
        if(response.ok){
            return response.json();
        }
        throw new Error('La respuesta no fue exitosa');
    })
    .catch(error => {
        console.error('Error:', error);
        return []; 
    });
  }
  let divHora = document.getElementById("divHora");
  divHora.style.display="none";
  let hora = document.getElementById("hora");
  let divDentista = document.getElementById("divDentista");
  divDentista.style.display ="none";
  //-----VER SI ESTÁ REGISTRADO-------------
  let idPaciente = document.getElementById("dniPaciente");
  let aviso = document.getElementById("aviso");
  aviso.style.display = "none"
  let registrado = document.getElementById("registrado");
  let registrar = document.getElementById("registrar");
  registrar.style.display="none";
  let options = document.getElementById("options");



  function validarDNI(dni) {
    const formatoDNI = /^[0-9]{8}[a-zA-Z]$/;
    const formatoNIE =/^[YXYZ]\d{7}[A-Za-z]$/;
    if(formatoDNI.test(dni)){
        return true;
    }else{
        return formatoNIE.test(dni);
    }
}

const diasSemana = ["Domingo","Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"]

idPaciente.addEventListener("input", async function() {
  let estaRegistrado = await getRegistrado(idPaciente);
  if (estaRegistrado) {
    aviso.style.display = "block";
    registrado.classList.replace("bg-danger", "bg-success");
    divHora.style.display = "block";
  } else {
    aviso.style.display = "none";
    registrado.classList.replace("bg-success", "bg-danger");
    divHora.style.display = "none";
  }
});
 
hora.addEventListener("change", async function(){
  let horaValor = parseInt(hora.value);
  var URL = "../Ajax/getHoraDisponible.php?hora="+encodeURIComponent(horaValor);
  
  options.innerHTML="";
  try{
    let array = await returnMeResponse(URL);
    divDentista.style.display ="block";
     array.forEach(nombre=>{
    let opt = document.createElement("option");
    opt.value = nombre["nombre"];
    opt.innerText = nombre["nombre"]+" "+nombre["apellido"];
    options.appendChild(opt);
  });
  }catch(error){
    divDentista.style.display ="none";
    console.error('Error:', error);
        return [];
  }
});

options.addEventListener("change", function() {
  let selectedOption = options.value; 
  registrar.style.display="block";
});
   
//----------REGISTRO PARA ESPECIALISTA--------------------------------
  let anuncio = document.getElementById("anuncio")
  anuncio.style.display="none"
  let idPacienteEm = document.getElementById("dniEm")
  let avisoEm = document.getElementById("avisoEm")
  avisoEm.style.display = "none"
  let registradoEm = document.getElementById("registradoEm");
  const espec = document.getElementById("espec")
  espec.style.display="none"
  const reservado =document.getElementById("reservar");
  reservado.style.display="none";

  idPacienteEm.addEventListener("input", async function() {
  var estaRegistrado = await getRegistrado(idPacienteEm);
  if (estaRegistrado) {
    avisoEm.style.display = "block";
    registradoEm.classList.replace("bg-danger", "bg-success");
    espec.style.display="block"
  } else {
    avisoEm.style.display = "block";
    registradoEm.classList.replace("bg-success", "bg-danger");
    espec.style.display="none"
  }
});
document.getElementById("horaReserva").style.display="none";
let dias = []
const dentistas = document.querySelectorAll(".dentista");
let arrayDentistas = [];
dentistas.forEach(function(elemento) {
    let idDentista = elemento.getAttribute("value");
    console.log(idDentista)
    arrayDentistas.push(idDentista);
});
let fechaCita= document.getElementById("fecha-cita")
let tipo = document.getElementById("tipo")
tipo.addEventListener("change", async function(){
  anuncio.innerText="";
  let hours = document.getElementById("hours")
  hours.innerHTML="";
  let horas=[]
  let dias = [];
  let id = this.options[this.selectedIndex];
  idDentista = id.getAttribute("value");
  var URL = "../Ajax/getHorasEspecialista.php?id=" + encodeURI(idDentista);
  var response = await returnMeResponse(URL);
  response.forEach((item) => {
    console.log(item)
     dias.push(item.dia);
   
  });
  console.log(response)
  anuncio.style.display="block"
  anuncio.innerText = "Los días disponibles son: " + dias.map(dia => diasSemana[dia]).join(", ")
  fechaCita.addEventListener("change", function(){
    horas.length = 0;
    hours.innerHTML="";
    console.log(horas.length==0)
    let fechaCitasValue = fechaCita.value;
    let fechaCitas = new Date(fechaCitasValue);
    let diaEspecialidad = fechaCitas.getDay();
    if(!dias.includes(diaEspecialidad)){
        fechaCitasValue = "";
        document.getElementById("horaReserva").style.display="none";
        reservado.style.display="none"
         // Vaciar el array de horas para evitar duplicados
    } else {
        document.getElementById("horaReserva").style.display="block";
        reservado.style.display="block"
        reservado.classList.add("text-center")
        response.forEach((item) => {
            if(item.dia === diaEspecialidad){
                var i = parseInt(item.hora_inicio);
                while(i < parseInt(item.hora_fin)){
                    horas.push(i);
                    i++;
                }
            }
        });
        // Llenar el select de horas con las horas disponibles
        horas.forEach((hora) => {
            var opcion = document.createElement("option");
            opcion.innerText = hora + ":00";
            opcion.value = hora;
            hours.appendChild(opcion);
        });
        
    }
});
  
});



//_______________________BUSCAR CITAS QUE HA TENIDO UN PACIENTE______________________________//
document.getElementById("noRegistrado").style.display = "none";
// let table = document.getElementById("citasPorClientes");
// table.style.display = "none";

document.querySelector('input[type="button"][value="verificar"]').addEventListener("click", async function() {
    var array = [];
    var registrado = await getRegistrado(document.getElementById("citasPorFechas"));
    
    if (registrado) {
        document.getElementById("noRegistrado").style.display = "none";
        // table.style.display = "block"; // Aquí se aplica el estilo a la tabla principal
        var URL2 = "../Ajax/getCitas.php?dni=" + encodeURIComponent(document.getElementById("citasPorFechas").value);
        var response = await returnMeResponse(URL2);
        var tabla = document.getElementById("tbody");
        tabla.innerHTML = "";
        if (response && response.fechas && Array.isArray(response.fechas)) {
            response.fechas.forEach((fila, index) => {
                var tr = document.createElement("tr");
                tr.setAttribute("scope", index);
                for (var key in fila) {
                    if (fila.hasOwnProperty(key)) {
                        if (key === "id_paciente") {
                            continue;
                        }
                        var td = document.createElement("td");
                        td.innerText = key === "hora" ? fila[key] + ":00" : fila[key];
                        tr.appendChild(td);
                    }
                }
                tabla.appendChild(tr);
            });
        } else {
            console.error("Invalid response format:", response);
        }
    } else {
        document.getElementById("noRegistrado").style.display = "block";
        // table.style.display = "none"; // Aquí se aplica el estilo a la tabla principal
    }
});


//________________________PARA VER LAS CITAS POR FECHA______________________________________
let fechaCitas = document.getElementById("fechasCitas");
let botonFecha = document.getElementById("fechaCitasButton");
let tablaFechas = document.getElementById("tablaFechas");
botonFecha.addEventListener("click", async function() {
  var array = [];
  var URL = "../Ajax/getFechas.php?fecha=" + encodeURIComponent(fechaCitas.value);
  var response = await returnMeResponse(URL);
  if(response.fechas.length > 0) {
    console.log(response);
    var table = document.createElement("table");
    table.classList.add("mx-auto")
    var thead = document.createElement("thead");
    var cabeceras = Object.keys(response.fechas[0]);
    var tr = document.createElement("tr");
    cabeceras.forEach((element, index) => {
      var th = document.createElement("th");
      th.classList.add("m-4")
      th.innerText = element;
      th.setAttribute("scope", index);
      tr.appendChild(th);
    });
    thead.appendChild(tr);
    var tbody = document.createElement("tbody");
    response.fechas.forEach((fecha, index) => {
      var tr = document.createElement("tr");
      for(var key in fecha) {
        var td = document.createElement("td");
        td.innerText = fecha[key];
        tr.appendChild(td);
      }
      tbody.appendChild(tr);
    });
    table.appendChild(thead);
    table.appendChild(tbody);
   
    tablaFechas.innerHTML = "";
    tablaFechas.appendChild(table);
  }else{
    if(tablaFechas.querySelector(table)){
      table.remove
      tablaFechas.innerHTML="<p class='text-center'>No hay citas en esta fecha </p>"
    }else{
      tablaFechas.innerHTML="<p class='text-center'>No hay citas en esta fecha </p>"
    }
  }
});


</script>
</body>
</html>