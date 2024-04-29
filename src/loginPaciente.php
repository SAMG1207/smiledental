<?php
session_start();
include 'includes/autoloader.inc.php';
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
}
$paciente = new Paciente();
if ($_SERVER['REQUEST_METHOD'] == 'POST'&& isset($_POST['correo']) && isset($_POST['clave'])&& ($_POST["token"] === $_SESSION["token"])) {
    $correo = $_POST['correo'];
    $correo=  filter_var($_POST["correo"], FILTER_SANITIZE_EMAIL);
    if(filter_var($correo, FILTER_VALIDATE_EMAIL)){
        $clave=$_POST['clave'];
        $row = $paciente->selectPaciente($correo);
        if($row === false){
            //Si no lo hay entonces se queda en la pagina
            echo "<script> alert('Usuario no encontrado')</script>";
        
        }else{
            $registrado = password_verify($clave, $row["clave"]);
            if($registrado){
                //si lo hay entonces crea una sesion "username" y nos redirige al profile
               $_SESSION['username'] = $row['nombre']." ".$row['apellido']; //Se crea la sesión 
               $_SESSION['email'] = $correo;
               header('location: profilePaciente.php');
               exit();
            }else{
                echo "<script> alert('Usuario no encontrado')</script>";
            }
    }
  }else{
    echo "<script> alert('Correo no es válido')</script>";
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
    <title>SmileDental - Portal pacientes</title>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="javascript/javascript.js" defer></script>
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
    <div class="container-fluid">
        <div class="row">
            <div class="col-8 mx-auto">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario">
            <h1 class = tituloForm>Área Pacientes SmileDental</h1>
              <div class = "form-label input">
                <label for="nombre" class="letras">Introduce tu correo</label>
                <input type="text" name="correo" required>
                </div>

                <div class = " form-label input">
                <label for="clave" class="letras">Introduce tu contraseña</label>
                <input type="password" name="clave" class="pass"required>
                <img src="img/ver.png" alt="mostrar" class="icono-vc">
                </div>
                <div style="display: none;">
                  <input type="hidden" name="token" value="<?php echo $_SESSION['token']?>">
                </div>
                <input type="submit" value="login">
        </form>
            </div>
       
             <div class="my-5 mx-auto col-md-6 text-center notInput">
                <a href="register.php">¿No estas registrado?</a>
                <a href="forgotPassword.php">He olvidado mi contraseña</a>
            </div>
        </div>
    </div>
   </section>
 
</body>
</html>