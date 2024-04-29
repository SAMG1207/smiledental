<?php
session_start();
include 'includes/autoloader.inc.php';
if (!isset($_SESSION["token"])) {
    $_SESSION["token"] = md5(uniqid(mt_rand(), true));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && ($_POST["token"] === $_SESSION["token"])) {
    $clave = $_POST['clave'];
    $secretario = new Secretario();
    $row = $secretario->alreadySecretario();
    $login = $secretario->logIn($clave);
    
    if ($login) {
        $_SESSION['secretario'] = 'Secretario';
        header("Location:profileSecretario.php");
        exit();
       
    } else {
        echo "<script> alert('Contraseña incorrecta') </script>";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Sergio Moreno">
    <title>SmileDental - Portal Secretario</title>
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
        <div>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="POST" class="form" id="formulario">
            <h1 class = tituloForm>Secretario SMILEDENTAL</h1>

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
                <a href="registerSecretario.php">Registro de secretario</a>
                </div>
        </form>

    
   </section>
   
</body>
</html>