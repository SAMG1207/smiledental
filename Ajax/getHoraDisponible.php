<?php
include '../src/includes/autoloader.inc.php';
if($_SERVER['REQUEST_METHOD'] ==="GET" && isset($_GET['hora'])){
   $dentist = new Dentist();
   try{
    echo $dentist->selectDentistGeneralPorHora($_GET["hora"]);
   }catch(Exception $e){
      echo"error:".$e->getMessage();
   }
   
}