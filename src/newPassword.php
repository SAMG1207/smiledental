<?php
session_start();
include 'includes/autoloader.inc.php';
if(isset($_SESSION['correo'])){
    var_dump($_SESSION["correo"]);
    if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['codigo']) && isset($_POST['clave']) && isset($_POST['clave1'])){
        if($_POST['codigo'] === $_SESSION['codigo']){
            $paciente = new Paciente();
            $email = $_SESSION['correo'];
            $registrado = $paciente->cambioClave($email, $_POST['clave']);
        
        }
        
    }
}


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Clinica dental Madrid">
    <meta name="keywords" content="implante, dolor, molestia, madrid, Madrid,">
    <meta name="author" content="Sergio Moreno">
    <title>Nueva Clave</title>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>
<body>
    <header class="cabecera">
        <div class="header itself">
            <div class="column side">
                <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'">
            </div>
            <div class="column main">
                <h1 class="titulo">SmileDental</h1>
                <span>Siempre lo mejor</span>
            </div>
        </div>
    </header>

    <section class="principal">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario">
            <h1 class="tituloForm">Área Pacientes SmileDental</h1>

            <div class="input">
                <label for="nombre" class="letras">Introduce el código copiado:</label>
                <input type="text" name="codigo" required>
            </div>

            <div class="input">
                <label for="clave" class="letras">Introduce tu contraseña</label>
                <input type="password" name="clave" class="pass" required id="pass">
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

            <div class="input">
                <label for="clave1" class="letras">Confirma tu contraseña</label>
                <input type="password" name="clave1" class="pass" required id="pass2">
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
                <div class="message" id="messageP2">
                    <h5>Ambas contraseñas deben coincidir</h5>
                    <p id="passConfirm" class="invalid">Sí <b>coinciden</b></p>
                </div>
            </div>

            <input type="submit" value="login" id="boton">
        </form>
    </section>

    <script>
        setTimeout(function() {
            formulario.style.opacity = "1";
        }, 1200);

        let iconos = document.querySelectorAll(".icono-vc");

        iconos.forEach(function(icono) {
            icono.addEventListener("mouseover", function() {
                const inputElement = icono.previousElementSibling;
                if (inputElement) {
                    inputElement.type = "text";
                }
            });
        });

        iconos.forEach(function(icono) {
            icono.addEventListener("mouseout", function() {
                const inputElement = icono.previousElementSibling;
                if (inputElement) {
                    inputElement.type = "password";
                }
            });
        });

        function wellPassword(password) {
            var regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@#$%^&*()<>?/\\|}{~:¡¿!?]).{8,}$/;
            return regex.test(password);
        }

        const myInput = document.getElementById("pass");
        var letter = document.getElementById("letter");
        var capital = document.getElementById("capital");
        var number = document.getElementById("number");
        var special = document.getElementById("special");
        var length = document.getElementById("length");

        myInput.onfocus = function() {
            document.getElementById("messageP1").style.display = "block";
        }

        myInput.onblur = function() {
            document.getElementById("messageP1").style.display = "none";
        }
        //funciones para evaluar que los inputs sean correctos
        //debe tener mayuscula,minuscula, numero y caracter especial

        myInput.onkeyup = function() {
            var inputVal = myInput.value;
            var inputValid = wellPassword(inputVal);
            var minuscula = /[a-z]/g;
            if (myInput.value.match(minuscula)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }

            var mayuscula = /[A-Z]/g;
            if (myInput.value.match(mayuscula)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }

            var numero = /[0-9]/g;
            if (myInput.value.match(numero)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }

            var especialChar = /[!@#$&?¿¡%()^*ç]/g;
            if (myInput.value.match(especialChar)) {
                special.classList.remove("invalid");
                special.classList.add("valid");
            } else {
                special.classList.remove("valid");
                special.classList.add("invalid");
            }

            if (myInput.value.length >= 8) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
        }

        /**Para Comprobar que coincidan ambas */
        var myInput2 = document.getElementById("pass2");
        var passConfirm = document.getElementById("passConfirm");

        myInput2.onfocus = function() {
            document.getElementById("messageP2").style.display = "block";
        }

        myInput2.onblur = function() {
            document.getElementById("messageP2").style.display = "none";
        }

        myInput2.onkeyup = function() {
            if (myInput2.value === myInput.value) {
                passConfirm.classList.remove("invalid");
                passConfirm.classList.add("valid");
            } else {
                passConfirm.classList.remove("valid");
                passConfirm.classList.add("invalid");
            }
        }

        let boton = document.getElementById("boton");

        boton.addEventListener("click", function(event) {
            event.preventDefault();
            const passValido = wellPassword(myInput.value);
            const coinciden = myInput2.value === myInput.value;

            console.log("passValido: " + passValido);
            console.log("coinciden: " + coinciden);

            if (passValido && coinciden) {
                formulario.submit();
            } else {
                alert("Por favor complete todas las validaciones");
            }
        });
    </script>
</body>
</html>
