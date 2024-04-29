<?php
 Class Paciente extends Query{


public function alreadyIn($dni){
    $sql ="SELECT * FROM pacientes WHERE dni = ?;";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $dni);
    $stmt->execute();
    $row = $stmt->fetch();
    $this->close();
    return $row;
}

public function searchPacienteBynName($nombre){
  $sql = "SELECT * FROM pacientes WHERE nombre = ?";
  $stmt = $this->connect()->prepare($sql);
  $stmt->bindParam(1, $nombre);
  $stmt->execute();
  $row = $stmt->fetchAll();
  $this->close();
  return $row;
}


public function insertar($nombre, $apellido, $telefono, $dni, $correo, $edad, $clave){
   if($this->correoValido($correo) === false || $this->selectPaciente($correo)){
    return false;
   }else{
    $passwordHash = password_hash($clave, PASSWORD_DEFAULT);
    $sql = "INSERT into pacientes (nombre, apellido, telefono, dni, correo, edad, clave) VALUES(?,?,?,?,?,?,?)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $nombre);
    $stmt->bindParam(2, $apellido);
    $stmt->bindParam(3, $telefono);
    $stmt->bindParam(4, $dni);
    $stmt->bindParam(5, $correo);
    $stmt->bindParam(6, $edad);
    $stmt->bindParam(7, $passwordHash);
    $stmt->execute();
    $this->close();
    return true;
   }
}

public function logIn($correo, $clave){
    $row = $this->selectPaciente($correo);
    //Esto para ver si existe
    if ($row==false) {
        return false;
    } else {
        return password_verify($clave, $row['clave']);
    }
  }

  
function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return  $data;
}
    function correoValido($correo){
    return filter_var($correo, FILTER_VALIDATE_EMAIL);
    }

    public function selectPaciente($correo){
        $sql = "SELECT * FROM pacientes WHERE correo = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $correo);
        $stmt->execute();
        $row=$stmt->fetch();
        if($row!==false){
          $this->close();
          return $row;
        } else {
          return false;}
        
      }

      function cambioClave($correo, $clave){
        $existe = $this->selectPaciente($correo);
        if($existe){
            $claveNueva = password_hash($clave, PASSWORD_DEFAULT);
            $sql = 'UPDATE pacientes SET clave = ? WHERE correo = ?';
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(1, $claveNueva);
            $stmt->bindParam(2, $correo);
            $stmt->execute();
            // Verificar si se ha actualizado alguna fila
            if ($stmt->rowCount() > 0) {
                $this->close();
                return true; // La clave se actualizó correctamente
            } else {
                $this->close();
                return false; // No se actualizó ninguna fila (quizás la clave nueva sea igual a la anterior)
            }
        }
        return false; // El paciente no existe con ese correo
    }
    

      public function selectCorreoPacientes() {
        $sql = "SELECT correo FROM pacientes";
        $stmt = $this->connect()->prepare( $sql );
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $correosRegistrados = [];
        foreach ($rows as $row) {
          $correosRegistrados[] = $row['correo'];
        }
        $this->close();
        return $correosRegistrados;
      }

      public function getIdPaciente($correo){
        $sql = 'SELECT * FROM pacientes WHERE correo = ?';
        $stmt =$this->connect()->prepare($sql);
        $stmt->bindParam(1, $correo);
        $stmt->execute();
        $row = $stmt->fetch();
        if ($row) {
          $this->close();
          return $row['id_paciente'];
        } else {
          $this->close();
          return null;}
      }

      public function printPacientes(){
        $sql ="SELECT id_paciente, nombre, apellido, telefono, dni, correo FROM pacientes";
        return $this->executeQuery($sql);
      }
}

