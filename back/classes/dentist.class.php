<?php
Class Dentist extends Query{
     /**
      * Esta clase está relacionada para importar y extraer datos que son nececarios para la funcion del dentista
      */
     public function insertaDentist($nombre, $apellido, $dni, $colegiado, $correo, $clave, $telefono, $especialidad){
        if($this->alreadyIn($dni)){
            return false;
        }else{
             $date = date("Y-m-d");
             $password = password_hash($clave, PASSWORD_DEFAULT);
             $sql = "INSERT INTO dentist(nombre, apellido, dni, nro_colegiado, correo, clave, telefono, especialidad, fecha_alta) VALUES(?,?,?,?,?,?,?,?,?)";
             $stmt = $this->connect()->prepare($sql);
             $stmt->bindParam(1, $nombre);
             $stmt->bindParam(2, $apellido);
             $stmt->bindParam(3, $dni);
             $stmt->bindParam(4, $colegiado);
             $stmt->bindParam(5, $correo);
             $stmt->bindParam(6, $password);
             $stmt->bindParam(7, $telefono);
             $stmt->bindParam(8, $especialidad);
             $stmt->bindParam(9, $date);
             $stmt->execute();
             $this->close();
        }    
     }


     public function update($campo, $valor, $dni) {
        
            $sql = "UPDATE dentist SET $campo = ? WHERE dni = ?";
            $stmt = $this->connect()->prepare($sql);
            $stmt->bindParam(1, $valor);
            $stmt->bindParam(2, $dni);
            if($stmt->execute()){
            $this->close(); 
            return true;
            }else {
                var_dump($stmt->errorInfo());
            return false;}
    }
    

     public function deleteDentist($dni) {
        if (!$this->alreadyIn($dni)) {
            return false;
        }else{
        $sql = "DELETE FROM dentist WHERE dni = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        $this->close();
        return true;
        }
    }

    public function selectDisponibilidadNoGeneral($id){
      $sql = "SELECT dia, hora_inicio, hora_fin FROM disponibilidad WHERE id_dentista='$id'";
      $stmt = $this->connect()->prepare($sql);
      $stmt->execute();
      $row=$stmt->fetchAll();
      $this->close();
      return $row;
     }
     public function insertBaja($dni) {
        $sql = "INSERT INTO baja_dentistas (id_odontologo, nombre, apellido, dni, fecha_baja) VALUES (?,?,?,?,?);";
        $stmt = $this->connect()->prepare($sql);
        $row = $this->alreadyIn($dni);
    
        if (is_array($row)) {
            $id = $row['id_odontologo'];
            $nombre = $row['nombre'];
            $apellido = $row['apellido'];
            $thisDate = date("Y-m-d");
    
            try {
                $stmt->bindParam(1, $id);
                $stmt->bindParam(2, $nombre);
                $stmt->bindParam(3, $apellido);
                $stmt->bindParam(4, $dni);
                $stmt->bindParam(5, $thisDate);
                $stmt->execute();
                $this->close();
            } catch (PDOException $e) {
                error_log($e->getMessage());
                return false;
            }
        } else { return false;
          }return true;
    }
    
    
    public function alreadyIn($dni){
        $sql ="SELECT * FROM dentist WHERE dni = ?;";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $dni);
        $stmt->execute();
        $row = $stmt->fetch();
        $this->close();
        return $row;
    }

    public function getId($dni){
        $sql = "SELECT id_odontologo FROM dentist WHERE dni=?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$dni]);
         $row=$stmt->fetch();
      
         if ($row) {
          $this->close();
          return $row['id_odontologo'];
      } else {
          return null; 
      }
    }


      public function selectDentist($correo){
        $sql = "SELECT * FROM dentist WHERE correo = ?";
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $correo);
        $stmt->execute();
        $row = $stmt->fetch();
        $this->close();
        return $row;
        
      }

      public function selectDentistGeneral(){
        $sql = "SELECT id_odontologo, nombre, apellido FROM dentist WHERE especialidad = 'general'";
        $stmt= $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $this->close();
        return $rows;
      }

      public function selectDentistGeneralPorHora($hora){
        $sql = "SELECT dentist.id_odontologo, nombre, apellido, hora_inicio, hora_fin FROM dentist INNER JOIN general ON dentist.id_odontologo =general.id_odontologo  WHERE especialidad = 'general' AND ? BETWEEN hora_inicio AND hora_fin - 1";
        $stmt= $this->connect()->prepare($sql);
        $stmt->bindParam(1, $hora);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $this->close();
        if($stmt->rowCount() > 0){
          return json_encode($rows);
        }else{
          $mesaje = "No hay odontólogo a esta hora";
          return json_encode(array('error' => 'No hay odontólogos disponibles a esta hora'));
        }
        
      }

      public function selectDentistNotGeneral(){
        $sql = "SELECT id_odontologo, especialidad, nombre, apellido FROM dentist WHERE especialidad != 'general'";
        $stmt= $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll();
        $this->close();
        return $rows;
      }

      public function printDentist(){
        $sql ="SELECT id_odontologo, nombre, apellido, dni, nro_colegiado, telefono, especialidad, fecha_alta FROM dentist";
        return $this->executeQuery($sql);
       }

      public function printDentistBaja(){
        $sql ="SELECT id_odontologo, nombre, apellido, dni, fecha_baja FROM baja_dentistas";
        return $this->executeQuery($sql);
      }
 
}