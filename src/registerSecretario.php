<?php

include 'includes/autoloader.inc.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {
 
    try {
        $user = new Secretario();
        $nombre = $user->test_input(@$_POST['nombre']);
        $apellido = $user->test_input(@$_POST['apellido']);
        $email = "secretario@smiledental.com";
        $dni = $user->test_input(@$_POST['dni']);
        $pass = $user->test_input(@$_POST['clave']);
        $telefono = "655194518";
        $registro = $user->setSecretario($nombre, $apellido, $dni, $email, $pass, $telefono);
        
        if ($registro) {
            echo "<script> alert('Registro exitoso') </script>";
            echo "<script>setTimeout(function() {
                window.location.href = 'portalSecretario.php';
            }, 500);</script>";
           
        } else {
            echo "<script> alert('Registro fallido, cargo ya existente, será redirigido') </script>";
            echo "<script>setTimeout(function() {
                window.location.href = 'index2.html';
            }, 500);</script>";
        }
    } catch (PDOException $e) {
        echo "Error en la inserción: " . $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../src/javascript.js" defer></script>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Registro Secretario SmileDental</title>
    <script src="javascript/javascript.js" defer></script>
</head>
<body>
   
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
            <h1 class = tituloForm>Registro Secretario SmileDental</h1>

            <fieldset><h3 class="mb-1 text-center">Información Personal</h2></fieldset>

              <div class = "input form-label">
                <label for="nombre" class="letras">Introduce tu nombre</label>
                <input type="text" name="nombre" id="nombre"required>
                <div class="message" id="messageN">
                 <h5>Solo se admiten letras</h5>
                 <p id="comNombre" class="invalid"><b>Ok</b></p>
                </div>
              </div>

                <div class = "input form-label">
                <label for="apellido" class="letras">Introduce tu apellido</label>
                <input type="text" name="apellido" id="apellido" required>
                <div class="message" id="messageA">
                 <h5>Solo se admiten letras</h5>
                 <p id="comApellido" class="invalid"><b>Ok</b></p>
                </div>
                </div>


                <div class = "input form-label">
                <label for="dni" class="letras">Introduce tu DNI/NIE</label>
                <input type="text" name="dni" id="dni"required>
                <div class="message" id="messageD">
                 <h5>Solo se admite formato DNI/NIE</h5>
                 <p id="comDni" class="invalid"><b>Ok</b></p>
                 </div>
                </div>

                <div class = "input form-label">
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

                <div class = "input form-label">
                <label for="clave1" class="letras">Confirma tu contraseña</label>
                <input type="password" name="clave1" class="pass"required id="pass2" >
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
                <div class="message" id="messageP2">
                 <h5>Ambas contraseñas deben coincidir</h5>
                 <p id="passConfirm" class="invalid">Sí <b>coinciden</b></p>
                </div>
                </div>
                
                <input type="submit" value="login" id ="botonS">
        </form>

   </section>
<script>

    const botonS = document.getElementById("botonS");
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
  var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*()<>?/\\|}{~:¡¿!?]).{8,}$/;
  // var regex = /^[a-zA-Z0-9@#$%^&*()<>?/\|}{~:!¡¿]{8,}$/;
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

    var especialChar =/[!@#$&?¿¡%()^*ç]/g;
    if(myInput.value.match(especialChar)){
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
 if(typeof botonS !== undefined){
  botonS.addEventListener("click",function(event){
    event.preventDefault();
    const nombreValido = wellName(nombre.value);
    const apellidoValido = wellName(apellido.value);
    const dniValido = wellDni(dni.value);
    const passValido = wellPassword(myInput.value);
    const coinciden = myInput2.value === myInput.value; 
  
    if(
      nombreValido &&
      apellidoValido &&
      dniValido&&
      passValido&&
      coinciden){
        formulario.submit();
      }else {
        alert("Por favor complete todas las validaciones");
      }
  })
 }
</script>
</body>
</html>