<?php
include_once "../back/classes/connect_pass.class.php";
$pass = new Pass();
$password = $pass->giveMeG("mapa");
echo json_encode($password);