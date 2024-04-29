<?php

include 'includes/autoloader.inc.php';
$paciente = new Paciente();
$exito=null;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && 
isset($_POST["nombre"])&&
isset ($_POST["apellido"]) &&
isset($_POST["telefono"])&&
isset($_POST["correo"]) &&
isset($_POST["dni"])&&
isset ($_POST["fdn"])&&
isset($_POST["clave"])) {
  
  $datos = [
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['telefono'],
                $_POST['correo'],
                $_POST['dni'],
                $_POST['fdn'],
                $_POST['clave']
  ];
      $correcto = false; 
    foreach($datos as $dato){
      if(empty($dato)){
        $correcto = false;
        echo "<p>Por favor, revise el formulario </p>";
        return;
      }else{
        $correcto = true;
      }
    }
  
  if($correcto == true){
    $nombre = $paciente->test_input($_POST["nombre"])?$_POST["nombre"]:"";
    $apellido = $paciente->test_input($_POST["apellido"])?$_POST["apellido"]:"";
    $telefono = is_numeric($_POST["telefono"])?$_POST["telefono"]:"";
    $edad = is_numeric($_POST["fdn"])?$_POST["fdn"]:"";
    $edad >17?$edad:"";
    $edad<100?$edad:"";
    filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL);
    filter_var($_POST["correo"], FILTER_VALIDATE_EMAIL)? $correo = $_POST["correo"]: $correo="";
    $pass = $paciente->test_input($_POST["clave"])?$_POST["clave"]:"";
    $dni = $paciente->test_input($_POST["dni"])?$_POST["dni"]:"";

   if($paciente->alreadyIn($dni)){
    echo "<script> alert('Registro fallido, usuario ya existente, será reedirigido') </script>";
    echo "<script>setTimeout(function() {
        window.location.href = 'index.html';
    }, 500);</script>";
   
   }else{
    $registro = $paciente->insertar($nombre, $apellido,$telefono,$dni, $correo, $edad, $pass);
    $exito = true;
   }
    
  }
    }


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php echo "<script>let exito = " . json_encode($exito) . ";</script>"; ?>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Registro SmileDental</title>
    <script src="javascript/javascript.js" defer></script>
</head>
<body>
  <div class="container-fluid" id="anuncio">
    <div class="row text-center bg bg-dark">
      <p id ="anuncio1" class="text-light mt-1"></p>
    </div>
  </div>
       <header class="cabecera">
        
        <div class="header itself">
            <div class ="column side">
                <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'">
            </div>
            <div class ="column main">
                <h1 class="titulo">SmileDental</h1>
                <span>Siempre lo mejor</span>
            </div>
        </div>
    </header>
    
    <section class="principal">
        <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario" name="formulario" required>
            <h1 class = tituloForm>Área Pacientes SmileDental</h1>

            <fieldset><h3 class="mb-1 text-center">Información Personal</h2></fieldset>

              <div class = "input">
                <label for="nombre" class="letras">Introduce tu nombre</label>
                <input type="text" name="nombre" id="nombre"required>
                <div class="message" id="messageN">
                 <h5>Solo se admiten letras</h5>
                 <p id="comNombre" class="invalid"><b>Ok</b></p>
                </div>
                </div>

                <div class = "input">
                <label for="apellido" class="letras">Introduce tu apellido</label>
                <input type="text" name="apellido" id="apellido" required>
                <div class="message" id="messageA">
                 <h5>Solo se admiten letras</h5>
                 <p id="comApellido" class="invalid"><b>Ok</b></p>
                </div>
                </div>

                <div class = "input">
                <label for="telefono" class="letras">Introduce tu teléfono</label>
                <input type="text" name="telefono" id="telefono" required>
                <div class="message" id="messageT">
                 <h5>Teléfono</h5>
                 <p id="comTelefono" class="invalid"><b>Ok</b></p>
                </div>
                </div>

                <div class = "input">
                <label for="dni" class="letras">Introduce tu DNI/NIE</label>
                <input type="text" name="dni" id="dni"required>
                <div class="message" id="messageD">
                 <h5>Solo se admite formato DNI/NIE</h5>
                 <p id="comDni" class="invalid"><b>Ok</b></p>
                 </div>
                </div>


                <div class="input">
                    <label for="fdn">Introduce tu edad</label>
                     <input type="text" name="fdn" id="fdn">
                      <div class="message" id="messageF">
                       <h5>Debe ser mayor de edad</h5>
                         <p id="comFdn" class="invalid"><b>Ok</b></p>
                       </div>
                </div>

                <div class = "input">
                <label for="correo" class="letras">Introduce tu e-mail</label>
                <input type="text" name="correo" id="correo"required>
                <div class="message" id="messageC">
                 <h5>Solo se admiten letras</h5>
                 <p id="comCorreo" class="invalid"><b>Ok</b></p>
                </div>
                </div>
    

                <div class = "input">
                <label for="clave" class="letras">Introduce tu contraseña</label>
                <input type="password" name="clave" class="pass"required id="pass">
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
                <div class="message" id="messageP1">
                <h5>La contraseña debe contener:</h5>
                <p id="letter" class="invalid">Una <b>minúscula</b></p>
                <p id="capital" class="invalid">Una <b>mayúscula</b></p>
                <p id="number" class="invalid">un <b>número</b></p>
                <p id="special" class="invalid">Mínimo<b> un caracter especial !@#$&?¿¡%()^*ç</b></p>
                <p id="length" class="invalid">Mínimo <b>8 caracteres</b></p>
                </div>
                </div>

                <div class = "input">
                <label for="clave1" class="letras">Confirma tu contraseña</label>
                <input type="password" name="clave1" class="pass"required id="pass2" >
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
                <div class="message" id="messageP2">
                 <h5>Ambas contraseñas deben coincidir</h5>
                 <p id="passConfirm" class="invalid">Sí <b>coinciden</b></p>
                </div>
                </div>
                
                <input type="submit" value="login" id ="botonR">
                <a href="loginPaciente.php" class="text-center">Volver</a>
        </form>

   </section>
