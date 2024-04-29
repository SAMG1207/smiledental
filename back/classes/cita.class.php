<?php
Class Cita extends Query{

    /**
     * Creamos una clase para administrar las citas de la aplicación, entre esta y la clase "horas" manejaremos la disponibilidad
     * 
     */
    //ESTA CLASE NOS GENERA TODA LA INFORMACIÓN DE UNA CITA EN UNA FECHA, USADA PARA COMUNICACIÓN ASÍNCRONA
    public function getDisponibildad($fecha){
        $sql = 'SELECT * FROM citas WHERE fecha = ?';
        $stmt = $this->connect()->prepare($sql);  
        $stmt->bindParam(1, $fecha);
        $stmt->execute();
        $row=$stmt->fetchAll();
        $this->close();
        return $row;
       }

    //    public function revisaCitas($fecha, $hora){
    //     $sql = 'SELECT * FROM citas WHERE fecha = ? AND hora = ?';
    //     $stmt = $this->connect()->prepare($sql);
    //     $stmt->bindParam(1, $fecha);
    //     $stmt->bindParam(2, $hora);
    //     $stmt->execute();
    //     $row=$stmt->fetchAll();
    //     $this->close();
    //     return $row;
    //    }

    //esta clase genera la información sobre todas las citas 
       public function tieneCitaGeneral($id){
        $sql = 'SELECT * FROM citas WHERE id_paciente = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->execute();
        $row=$stmt->fetchAll();
        $this->close();
        return $row;
       }

       public function historicoCitas($id){
        $sql = 'SELECT * FROM citas WHERE id_dentista = ? and fecha < ?';
        $fecha = date('Y-m-d');
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $fecha);
        $stmt->execute();
        $row=$stmt->fetchAll();
        $this->close();
        return $row;
       }
       
      
       public function fechaCitasId($id, $hoy){
       
        $sql = 'SELECT * FROM citas WHERE id_paciente = ? AND fecha > ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $id);
        $stmt->bindParam(2, $hoy);
        $stmt->execute();
        $row=$stmt->fetch();
        $this->close();
        return $row;
       }

       
   public function fechaCitasDent($id, $hoy){
    $sql = 'SELECT * FROM citas WHERE id_dentista = ? AND fecha > ? ORDER BY fecha';
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $hoy);
    $stmt->execute();
    $row=$stmt->fetchAll();
    $this->close();
    return $row;
   }


   public function insertCita($id_paciente,$id_dentista, $fecha, $hora){
    $sql = 'INSERT INTO citas(id_paciente, id_dentista, fecha, hora) VALUES (?,?,?,?)';
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id_paciente);
    $stmt->bindParam(2, $id_dentista);
    $stmt->bindParam(3, $fecha);
    $stmt->bindParam(4, $hora);
    $stmt->execute();
    $this->close();
    return true;
   }

   public function updateDocs($id, $fileName, $fileContent){
    $sql = "INSERT INTO historia (id_cita, archivo) VALUES (?,?)";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $fileContent);
    $stmt->execute();
    $this->close();
    return true;
 }

 public function selectAllFromCitas($id){
    $sql ="SELECT archivo FROM historia WHERE id_cita=?";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row=$stmt->fetch();
    $this->close();
    return $row;
 }


public function getFecha($id_paciente){
    $sql = 'SELECT MAX(fecha) AS fecha_reciente FROM citas WHERE id_paciente = ?';
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id_paciente);
    $stmt->execute();
    $row=$stmt->fetch();
    $this->close();
    return $row['fecha_reciente'];
  }
  
  public function borrarCita($id_cita){
    $sql = 'DELETE FROM citas WHERE id_cita = :id';
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(':id', $id_cita, PDO::PARAM_INT);
    if ($stmt->execute()) {
        $this->close();
        return true;
    } else {
        $this->close();
        return false;
    }
  }

   function buscaCitaEspecialista($id, $fecha){
    $sql = 'SELECT hora FROM citas WHERE id_dentista = ? and fecha = ?';
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->bindParam(2, $fecha);
    $stmt->execute();
    $row=$stmt->fetchAll(PDO::FETCH_COLUMN, 0);
    $this->close();
    return $row;  
    
   }

//    function getHoraEntradaYSalida($id) {
//     $sql = 'SELECT hora_inicio, hora_fin FROM disponibilidad WHERE id_dentista = ?';
//     $stmt = $this->connect()->prepare($sql);
//     $stmt->bindParam(1, $id);
//     $stmt->execute();
//     $row = $stmt->fetch(PDO::FETCH_ASSOC);
//     $this->close();
//     return $row;
// }



   public function seleccionaUltimaCita($id){
    $sql ="SELECT MAX(fecha) FROM citas WHERE id_paciente = ? ";
    $stmt = $this->connect()->prepare($sql);
    $stmt->bindParam(1, $id);
    $stmt->execute();
    $row=$stmt->fetch(PDO::FETCH_ASSOC);
    $this->close();
    return $row;
   }
}