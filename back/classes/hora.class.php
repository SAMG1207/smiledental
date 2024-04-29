<?php

Class Hora extends Query{

    /**
     * Clase para obtener las horas libres y mostrarla por pantalla de los distintos odontologos generales 
     */
    public function getIdGenerales(){
        //Obetenemos la información en un array de los odontologos generales, su id, hora inicio y hora de fin de turno
        $sql = "SELECT id_odontologo, hora_inicio, hora_fin FROM general";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC); // Obtener los resultados como un array asociativo
        $this->close();
    
        $result = [];
        foreach ($rows as $row) {
            $result[] = [
                "id_dentista" => $row["id_odontologo"],
                "horaI" => $row["hora_inicio"],
                "horaF" => $row["hora_fin"]
            ];
        }
    
        return $result;
    }
    

    public function getHorasOcupadas($fecha){
        //Obtenemos todas las horas ocupadas de los dentistas que son generales, los que obtuvimos en la funcion getIdGeneral()
        $generales = array_column($this->getIdGenerales(), 'id_dentista');
        $sql = "SELECT id_dentista, hora FROM citas WHERE fecha = ? AND id_dentista IN (" . implode(',', $generales) . ")";
        $stmt = $this->connect()->prepare($sql); 
        $stmt->execute([$fecha]);
        $row = $stmt->fetchAll();
        $this->close();
        return $row;
    }
    public function getTodasHoras(){
        //Obtenemos un array con todas las de los dentistas generales, desde que empiezan hasta que se van de la clínica
        $dentistas = $this->getIdGenerales();
        $horas=[];
        for($i=0; $i < count($dentistas); $i++){
            for($j = $dentistas[$i]['horaI']; $j < $dentistas[$i]["horaF"]; $j++){
                array_push($horas, ["id_dentista" => $dentistas[$i]["id_dentista"], "hora"=>$j]);
            }
        }
        return $horas;
    }

    public function getHorasLibres($fecha){
        //Comparamos el array de las horas ocupadas con el de todas las horas y los valores que coincidan son eliminados para obtener las horas libres
        $ocupadas = $this->getHorasOcupadas($fecha);
        $todas = $this->getTodasHoras();
        $ocupadasSerialized = array_map('serialize', $ocupadas); //obtenemos la información serialiazada de la bbdd
        $todasSerialized = array_map('serialize', $todas);//obtenemos la información seriliazada de la bbdd
        $disponibleSerialized = array_diff($todasSerialized, $ocupadasSerialized);//Comparamos esta información para descartar
        $disponible = array_map('unserialize', $disponibleSerialized);//Obtenemos las horas con deserielización
        return $disponible;// devolvemos un array con las horas disponiles. 
    }
    
    
    public function getHoraLibrePorNumero($fecha){
        // Obtendremos valores únicos, es decir, si la hora esta en horas disponibles puede salir repetida
        $disponible = $this->getHorasLibres($fecha);
        $horas = array_column($disponible, 'hora'); //Obtenemos las horas
        sort($horas); //ordenamos por horas
        $horas = array_unique($horas); //obtenemos los valores únicos
        return $horas;
    }
    
    public function getIdGeneral($hora){
        //Obtenemos los dentistas disponibles en la hora que ha marcado el paciente
        $sql = 'SELECT id_odontologo FROM general WHERE ? BETWEEN hora_inicio AND hora_fin';
        $stmt = $this->connect()->prepare($sql);
        $stmt->bindParam(1, $hora);
        // $stmt->execute([$hora]);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN); // Obtiene todos los valores de la columna como un array
        $this->close();
        return array_map('intval', $rows); // Convierte todos los valores a enteros y retorna el array
    }
    
    public function revisaCitas($fecha, $hora){
        //Obtenems el id_dentista de los dentistas que tienen cita en una fecha y hora 
        $sql = 'SELECT id_dentista FROM citas WHERE fecha = ? AND hora = ?';
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([$fecha, $hora]);
        $rows = $stmt->fetchAll(PDO::FETCH_COLUMN); // Obtiene todos los valores de la columna como un array
        $this->close();
        return array_map('intval', $rows); // Convierte todos los valores a enteros y retorna el array
    }
    
}    