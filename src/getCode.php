<?php
session_start();
if(isset($_SESSION['correo'])){
  
    function generarPalabraRandom($longitud) {
        $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ123456789';
        $palabraAleatoria = str_shuffle($caracteres);
        $palabraAleatoria = substr($palabraAleatoria, 0, $longitud);
        return $palabraAleatoria;
    }
    $palabraGenerada = generarPalabraRandom(8);
    $_SESSION['codigo'] = $palabraGenerada;

    ?>

    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="icon" type="image/png" href="img/cepillo.png">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <title>Obtener nuevo password</title>
    </head>
    <body class="d-flex justify-content-center align-items-center vh-100">
        <div class="text-center">
        <p>Para poder obtener una nueva contraseña, copia este codigo: <span id ="codigo"><?php echo $palabraGenerada ?></span> </p>
        
        <button id = "boton">Copiar</button>
        <p style ="color: green" id ="aviso">copiado</p>
        <p>Con este codigo copiado, clickea este enlace para reestablecer tu contraseña: <a href="newPassword.php">Aquí! :D</a></p>
        </div>
        
        
        <script>
            let codigo = document.getElementById("codigo");
            let boton = document.getElementById("boton");
            let aviso = document.getElementById("aviso");
            aviso.style.display="none";
            boton.addEventListener("click", function(){
                var rango = document.createRange();
                rango.selectNode(codigo);
                window.getSelection().addRange(rango);
                try{
                  document.execCommand('copy');
                  aviso.style.display = "block";
                }catch{
                   console.log("Error al copiar");
                   aviso.style.display = "none";
                }
                window.getSelection().removeAllRanges();
               
            });
        </script>
    </body>
    </html>
<?php


}else{
  echo'ESTAS EN EL ELSE2';}
?>