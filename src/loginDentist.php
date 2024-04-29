<?php
session_start();
include 'includes/autoloader.inc.php';
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
}

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['correo']) && isset($_POST['clave']) && ($_POST["token"] === $_SESSION["token"])){
    $dentist = new Dentist();
    $correo = $dentist->test_input($_POST['correo'])? $_POST['correo']:"";
    $clave = $dentist->test_input($_POST['clave'])? $_POST['clave']:"";
    $datos = $dentist->selectDentist($correo);
 
    if(password_verify($clave, $datos['clave']) === true){
        var_dump($datos);
         $_SESSION['dentist'] = $datos['nombre']." ".$datos['apellido'];
         $_SESSION['dentist_id'] =$datos['id_odontologo'];
         header('location: profileDentist.php');
         exit();
    }
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name = "description" content="Clinica dental Madrid">
    <meta name="keywords" content="implante, dolor, molestia, madrid, Madrid,">
    <meta name="author" content="Sergio Moreno">
    <title>SmileDental - Portal Dentista</title>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
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
            </div>
        </div>
    </header>

    <section class="principal">
        <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario">
            <h1 class = tituloForm>Dentista SmileDental</h1>

            <div class = "input">
                <label for="correo" class="letras">Introduce tu correo</label>
                <input type="email" name="correo" required>
                </div>

            <div class = "input">
                <label for="clave" class="letras">Introduce tu contraseña</label>
                <input type="password" name="clave" class="pass"required>
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
            </div>
            <div style="display: none;">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
            </div>

                <input type="submit" value="login">
                <div class="notInput">
             
                </div>
        </form>
   </section>
   
</body>
</html>