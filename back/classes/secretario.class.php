<?php

Class Secretario extends Connection{

    public function alreadySecretario(){
        $sql = "SELECT * FROM secretario";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $row = $stmt->fetch();
         $this->close();
         return $row;
         // si devuelve es porque ya hay un secretario registrado y no se puede registrar otro con un usuario activo
    }
    public function setSecretario($nombre, $apellido, $dni,$correo, $clave, $telefono){
       $row = $this->alreadySecretario();
       if($row){  
        return false;  
    }else{
      $passwordHash = password_hash($clave, PASSWORD_DEFAULT);
      $sql = "INSERT INTO secretario(nombre, apellido, dni, correo, clave, telefono) VALUES (?,?,?,?,?,?)";
      $stmt = $this->connect()->prepare($sql);
      $stmt->bindParam(1, $nombre);
      $stmt->bindParam(2, $apellido);
      $stmt->bindParam(3, $dni);
      $stmt->bindParam(4, $correo);
      $stmt->bindParam(5, $passwordHash);
      $stmt->bindParam(6, $telefono);
      $stmt->execute();
      $this->close();
      return true;
    }
}

public function logIn($clave){
        $row = $this->alreadySecretario();
        $hash = $row["clave"];
        return password_verify($clave, $hash);
   
}

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return  $data;
}
}