<script>
  const anuncio = document.getElementById("anuncio");
  anuncio.style.display="none";
  
   let anuncio1 = document.getElementById("anuncio1");

    const boton = document.getElementById("botonR");

    function wellName(nombreV){
    var regex = /^[a-zA-Z\s]{3,}$/g;
    return regex.test(nombreV);
  }

  const nombre = document.getElementById("nombre");
  let comNombre = document.getElementById("comNombre");

  nombre.onfocus = function(){
    document.getElementById("messageN").style.display="block";
  }

  nombre.onblur =function(){
    document.getElementById("messageN").style.display="none";
  }
nombre.onkeyup =function(){
   
  var nombreValue = nombre.value;
  var isNameValid = wellName(nombreValue);
  console.log(isNameValid);
    if(isNameValid){
        comNombre.classList.remove("invalid");
        comNombre.classList.add("valid");
        
    }else{
        comNombre.classList.remove("valid");
        comNombre.classList.add("invalid");
        
    }
 }
 //para comprobar el apellido
 const apellido=document.getElementById("apellido");
 let comApellido=document.getElementById("comApellido");
 apellido.onfocus = function(){
    document.getElementById("messageA").style.display="block";
  }

  apellido.onblur =function(){
    document.getElementById("messageA").style.display="none";
  }

  apellido.onkeyup =function(){
    
    var apellidoValue = apellido.value;
    var isLastValid = wellName(apellidoValue);
    console.log(isLastValid);
    if(isLastValid){
        comApellido.classList.remove("invalid");
        comApellido.classList.add("valid");
       
    }else{
        comApellido.classList.remove("valid");
        comApellido.classList.add("invalid");
       
    }
 }

 //para comprobar el dni
 function wellDni(dniV){
  var patronDni=/^\d{8}[a-zA-Z]$/g;
  var patronNie =/^[XYZxyz]\d{7}[A-Za-z]$/g;
  if(patronDni.test(dniV)||patronNie.test(dniV)){
    return true;
  }else {
    return false;
  }

 }

const dni = document.getElementById("dni")
 let comDni =document.getElementById("comDni");

 dni.onfocus = function(){
    document.getElementById("messageD").style.display="block";
  }

  dni.onblur =function(){
    document.getElementById("messageD").style.display="none";
  }

 dni.onkeyup=function(){
  var dniValue = dni.value;
  var dniValid = wellDni(dniValue);
 if(dniValid){
        comDni.classList.remove("invalid");
        comDni.classList.add("valid");
      
    }else{
        comDni.classList.remove("valid");
        comDni.classList.add("invalid");
       
 }
}

function wellPassword(password) {
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^*()?/\\|}{~:¡¿!?]).{8,}$/;
  return regex.test(password);
}

const myInput = document.getElementById("pass");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var special = document.getElementById("special");
var length = document.getElementById("length");

myInput.onfocus =function(){
    document.getElementById("messageP1").style.display ="block";
}

myInput.onblur = function(){
    document.getElementById("messageP1").style.display ="none";
}

