<?php
/**
 * Esta carpeta tiene el fin de generar más seguridad en el código
 * 
 */
$_SESSION['last_regeneration'] = time();
ini_set('session.use_only_cookies',1);
//Previe el secuestro de sesion 
ini_set('session.use_strict_mode',1);
//Ante cualquier amenaza se cierra la sesion 

session_set_cookie_params([
    /**
     * Vamos a establecer prametros:
     * la sesion funciona por 30 minutos
     * solo en el dominion localhost en este caso
     * 
     */
'lifetime' =>1800,
'domain' => 'localhost',
'path'=>'/',
'secure' => true,
'httponly'=> true
]);
session_start();

session_regenerate_id(true);

if(isset($_SESSION['last_regeneration'])){
    session_regenerate_id(true);
    $_SESSION['last_regeneration'] = time();

}else{
    $intervalo = 1800;
    if(time()-  $_SESSION['last_regeneration'] >= $intervalo ){
        session_regenerate_id(true);
        $_SESSION['last_regeneration'] = time();
    }
}