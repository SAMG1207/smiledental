
<?php
session_start();
include '../src/includes/autoloader.inc.php';

$dentist = new Paciente();
echo $dentist->alreadyIn("06342363K");