myInput.onkeyup = function(){
  var inputVal = myInput.value;
  var inputValid = wellPassword(inputVal);
    var minuscula = /[a-z]/g;
    if(myInput.value.match(minuscula)){
        letter.classList.remove("invalid");
        letter.classList.add("valid");
    }else{
        letter.classList.remove("valid");
        letter.classList.add("invalid");
    }

    var mayuscula = /[A-Z]/g;
    if(myInput.value.match(mayuscula)){
        capital.classList.remove("invalid");
        capital.classList.add("valid");
    }else{
        capital.classList.remove("valid");
        capital.classList.add("invalid");
    }

    var numero = /[0-9]/g;
    if(myInput.value.match(numero)){
        number.classList.remove("invalid");
        number.classList.add("valid");
    }else{
        number.classList.remove("valid");
        number.classList.add("invalid");
    }

    var especialChar =/[!@#$?¿¡%()^*ç]/g;
    var invalidChars = /[<>&]/;
    if(myInput.value.match(especialChar) && !myInput.value.match(invalidChars)){
        special.classList.remove("invalid");
        special.classList.add("valid");
    }else{
        special.classList.remove("valid");
        special.classList.add("invalid");
    }

    if(myInput.value.length>=8){
        length.classList.remove("invalid");
        length.classList.add("valid");
    }else{
        length.classList.remove("valid");
        length.classList.add("invalid");
    }
}

/**Para Comprobar que coincidan ambas */
var myInput2 = document.getElementById("pass2");
var passConfirm =document.getElementById("passConfirm");

myInput2.onfocus =function(){
    document.getElementById("messageP2").style.display ="block";
}

myInput2.onblur = function(){
    document.getElementById("messageP2").style.display ="none";
}

myInput2.onkeyup =function(){
    if(myInput2.value === myInput.value){
        passConfirm.classList.remove("invalid");
        passConfirm.classList.add("valid");
    }else{
        passConfirm.classList.remove("valid");
        passConfirm.classList.add("invalid");
    }
}
//  


 //PAra comprobarel telefono 
function wellNumber(numberV){
  var regex = /^\d{9}$/;
  return regex.test(numberV);
}

const telefono = document.getElementById("telefono");
let comT = document.getElementById("comTelefono");
telefono.onfocus = function(){
  document.getElementById("messageT").style.display = "block";
}
telefono.onblur=function(){
  document.getElementById("messageT").style.display = "none";
}

telefono.onkeyup = function(){
  var tlfValue = telefono.value;
  var isTlfValid = wellNumber(tlfValue);
  if(isTlfValid){
    comT.classList.remove("invalid");
    comT.classList.add("valid");
  }else{
    comT.classList.remove("valid");
    comT.classList.add("invalid");
  }
}

//comprobar que es mayor de edad
function wellEdad(age){
const edadNumerica = parseInt(age, 10);
if(isNaN(edadNumerica)){
  return false;
}
if (edadNumerica<18){
return false;
}else{
  return true;
}
}

const edad = document.getElementById("fdn");
var comE = document.getElementById("comFdn");

edad.onfocus = function(){
  document.getElementById("messageF").style.display = "block";
}
edad.onblur=function(){
  document.getElementById("messageF").style.display = "none";
}

edad.onkeyup = function(){
  var edadV = edad.value;
  var edadBien = wellEdad(edadV);
  if(edadBien){
    comE.classList.remove("invalid");
    comE.classList.add("valid");
  }else{
    comE.classList.remove("valid");
    comE.classList.add("invalid");
  }
}

   //PARA COMPROBAR EL CORREO
   function validCorreo(correoV) {
    var patronCorreo = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return patronCorreo.test(correoV);
  }
  
  var correo = document.getElementById("correo");
  var comC = document.getElementById("comCorreo");
  
  correo.onfocus = function () {
    document.getElementById("messageC").style.display = "block";
  };
  
  correo.onblur = function () {
    document.getElementById("messageC").style.display = "none";
  };
  
  correo.onkeyup = function(){
    var correoValue = correo.value;
    var wellCorreo = validCorreo(correoValue);
    if (wellCorreo) {
      comC.classList.remove("invalid");
      comC.classList.add("valid");
    } else {
      comC.classList.remove("valid");
      comC.classList.add("invalid");
    }
  }
 


//Para enviar el formulario evaluamos que todas las funciones de validación sean true
boton.addEventListener("click", function(event){
event.preventDefault();
const nombreValido = wellName(nombre.value);
const apellidoValido = wellName(apellido.value);
const telefonoValido = wellNumber(telefono.value);
const dniValido = wellDni(dni.value);
const edadValida = wellEdad(fdn.value);
const edadCorreo = validCorreo(correo.value);
const passValido = wellPassword(myInput.value);
const coinciden = myInput2.value === myInput.value;

console.log("nombreValido: " + nombreValido);
console.log("apellidoValido: " + apellidoValido);
console.log("telefonoValido: " + telefonoValido);
console.log("dniValido: " + dniValido);
console.log("edadValida: " + edadValida);
console.log("edadCorreo: " + edadCorreo);
console.log("passValido: " + passValido);
console.log("coinciden: " + coinciden);

if(nombreValido &&
  apellidoValido &&
  telefonoValido&&
  dniValido&&
  edadValida&&
  edadCorreo&&
  passValido&&
  coinciden){
    formulario.submit();
  }else {
    alert("Por favor complete todas las validaciones");
  }
})
console.log(exito);
if(exito !== null){
   anuncio.style.display="block";
   anuncio1.innerText="Usuario registrado con éxito";
}

</script>
</body>
</html>