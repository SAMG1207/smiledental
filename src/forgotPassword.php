<?php
include 'includes/autoloader.inc.php';
session_start();
if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST["correo"])){
    $email = filter_var($_POST['correo'], FILTER_SANITIZE_EMAIL);
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        $paciente = new Paciente();
        if($paciente->selectPaciente($email)){
            $_SESSION['correo'] = $email;
            header('Location: getCode.php');
            exit;
        }else{
            echo"<script> alert('Usuario no registrado')</script>";
            header('Location: register.php');
            exit;
        }
    }
}
/**
 * Este codigo debería enviar un correo al usuario. Al trabajar en local se ha optado
 * por esta forma, de publicar esta aplicación este debe ser corregido ya que si no, sería un fallo tremendo en la seguridad
 * El desarrollador realiza esto con fines ilustrativos
 * 
 * this code should send an email to the user with the code. Since this project is done on a local server, the developer made this
 * work in another page instead of an email. If this web app is launched, this must be fixed, 
 * otherwise this would be a tremendous security failure
 */
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content="Clinica dental Madrid">
    <meta name="keywords" content="implante, dolor, molestia, madrid, Madrid,">
    <meta name="author" content="Sergio Moreno">
    <title>SmileDental - Portal pacientes</title>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    
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
    
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario">
            <h1 class = tituloForm>Área Pacientes SmileDental</h1>
              <div class = "input">
                <label for="nombre" class="letras">Introduce tu correo</label>
                <input type="text" name="correo" id="correo" required>
                <p id = "anuncio"></p>
                </div>

                <input type="submit" value="login" id="envio">
            
        </form>
   </section>

     <script>
        let formulario = document.getElementById("formulario");
        setTimeout(function() {
       formulario.style.opacity = "1"; }, 1200);
    </script> 
</body>
</html>
