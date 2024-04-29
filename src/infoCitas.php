<?php
session_start();
include 'includes/autoloader.inc.php';

if (!isset($_SESSION['dentist']) || !isset($_GET["id_cita"])) {
    header('Location: loginDentist.php');
    exit();
} else {
    if ($_SERVER["REQUEST_METHOD"] == "POST"&& isset($_FILES['historia'])) {
        $query = new Query();

        $idCita = $query->test_input($_GET["id_cita"])?$_GET["id_cita"] : "";
     

        // Obtener información del archivo
        $fileName = $_FILES['historia']['name'];
        $fileTmpName = $_FILES['historia']['tmp_name'];

        // Leer el contenido del archivo
        $fileContent = file_get_contents($fileTmpName);

        $cita = new Cita();
        $registro = $cita->updateDocs($idCita, $fileName, $fileContent);

        if($registro){
            echo "<script> alert('Historia subida con éxito'); </script>";
            header('location: profileDentist.php');
            exit();
        }else {
            echo "<script> alert('No se ha podido subir la historia'); </script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="icon" type="image/png" href="img/cepillo.png">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script defer src="portal.js"></script>
    <link rel="stylesheet" href="css/perfiles.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Área Dentista SmileDental</title>
</head>
<body>
<header class="container-fluid bg-primary">
 <div class="row align-items-center p-3">
    <div class ="col-sm-6 col-md-3 text-center"> <img id="logo" src="img/logo.svg" alt="imagen" onerror="this.src='img/logo.jpg'"></div>
    <div class ="col-sm-6 col-md-9 text-center d-block "> 
     <h1> Área Dentista Smile Dental</h1>
     <h3>Bienvenido Dr. <?php echo $_SESSION['dentist'];?></h3>
     <a href="loginDentist.php" class ="d-block">Volver</a>
    </div>
 </div>
</header>
<section class="principal">
    <div class="row text-center justify-content-center">
        <div class="col-6 mt-5">
            <form action="" method="post" class="bg-light" enctype="multipart/form-data">
                <h4>Este Apartado es para subir información sobre la cita</h4>
                <div class="input">
                <label for="historia">Subir documentación de la cita</label>
                <input type="file" name="historia" id="" accept=".pdf">
                </div>
                <input type="submit" value="Enviar informarción" class="mt-3 mb-3">
            </form>
        </div>
    </div>
</section>
</body>
</html>